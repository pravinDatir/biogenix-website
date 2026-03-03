<?php

namespace App\Http\Controllers;

use App\Services\SupportTicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(Request $request, SupportTicketService $supportTicketService): View
    {
        return view('support-tickets.index', $supportTicketService->indexPageData($request->user()));
    }

    public function show(int $ticketId, Request $request, SupportTicketService $supportTicketService): View
    {
        return view('support-tickets.index', $supportTicketService->showPageData($request->user(), $ticketId));
    }

    public function store(Request $request, SupportTicketService $supportTicketService): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', Rule::in(SupportTicketService::CATEGORIES)],
            'priority' => ['required', Rule::in(SupportTicketService::PRIORITIES)],
            'description' => ['required', 'string', 'max:4000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:5120'],
        ]);

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
    }

    public function addComment(
        int $ticketId,
        Request $request,
        SupportTicketService $supportTicketService,
    ): RedirectResponse {
        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:4000'],
            'comment_attachments' => ['nullable', 'array', 'max:5'],
            'comment_attachments.*' => ['file', 'max:5120'],
        ]);

        $supportTicketService->addComment(
            $request->user(),
            $ticketId,
            trim((string) $validated['comment']),
            $request->file('comment_attachments', []),
        );

        return redirect()
            ->route('support-tickets.show', $ticketId)
            ->with('status', 'Comment added successfully.');
    }

    public function updateStatus(
        int $ticketId,
        Request $request,
        SupportTicketService $supportTicketService,
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => ['required', Rule::in(SupportTicketService::STATUSES)],
        ]);

        $supportTicketService->updateStatus(
            $request->user(),
            $ticketId,
            $validated['status'],
        );

        return redirect()
            ->route('support-tickets.show', $ticketId)
            ->with('status', 'Support ticket status updated.');
    }
}
