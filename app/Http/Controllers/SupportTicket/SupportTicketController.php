<?php

namespace App\Http\Controllers\SupportTicket;

use App\Http\Controllers\Controller;
use App\Services\SupportTicket\SupportTicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class SupportTicketController extends Controller
{
    // This renders the support ticket list page.
    public function index(Request $request, SupportTicketService $supportTicketService): View
    {
        try {
            // Step 1: load the main support ticket page data.
            return view('support-tickets.index', $supportTicketService->indexPageData($request->user()));
        } catch (Throwable $exception) {
            Log::error('Failed to load support ticket index.', ['error' => $exception->getMessage()]);

            return $this->viewWithError('support-tickets.index', [
                'tickets' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15),
                'categories' => SupportTicketService::CATEGORIES,
                'priorities' => SupportTicketService::PRIORITIES,
                'statuses' => SupportTicketService::STATUSES,
                'selectedTicket' => null,
                'ticketComments' => collect(),
                'ticketHistory' => collect(),
                'ticketAttachments' => collect(),
                'canCreateTicket' => false,
                'canHandleTickets' => false,
                'canAddComment' => false,
                'canUpdateStatus' => false,
            ], $exception, 'Unable to load support tickets.');
        }
    }

    // This renders the support ticket detail state inside the same page.
    public function show(int $ticketId, Request $request, SupportTicketService $supportTicketService): View
    {
        try {
            // Step 1: load the selected ticket with its detail data.
            return view('support-tickets.index', $supportTicketService->showPageData($request->user(), $ticketId));
        } catch (Throwable $exception) {
            Log::error('Failed to load support ticket detail.', ['ticket_id' => $ticketId, 'error' => $exception->getMessage()]);

            return $this->viewWithError('support-tickets.index', [
                'tickets' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15),
                'categories' => SupportTicketService::CATEGORIES,
                'priorities' => SupportTicketService::PRIORITIES,
                'statuses' => SupportTicketService::STATUSES,
                'selectedTicket' => null,
                'ticketComments' => collect(),
                'ticketHistory' => collect(),
                'ticketAttachments' => collect(),
                'canCreateTicket' => false,
                'canHandleTickets' => false,
                'canAddComment' => false,
                'canUpdateStatus' => false,
            ], $exception, 'Unable to load support ticket.');
        }
    }

    // This creates a new support ticket.
    public function store(Request $request, SupportTicketService $supportTicketService): RedirectResponse
    {
        try {
            // Step 1: validate the submitted ticket form.
            $validated = $request->validate([
                'category' => ['required', Rule::in(SupportTicketService::CATEGORIES)],
                'priority' => ['required', Rule::in(SupportTicketService::PRIORITIES)],
                'description' => ['required', 'string', 'max:4000'],
                'attachments' => ['nullable', 'array', 'max:5'],
                'attachments.*' => ['file', 'max:5120'],
            ]);

            // Step 2: create the ticket and redirect to its detail state.
            $ticketId = $supportTicketService->createTicket(
                $request->user(),
                [
                    'category' => $validated['category'],
                    'priority' => $validated['priority'],
                    'description' => trim((string) $validated['description']),
                ],
                $request->file('attachments', []),
            );

            return redirect()
                ->route('support-tickets.show', $ticketId)
                ->with('status', 'Support ticket created successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to create support ticket.', ['error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to create support ticket.');
        }
    }

    // This adds a comment to an existing support ticket.
    public function addComment(
        int $ticketId,
        Request $request,
        SupportTicketService $supportTicketService,
    ): RedirectResponse {
        try {
            // Step 1: validate the submitted comment form.
            $validated = $request->validate([
                'comment' => ['required', 'string', 'max:4000'],
                'comment_attachments' => ['nullable', 'array', 'max:5'],
                'comment_attachments.*' => ['file', 'max:5120'],
            ]);

            // Step 2: store the comment and any uploaded comment files.
            $supportTicketService->addComment(
                $request->user(),
                $ticketId,
                trim((string) $validated['comment']),
                $request->file('comment_attachments', []),
            );

            return redirect()
                ->route('support-tickets.show', $ticketId)
                ->with('status', 'Comment added successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to add support ticket comment.', ['ticket_id' => $ticketId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to add comment.');
        }
    }

    // This updates the status of a support ticket.
    public function updateStatus(
        int $ticketId,
        Request $request,
        SupportTicketService $supportTicketService,
    ): RedirectResponse {
        try {
            // Step 1: validate the new ticket status.
            $validated = $request->validate([
                'status' => ['required', Rule::in(SupportTicketService::STATUSES)],
            ]);

            // Step 2: save the new status on the ticket.
            $supportTicketService->updateStatus(
                $request->user(),
                $ticketId,
                $validated['status'],
            );

            return redirect()
                ->route('support-tickets.show', $ticketId)
                ->with('status', 'Support ticket status updated.');
        } catch (Throwable $exception) {
            Log::error('Failed to update support ticket status.', ['ticket_id' => $ticketId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to update support ticket status.');
        }
    }
}
