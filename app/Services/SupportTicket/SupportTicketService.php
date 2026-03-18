<?php

namespace App\Services\SupportTicket;

use App\Models\Authorization\User;
use App\Models\SupportTicket\SupportTicket;
use App\Models\SupportTicket\SupportTicketAttachment;
use App\Models\SupportTicket\SupportTicketComment;
use App\Models\SupportTicket\SupportTicketHistory;
use App\Services\Authorization\RolePermissionService;
use App\Services\Utility\FileHandlingService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class SupportTicketService
{
    public const STATUSES = ['open', 'in_progress', 'awaiting_response', 'closed'];

    public const CATEGORIES = ['technical', 'billing', 'account', 'general', 'other'];

    public const PRIORITIES = ['low', 'medium', 'high', 'urgent'];

    public function __construct(
        protected RolePermissionService $rolePermissionService,
        protected FileHandlingService $fileHandlingService,
    ) {
    }

    // This prepares the main support ticket page data for the current user.
    public function indexPageData(User $user): array
    {
        try {
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
        } catch (Throwable $exception) {
            Log::error('Failed to build support ticket index.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This prepares the support ticket detail page data for the selected ticket.
    public function showPageData(User $user, int $ticketId): array
    {
        try {
            $ticket = $this->findTicketForViewerOrFail($user, $ticketId);
            $baseData = $this->indexPageData($user);

            $baseData['selectedTicket'] = $ticket;
            $baseData['ticketComments'] = $this->commentsForTicket($ticketId);
            $baseData['ticketHistory'] = $this->historyForTicket($ticketId);
            $baseData['ticketAttachments'] = $this->attachmentsForTicket($ticketId);
            $baseData['canAddComment'] = $this->canCommentOnTicket($user, $ticket);
            $baseData['canUpdateStatus'] = $this->canUpdateTicketStatus($user);

            return $baseData;
        } catch (Throwable $exception) {
            Log::error('Failed to build support ticket detail.', ['ticket_id' => $ticketId, 'user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This creates a support ticket, its history row, and any uploaded attachments.
    public function createTicket(User $user, array $validated, array $attachments = []): int
    {
        try {
            if (! $this->canCreateTicket($user)) {
                throw new AuthorizationException('You are not allowed to create support tickets.');
            }

            return DB::transaction(function () use ($user, $validated, $attachments): int {
                $ticket = SupportTicket::query()->create([
                    'ticket_number' => $this->generateTicketNumber(),
                    'owner_user_id' => $user->id,
                    'owner_company_id' => $user->company_id,
                    'created_by_user_id' => $user->id,
                    'category' => $validated['category'],
                    'priority' => $validated['priority'],
                    'description' => $validated['description'],
                    'status' => 'open',
                    'last_activity_at' => now(),
                ]);

                $this->insertHistory((int) $ticket->id, 'created', $user->id, null, 'open', null, 'Ticket created.');
                $this->persistAttachments((int) $ticket->id, null, $user->id, $attachments);

                return (int) $ticket->id;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to create support ticket.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This adds a comment, updates ticket activity time, and stores any uploaded files.
    public function addComment(User $user, int $ticketId, string $comment, array $attachments = []): void
    {
        try {
            $ticket = $this->findTicketForViewerOrFail($user, $ticketId);

            if (! $this->canCommentOnTicket($user, $ticket)) {
                throw new AuthorizationException('You are not allowed to comment on this support ticket.');
            }

            DB::transaction(function () use ($user, $ticketId, $comment, $attachments): void {
                $ticketComment = SupportTicketComment::query()->create([
                    'support_ticket_id' => $ticketId,
                    'commenter_user_id' => $user->id,
                    'comment' => $comment,
                ]);

                SupportTicket::query()->whereKey($ticketId)->update(['last_activity_at' => now()]);

                $this->insertHistory($ticketId, 'comment_added', $user->id, null, null, (int) $ticketComment->id, 'Comment added.');
                $this->persistAttachments($ticketId, (int) $ticketComment->id, $user->id, $attachments);
            });
        } catch (Throwable $exception) {
            Log::error('Failed to add support ticket comment.', ['ticket_id' => $ticketId, 'user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This updates ticket status and records the change in ticket history.
    public function updateStatus(User $user, int $ticketId, string $newStatus): void
    {
        try {
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
                SupportTicket::query()->whereKey($ticket->id)->update([
                    'status' => $newStatus,
                    'last_activity_at' => now(),
                ]);

                $this->insertHistory((int) $ticket->id, 'status_changed', $user->id, (string) $ticket->status, $newStatus, null, 'Status updated.');
            });
        } catch (Throwable $exception) {
            Log::error('Failed to update support ticket status.', ['ticket_id' => $ticketId, 'user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This checks whether the user can create tickets.
    public function canCreateTicket(User $user): bool
    {
        try {
            if (! in_array($user->user_type, ['b2c', 'b2b', 'internal', 'admin', 'delegated_admin'], true)) {
                return false;
            }

            if ($this->rolePermissionService->hasPermission($user, 'tickets.create')) {
                return true;
            }

            return $user->user_type === 'internal'
                && $this->rolePermissionService->hasRole($user, 'internal_user_support');
        } catch (Throwable $exception) {
            Log::error('Failed to check ticket-create permission.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This checks whether the user can handle tickets across the system.
    public function canHandleTickets(User $user): bool
    {
        try {
            if ($this->rolePermissionService->hasPermission($user, 'tickets.handle')) {
                return true;
            }

            if ($this->rolePermissionService->hasRole($user, 'admin')
                || $this->rolePermissionService->hasRole($user, 'delegated_admin')) {
                return true;
            }

            return $user->user_type === 'internal'
                && $this->rolePermissionService->hasRole($user, 'internal_user_support');
        } catch (Throwable $exception) {
            Log::error('Failed to check ticket-handle permission.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This checks whether the user can view only their own tickets.
    protected function canViewOwnTickets(User $user): bool
    {
        try {
            return $this->rolePermissionService->hasPermission($user, 'tickets.view.own')
                || $this->rolePermissionService->hasPermission($user, 'tickets.create');
        } catch (Throwable $exception) {
            Log::error('Failed to check own-ticket visibility.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This checks whether the user can comment on the selected ticket.
    protected function canCommentOnTicket(User $user, object $ticket): bool
    {
        try {
            if ($this->canHandleTickets($user)) {
                return $this->rolePermissionService->hasPermission($user, 'tickets.comment.handle')
                    || $this->rolePermissionService->hasPermission($user, 'tickets.handle');
            }

            if ((int) $ticket->owner_user_id !== $user->id) {
                return false;
            }

            return $this->rolePermissionService->hasPermission($user, 'tickets.comment.own')
                || $this->rolePermissionService->hasPermission($user, 'tickets.view.own');
        } catch (Throwable $exception) {
            Log::error('Failed to check ticket comment permission.', ['user_id' => $user->id, 'ticket_id' => $ticket->id ?? null, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This checks whether the user can update ticket status.
    protected function canUpdateTicketStatus(User $user): bool
    {
        try {
            if (! $this->canHandleTickets($user)) {
                return false;
            }

            return $this->rolePermissionService->hasPermission($user, 'tickets.status.update')
                || $this->rolePermissionService->hasPermission($user, 'tickets.handle');
        } catch (Throwable $exception) {
            Log::error('Failed to check ticket status permission.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This returns visible tickets and flattens owner fields used by the current view.
    protected function listVisibleTickets(User $user): LengthAwarePaginator
    {
        try {
            if (! $this->canHandleTickets($user) && ! $this->canViewOwnTickets($user)) {
                throw new AuthorizationException('You are not allowed to view support tickets.');
            }

            $query = SupportTicket::query()
                ->with(['ownerUser:id,name', 'ownerCompany:id,name'])
                ->withCount('comments');

            if (! $this->canHandleTickets($user)) {
                $query->where('owner_user_id', $user->id);
            }

            $tickets = $query
                ->orderByRaw('COALESCE(last_activity_at, created_at) DESC')
                ->orderByDesc('id')
                ->paginate(15)
                ->withQueryString();

            $tickets->setCollection(
                $tickets->getCollection()->map(function (SupportTicket $ticket) {
                    $ticket->owner_name = $ticket->ownerUser?->name;
                    $ticket->owner_company_name = $ticket->ownerCompany?->name;
                    return $ticket;
                }),
            );

            return $tickets;
        } catch (Throwable $exception) {
            Log::error('Failed to list visible support tickets.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This loads one ticket for the viewer and blocks access outside the allowed scope.
    protected function findTicketForViewerOrFail(User $user, int $ticketId): object
    {
        try {
            if (! $this->canHandleTickets($user) && ! $this->canViewOwnTickets($user)) {
                throw new AuthorizationException('You are not allowed to access support tickets.');
            }

            $query = SupportTicket::query()
                ->with(['ownerUser:id,name', 'ownerCompany:id,name'])
                ->whereKey($ticketId);

            if (! $this->canHandleTickets($user)) {
                $query->where('owner_user_id', $user->id);
            }

            $ticket = $query->first();

            if (! $ticket) {
                throw new NotFoundHttpException('Support ticket not found.');
            }

            $ticket->owner_name = $ticket->ownerUser?->name;
            $ticket->owner_company_name = $ticket->ownerCompany?->name;

            return $ticket;
        } catch (Throwable $exception) {
            Log::error('Failed to load support ticket for viewer.', ['user_id' => $user->id, 'ticket_id' => $ticketId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This loads ticket comments and flattens commenter names for the current view.
    protected function commentsForTicket(int $ticketId): Collection
    {
        try {
            return SupportTicketComment::query()
                ->with('commenter:id,name')
                ->where('support_ticket_id', $ticketId)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get()
                ->map(function (SupportTicketComment $comment) {
                    $comment->commenter_name = $comment->commenter?->name;
                    return $comment;
                });
        } catch (Throwable $exception) {
            Log::error('Failed to load support ticket comments.', ['ticket_id' => $ticketId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This loads ticket history and flattens actor/comment data for the current view.
    protected function historyForTicket(int $ticketId): Collection
    {
        try {
            return SupportTicketHistory::query()
                ->with(['actor:id,name', 'comment:id,comment'])
                ->where('support_ticket_id', $ticketId)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get()
                ->map(function (SupportTicketHistory $history) {
                    $history->actor_name = $history->actor?->name;
                    $history->comment_text = $history->comment?->comment;
                    return $history;
                });
        } catch (Throwable $exception) {
            Log::error('Failed to load support ticket history.', ['ticket_id' => $ticketId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This loads ticket attachments and flattens uploader names for the current view.
    protected function attachmentsForTicket(int $ticketId): Collection
    {
        try {
            return SupportTicketAttachment::query()
                ->with('uploader:id,name')
                ->where('support_ticket_id', $ticketId)
                ->orderBy('created_at')
                ->orderBy('id')
                ->get()
                ->map(function (SupportTicketAttachment $attachment) {
                    $attachment->uploader_name = $attachment->uploader?->name;
                    return $attachment;
                });
        } catch (Throwable $exception) {
            Log::error('Failed to load support ticket attachments.', ['ticket_id' => $ticketId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This creates one support ticket history row.
    protected function insertHistory(
        int $ticketId,
        string $eventType,
        ?int $actorUserId,
        ?string $fromStatus,
        ?string $toStatus,
        ?int $commentId,
        ?string $message,
    ): void {
        try {
            SupportTicketHistory::query()->create([
                'support_ticket_id' => $ticketId,
                'event_type' => $eventType,
                'actor_user_id' => $actorUserId,
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'support_ticket_comment_id' => $commentId,
                'message' => $message,
                'created_at' => now(),
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to insert support ticket history.', ['ticket_id' => $ticketId, 'event_type' => $eventType, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This stores ticket attachments and links them to the ticket and optional comment.
    protected function persistAttachments(int $ticketId, ?int $commentId, int $actorUserId, array $attachments): void
    {
        try {
            foreach ($attachments as $attachment) {
                if (! $attachment instanceof UploadedFile) {
                    continue;
                }

                SupportTicketAttachment::query()->create([
                    'support_ticket_id' => $ticketId,
                    'support_ticket_comment_id' => $commentId,
                    'original_file_name' => $attachment->getClientOriginalName(),
                    // Step 1: save support attachments through the shared file helper so future storage changes stay centralized.
                    'stored_file_path' => $this->fileHandlingService->storeUploadedFile(
                        $attachment,
                        FileHandlingService::DOCUMENT_DIRECTORY.'/support-tickets/'.$ticketId,
                    ),
                    'file_size' => (int) ($attachment->getSize() ?? 0),
                    'mime_type' => $attachment->getClientMimeType(),
                    'uploaded_by_user_id' => $actorUserId,
                    'created_at' => now(),
                ]);
            }
        } catch (Throwable $exception) {
            Log::error('Failed to persist support ticket attachments.', ['ticket_id' => $ticketId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This generates a readable support ticket number.
    protected function generateTicketNumber(): string
    {
        try {
            return 'ST-'.now()->format('YmdHis').'-'.str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } catch (Throwable $exception) {
            Log::error('Failed to generate support ticket number.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}
