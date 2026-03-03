<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SupportTicketService
{
    public const STATUSES = ['open', 'in_progress', 'awaiting_response', 'closed'];

    public const CATEGORIES = ['technical', 'billing', 'account', 'general', 'other'];

    public const PRIORITIES = ['low', 'medium', 'high', 'urgent'];

    public function __construct(
        protected RolePermissionService $rolePermissionService,
    ) {
    }

    /**
     * @return array{
     *     tickets: LengthAwarePaginator,
     *     categories: array<int, string>,
     *     priorities: array<int, string>,
     *     statuses: array<int, string>,
     *     selectedTicket: object|null,
     *     ticketComments: Collection,
     *     ticketHistory: Collection,
     *     ticketAttachments: Collection,
     *     canCreateTicket: bool,
     *     canHandleTickets: bool,
     *     canAddComment: bool,
     *     canUpdateStatus: bool
     * }
     */
    public function indexPageData(User $user): array
    {
        return [
            'tickets' => $this->listVisibleTickets($user),
            'categories' => self::CATEGORIES,
            'priorities' => self::PRIORITIES,
            'statuses' => self::STATUSES,
            'selectedTicket' => null,
            'ticketComments' => collect(),
            'ticketHistory' => collect(),
            'ticketAttachments' => collect(),
            'canCreateTicket' => $this->canCreateTicket($user),
            'canHandleTickets' => $this->canHandleTickets($user),
            'canAddComment' => false,
            'canUpdateStatus' => false,
        ];
    }

    /**
     * @return array{
     *     tickets: LengthAwarePaginator,
     *     categories: array<int, string>,
     *     priorities: array<int, string>,
     *     statuses: array<int, string>,
     *     selectedTicket: object,
     *     ticketComments: Collection,
     *     ticketHistory: Collection,
     *     ticketAttachments: Collection,
     *     canCreateTicket: bool,
     *     canHandleTickets: bool,
     *     canAddComment: bool,
     *     canUpdateStatus: bool
     * }
     */
    public function showPageData(User $user, int $ticketId): array
    {
        $ticket = $this->findTicketForViewerOrFail($user, $ticketId);
        $baseData = $this->indexPageData($user);

        $baseData['selectedTicket'] = $ticket;
        $baseData['ticketComments'] = $this->commentsForTicket($ticketId);
        $baseData['ticketHistory'] = $this->historyForTicket($ticketId);
        $baseData['ticketAttachments'] = $this->attachmentsForTicket($ticketId);
        $baseData['canAddComment'] = $this->canCommentOnTicket($user, $ticket);
        $baseData['canUpdateStatus'] = $this->canUpdateTicketStatus($user);

        return $baseData;
    }

    /**
     * @param  array{category: string, priority: string, description: string}  $validated
     * @param  array<int, UploadedFile>  $attachments
     */
    public function createTicket(User $user, array $validated, array $attachments = []): int
    {
        if (! $this->canCreateTicket($user)) {
            throw new AuthorizationException('You are not allowed to create support tickets.');
        }

        return DB::transaction(function () use ($user, $validated, $attachments): int {
            $now = now();

            $ticketId = DB::table('support_tickets')->insertGetId([
                'ticket_number' => $this->generateTicketNumber(),
                'owner_user_id' => $user->id,
                'owner_company_id' => $user->company_id,
                'created_by_user_id' => $user->id,
                'category' => $validated['category'],
                'priority' => $validated['priority'],
                'description' => $validated['description'],
                'status' => 'open',
                'last_activity_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $this->insertHistory(
                $ticketId,
                'created',
                $user->id,
                null,
                'open',
                null,
                'Ticket created.',
            );

            $this->persistAttachments($ticketId, null, $user->id, $attachments);

            return $ticketId;
        });
    }

    /**
     * @param  array<int, UploadedFile>  $attachments
     */
    public function addComment(User $user, int $ticketId, string $comment, array $attachments = []): void
    {
        $ticket = $this->findTicketForViewerOrFail($user, $ticketId);

        if (! $this->canCommentOnTicket($user, $ticket)) {
            throw new AuthorizationException('You are not allowed to comment on this support ticket.');
        }

        DB::transaction(function () use ($user, $ticketId, $comment, $attachments): void {
            $now = now();

            $commentId = DB::table('support_ticket_comments')->insertGetId([
                'support_ticket_id' => $ticketId,
                'commenter_user_id' => $user->id,
                'comment' => $comment,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('support_tickets')
                ->where('id', $ticketId)
                ->update([
                    'last_activity_at' => $now,
                    'updated_at' => $now,
                ]);

            $this->insertHistory(
                $ticketId,
                'comment_added',
                $user->id,
                null,
                null,
                $commentId,
                'Comment added.',
            );

            $this->persistAttachments($ticketId, $commentId, $user->id, $attachments);
        });
    }

    public function updateStatus(User $user, int $ticketId, string $newStatus): void
    {
        if (! in_array($newStatus, self::STATUSES, true)) {
            throw new AuthorizationException('Invalid support ticket status transition request.');
        }

        $ticket = $this->findTicketForViewerOrFail($user, $ticketId);

        if (! $this->canUpdateTicketStatus($user)) {
            throw new AuthorizationException('You are not allowed to update support ticket status.');
        }

        if ($ticket->status === $newStatus) {
            return;
        }

        DB::transaction(function () use ($user, $ticket, $newStatus): void {
            $now = now();

            DB::table('support_tickets')
                ->where('id', $ticket->id)
                ->update([
                    'status' => $newStatus,
                    'last_activity_at' => $now,
                    'updated_at' => $now,
                ]);

            $this->insertHistory(
                (int) $ticket->id,
                'status_changed',
                $user->id,
                (string) $ticket->status,
                $newStatus,
                null,
                'Status updated.',
            );
        });
    }

    public function canCreateTicket(User $user): bool
    {
        if (! in_array($user->user_type, ['b2c', 'b2b', 'internal', 'admin', 'delegated_admin'], true)) {
            return false;
        }

        if ($this->rolePermissionService->hasPermission($user, 'tickets.create')) {
            return true;
        }

        return $user->user_type === 'internal'
            && $this->rolePermissionService->hasRole($user, 'internal_user_support');
    }

    public function canHandleTickets(User $user): bool
    {
        if ($this->rolePermissionService->hasPermission($user, 'tickets.handle')) {
            return true;
        }

        if ($this->rolePermissionService->hasRole($user, 'admin')
            || $this->rolePermissionService->hasRole($user, 'delegated_admin')) {
            return true;
        }

        return $user->user_type === 'internal'
            && $this->rolePermissionService->hasRole($user, 'internal_user_support');
    }

    protected function canViewOwnTickets(User $user): bool
    {
        return $this->rolePermissionService->hasPermission($user, 'tickets.view.own')
            || $this->rolePermissionService->hasPermission($user, 'tickets.create');
    }

    protected function canCommentOnTicket(User $user, object $ticket): bool
    {
        if ($this->canHandleTickets($user)) {
            return $this->rolePermissionService->hasPermission($user, 'tickets.comment.handle')
                || $this->rolePermissionService->hasPermission($user, 'tickets.handle');
        }

        if ((int) $ticket->owner_user_id !== $user->id) {
            return false;
        }

        return $this->rolePermissionService->hasPermission($user, 'tickets.comment.own')
            || $this->rolePermissionService->hasPermission($user, 'tickets.view.own');
    }

    protected function canUpdateTicketStatus(User $user): bool
    {
        if (! $this->canHandleTickets($user)) {
            return false;
        }

        return $this->rolePermissionService->hasPermission($user, 'tickets.status.update')
            || $this->rolePermissionService->hasPermission($user, 'tickets.handle');
    }

    protected function listVisibleTickets(User $user): LengthAwarePaginator
    {
        if (! $this->canHandleTickets($user) && ! $this->canViewOwnTickets($user)) {
            throw new AuthorizationException('You are not allowed to view support tickets.');
        }

        $query = DB::table('support_tickets as st')
            ->leftJoin('users as owners', 'owners.id', '=', 'st.owner_user_id')
            ->leftJoin('companies as owner_companies', 'owner_companies.id', '=', 'st.owner_company_id')
            ->select(
                'st.id',
                'st.ticket_number',
                'st.category',
                'st.priority',
                'st.status',
                'st.created_at',
                'st.updated_at',
                'st.last_activity_at',
                'owners.name as owner_name',
                'owner_companies.name as owner_company_name',
            )
            ->selectRaw('(SELECT COUNT(*) FROM support_ticket_comments c WHERE c.support_ticket_id = st.id) as comments_count');

        if (! $this->canHandleTickets($user)) {
            $query->where('st.owner_user_id', $user->id);
        }

        return $query
            ->orderByRaw('COALESCE(st.last_activity_at, st.created_at) DESC')
            ->orderByDesc('st.id')
            ->paginate(15)
            ->withQueryString();
    }

    protected function findTicketForViewerOrFail(User $user, int $ticketId): object
    {
        if (! $this->canHandleTickets($user) && ! $this->canViewOwnTickets($user)) {
            throw new AuthorizationException('You are not allowed to access support tickets.');
        }

        $query = DB::table('support_tickets as st')
            ->leftJoin('users as owners', 'owners.id', '=', 'st.owner_user_id')
            ->leftJoin('companies as owner_companies', 'owner_companies.id', '=', 'st.owner_company_id')
            ->select(
                'st.id',
                'st.ticket_number',
                'st.owner_user_id',
                'st.owner_company_id',
                'st.category',
                'st.priority',
                'st.description',
                'st.status',
                'st.created_at',
                'st.updated_at',
                'st.last_activity_at',
                'owners.name as owner_name',
                'owner_companies.name as owner_company_name',
            )
            ->where('st.id', $ticketId);

        if (! $this->canHandleTickets($user)) {
            $query->where('st.owner_user_id', $user->id);
        }

        $ticket = $query->first();

        if (! $ticket) {
            throw new NotFoundHttpException('Support ticket not found.');
        }

        return $ticket;
    }

    protected function commentsForTicket(int $ticketId): Collection
    {
        return DB::table('support_ticket_comments as c')
            ->leftJoin('users as commenters', 'commenters.id', '=', 'c.commenter_user_id')
            ->select(
                'c.id',
                'c.support_ticket_id',
                'c.comment',
                'c.created_at',
                'commenters.name as commenter_name',
            )
            ->where('c.support_ticket_id', $ticketId)
            ->orderBy('c.created_at')
            ->orderBy('c.id')
            ->get();
    }

    protected function historyForTicket(int $ticketId): Collection
    {
        return DB::table('support_ticket_history as h')
            ->leftJoin('users as actors', 'actors.id', '=', 'h.actor_user_id')
            ->leftJoin('support_ticket_comments as c', 'c.id', '=', 'h.support_ticket_comment_id')
            ->select(
                'h.id',
                'h.event_type',
                'h.from_status',
                'h.to_status',
                'h.message',
                'h.created_at',
                'actors.name as actor_name',
                'c.comment as comment_text',
            )
            ->where('h.support_ticket_id', $ticketId)
            ->orderBy('h.created_at')
            ->orderBy('h.id')
            ->get();
    }

    protected function attachmentsForTicket(int $ticketId): Collection
    {
        return DB::table('support_ticket_attachments as a')
            ->leftJoin('users as uploaders', 'uploaders.id', '=', 'a.uploaded_by_user_id')
            ->select(
                'a.id',
                'a.support_ticket_id',
                'a.support_ticket_comment_id',
                'a.original_file_name',
                'a.stored_file_path',
                'a.file_size',
                'a.mime_type',
                'a.created_at',
                'uploaders.name as uploader_name',
            )
            ->where('a.support_ticket_id', $ticketId)
            ->orderBy('a.created_at')
            ->orderBy('a.id')
            ->get();
    }

    protected function insertHistory(
        int $ticketId,
        string $eventType,
        ?int $actorUserId,
        ?string $fromStatus,
        ?string $toStatus,
        ?int $commentId,
        ?string $message,
    ): void {
        DB::table('support_ticket_history')->insert([
            'support_ticket_id' => $ticketId,
            'event_type' => $eventType,
            'actor_user_id' => $actorUserId,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'support_ticket_comment_id' => $commentId,
            'message' => $message,
            'created_at' => now(),
        ]);
    }

    /**
     * @param  array<int, UploadedFile>  $attachments
     */
    protected function persistAttachments(int $ticketId, ?int $commentId, int $actorUserId, array $attachments): void
    {
        foreach ($attachments as $attachment) {
            if (! $attachment instanceof UploadedFile) {
                continue;
            }

            $storedFilePath = $attachment->store('support-tickets/'.$ticketId, 'public');

            DB::table('support_ticket_attachments')->insert([
                'support_ticket_id' => $ticketId,
                'support_ticket_comment_id' => $commentId,
                'original_file_name' => $attachment->getClientOriginalName(),
                'stored_file_path' => $storedFilePath,
                'file_size' => (int) ($attachment->getSize() ?? 0),
                'mime_type' => $attachment->getClientMimeType(),
                'uploaded_by_user_id' => $actorUserId,
                'created_at' => now(),
            ]);
        }
    }

    protected function generateTicketNumber(): string
    {
        return 'ST-'.now()->format('YmdHis').'-'.str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}
