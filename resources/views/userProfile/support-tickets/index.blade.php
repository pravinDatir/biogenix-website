@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
    $backUrl = url()->previous() ?: route('home');
    $ticketItems = collect($tickets->items());
    $openCount = $ticketItems->where('status', 'open')->count();
    $inProgressCount = $ticketItems->where('status', 'in_progress')->count();
    $awaitingResponseCount = $ticketItems->where('status', 'awaiting_response')->count();
    $closedCount = $ticketItems->where('status', 'closed')->count();
    $metricCardClass = 'rounded-3xl border border-slate-200 bg-white p-4 shadow-sm md:p-5';
    $panelClass = 'space-y-6 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm md:p-8';
    $compactPanelClass = 'space-y-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm';
    $inputClass = 'h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-2 focus:ring-primary-500/40';
    $textareaClass = 'w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-2 focus:ring-primary-500/40';
@endphp

@section('title', 'Support Tickets')
@section('customer_active', 'support')
@section('customer_minimal', 'minimal')

@section('customer_content')
    <x-account.workspace
        :portal="$portal"
        active="support"
        :back-url="$backUrl"
        back-label="Back"
        framed
        title="Support Tickets"
        description="Create, track, and collaborate on support issues without leaving the main customer workspace."
    >

        <x-slot:metrics>
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="flex items-center gap-5 rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-semibold text-slate-500">Open Tickets</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $openCount }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-5 rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-secondary-50 text-secondary-700">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-semibold text-slate-500">In Progress</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $inProgressCount }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-5 rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-[13px] font-semibold text-slate-500">Resolved</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $closedCount }}</p>
                    </div>
                </div>
            </div>
        </x-slot:metrics>

        <div class="space-y-6">
            {{-- Ticket History Table --}}
            <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
                <div class="flex flex-col gap-4 border-b border-slate-100 p-6 sm:flex-row sm:items-center sm:justify-between md:p-8">
                    <h2 class="text-lg font-bold text-slate-900">Ticket History</h2>
                    <div class="flex items-center gap-3">
                        <div class="relative w-full sm:w-64">
                            <input type="text" placeholder="Search tickets..." class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-[13px] font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                            <div class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                        </div>
                        <button type="button" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                        </button>
                    </div>
                </div>

                <div class="w-full overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">
                                <th class="whitespace-nowrap px-6 py-4 md:px-8">Ticket ID</th>
                                <th class="whitespace-nowrap px-6 py-4">Date</th>
                                <th class="whitespace-nowrap px-6 py-4">Subject</th>
                                <th class="whitespace-nowrap px-6 py-4 text-center">Category</th>
                                <th class="whitespace-nowrap px-6 py-4">Status</th>
                                <th class="whitespace-nowrap px-6 py-4 md:px-8 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($tickets as $ticket)
                                @php
                                    $activityAt = $ticket->created_at;
                                    $statusColors = [
                                        'open' => ['dot' => 'bg-primary-600', 'text' => 'text-primary-600'],
                                        'in_progress' => ['dot' => 'bg-secondary-600', 'text' => 'text-secondary-700'],
                                        'resolved' => ['dot' => 'bg-primary-600', 'text' => 'text-primary-600'],
                                        'closed' => ['dot' => 'bg-primary-600', 'text' => 'text-primary-600'],
                                        'awaiting_response' => ['dot' => 'bg-purple-500', 'text' => 'text-purple-700']
                                    ];
                                    $sColor = $statusColors[$ticket->status] ?? ['dot' => 'bg-slate-400', 'text' => 'text-slate-700'];
                                    
                                    // Mock subject since original DB might not have 'subject' column clearly
                                    // fallback: ticket_number logic or just display
                                @endphp
                                <tr class="transition hover:bg-slate-50/50">
                                    <td class="whitespace-nowrap px-6 py-4 md:px-8">
                                        <span class="text-[13px] font-bold text-primary-600">{{ $ticket->ticket_number }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-[13px] font-medium text-slate-500">
                                        {{ $activityAt ? \Illuminate\Support\Carbon::parse($activityAt)->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-[14px] font-semibold text-slate-900 max-w-[200px] truncate">
                                        {{ $ticket->subject ?? 'Support Tracking Inquiry' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-600">
                                            {{ ucwords(str_replace('_', ' ', $ticket->category)) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center gap-2 text-[13px] font-bold {{ $sColor['text'] }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $sColor['dot'] }}"></span>
                                            {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 md:px-8 text-right">
                                        <a href="{{ route('support-tickets.show', $ticket->id) }}" class="text-[13px] font-bold text-primary-600 transition hover:text-primary-700">View Details</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm font-medium text-slate-500">No tickets found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination mock --}}
                <div class="flex items-center justify-between border-t border-slate-100 px-6 py-4 md:px-8">
                    <p class="text-[13px] font-medium text-slate-500">Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} tickets</p>
                    <div class="flex items-center gap-2">
                        <button type="button" class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-[13px] font-medium text-slate-700 transition hover:bg-slate-50">Previous</button>
                        <button type="button" class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-[13px] font-medium text-slate-700 transition hover:bg-slate-50">Next</button>
                    </div>
                </div>
            </div>

        </div>

        @if ($selectedTicket)
            @php
                $attachmentsByComment = $ticketAttachments->groupBy(fn ($attachment) => $attachment->support_ticket_comment_id ?? 'ticket');
                $ticketLevelAttachments = $attachmentsByComment->get('ticket', collect());
            @endphp

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_22rem]">
                <div class="{{ $panelClass }}">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Ticket Detail: {{ $selectedTicket->ticket_number }}</h2>
                        <p class="mt-1 text-sm text-slate-500">Work through the current issue, collaborate with comments, and review all related history.</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        <div class="rounded-2xl bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</p>
                            <div class="mt-2">
                                <x-ui.status-badge
                                    type="status"
                                    :value="$selectedTicket->status"
                                    :label="strtoupper(str_replace('_', ' ', $selectedTicket->status))"
                                    uppercase
                                />
                            </div>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Category</p>
                            <p class="mt-2 text-base font-semibold text-slate-900">{{ ucwords(str_replace('_', ' ', $selectedTicket->category)) }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Priority</p>
                            <div class="mt-2">
                                <x-ui.status-badge type="priority" :value="$selectedTicket->priority" :label="strtoupper($selectedTicket->priority)" uppercase />
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Description</p>
                        <p class="mt-3 text-sm leading-7 text-slate-700">{{ $selectedTicket->description }}</p>
                    </div>

                    <div class="{{ $compactPanelClass }}">
                        <h3 class="text-base font-semibold text-slate-900">Comments</h3>
                        <div class="space-y-4">
                            @forelse ($ticketComments as $comment)
                                @php
                                    $commentAttachments = $attachmentsByComment->get($comment->id, collect());
                                @endphp
                                <article class="rounded-2xl bg-slate-50 px-4 py-4">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <p class="font-semibold text-slate-900">{{ $comment->commenter_name ?? 'System' }}</p>
                                        <p class="text-xs text-slate-500">{{ \Illuminate\Support\Carbon::parse($comment->created_at)->format('d M Y, h:i A') }}</p>
                                    </div>
                                    <p class="mt-3 text-sm leading-7 text-slate-700">{{ $comment->comment }}</p>
                                    @if ($commentAttachments->isNotEmpty())
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach ($commentAttachments as $attachment)
                                                    <span class="break-all rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-600">{{ $attachment->original_file_name }}</span>
                                                @endforeach
                                            </div>
                                    @endif
                                </article>
                            @empty
                                <x-ui.empty-state
                                    icon="support"
                                    title="No comments yet"
                                    description="Comments from your team and the support desk will appear here."
                                />
                            @endforelse
                        </div>

                        @if ($canAddComment)
                            <form method="POST" action="{{ route('support-tickets.comments.store', $selectedTicket->id) }}" enctype="multipart/form-data" class="space-y-4 border-t border-slate-200 pt-4">
                                @csrf
                                <div class="space-y-2">
                                    <label for="comment" class="text-sm font-semibold text-slate-700">Add Comment</label>
                                    <textarea id="comment" name="comment" rows="4" class="{{ $textareaClass }}" required></textarea>
                                </div>
                                <x-ui.file-upload
                                    id="comment_attachments"
                                    name="comment_attachments[]"
                                    label="Comment Attachments"
                                    hint="Optional supporting files for this reply."
                                    multiple
                                    error-key="comment_attachments"
                                />
                                <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="text-sm text-slate-500">Add your response and any supporting files, then send the update to the ticket thread.</p>
                                    <button type="submit" class="inline-flex h-10 w-full items-center justify-center rounded-xl bg-primary-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 sm:w-auto">Submit Comment</button>
                                </div>
                            </form>
                        @else
                            <p class="rounded-2xl bg-slate-50 px-4 py-4 text-sm text-slate-500">You are not allowed to comment on this ticket.</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="{{ $panelClass }} !space-y-4">
                        <h3 class="text-base font-semibold text-slate-900">Status Control</h3>
                        @if ($canUpdateStatus)
                            <form method="POST" action="{{ route('support-tickets.status.update', $selectedTicket->id) }}" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                <div class="space-y-2">
                                    <label for="status" class="text-sm font-semibold text-slate-700">Update Status</label>
                                    <select id="status" name="status" class="{{ $inputClass }}" required>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}" @selected($selectedTicket->status === $status)>{{ strtoupper(str_replace('_', ' ', $status)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="inline-flex h-10 w-full items-center justify-center rounded-xl bg-primary-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700">Update Status</button>
                            </form>
                        @elseif ($canHandleTickets)
                            <p class="rounded-2xl bg-slate-50 px-4 py-4 text-sm text-slate-500">Your current permissions do not allow status updates.</p>
                        @else
                            <p class="rounded-2xl bg-slate-50 px-4 py-4 text-sm text-slate-500">This ticket is view-only for the current account.</p>
                        @endif
                    </div>

                    <div class="{{ $panelClass }} !space-y-4">
                        <h3 class="text-base font-semibold text-slate-900">Attachments</h3>
                        @if ($ticketLevelAttachments->isEmpty())
                            <x-ui.empty-state
                                icon="support"
                                title="No attachments uploaded"
                                description="Files attached during ticket creation will appear here."
                            />
                        @else
                            <div class="space-y-2">
                                @foreach ($ticketLevelAttachments as $attachment)
                                    <div class="break-all rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ $attachment->original_file_name }} ({{ $attachment->mime_type ?? 'unknown' }}, {{ $attachment->file_size }} bytes)
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="{{ $panelClass }} !space-y-4">
                        <h3 class="text-base font-semibold text-slate-900">Ticket History</h3>
                        <div class="space-y-3">
                            @forelse ($ticketHistory as $history)
                                <article class="rounded-2xl bg-slate-50 px-4 py-4">
                                    <p class="text-sm font-semibold text-slate-900">
                                        {{ strtoupper(str_replace('_', ' ', $history->event_type)) }}
                                    </p>
                                    <p class="mt-1 text-sm text-slate-600">by {{ $history->actor_name ?? 'System' }} at {{ \Illuminate\Support\Carbon::parse($history->created_at)->format('d M Y, h:i A') }}</p>
                                    @if ($history->event_type === 'status_changed')
                                        <p class="mt-2 text-sm text-slate-700">{{ strtoupper(str_replace('_', ' ', $history->from_status ?? '-')) }} to {{ strtoupper(str_replace('_', ' ', $history->to_status ?? '-')) }}</p>
                                    @endif
                                    @if ($history->comment_text)
                                        <p class="mt-2 text-sm text-slate-500">{{ $history->comment_text }}</p>
                                    @endif
                                    @if ($history->message)
                                        <p class="mt-2 text-sm text-slate-500">{{ $history->message }}</p>
                                    @endif
                                </article>
                            @empty
                                <x-ui.empty-state
                                    icon="support"
                                    title="No history available"
                                    description="Status changes and system events will appear here."
                                />
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </x-account.workspace>
@endsection
