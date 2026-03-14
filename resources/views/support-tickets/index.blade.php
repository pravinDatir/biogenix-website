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
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="{{ $metricCardClass }}">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Open</p>
                    <p class="mt-3 text-2xl font-bold text-slate-900">{{ $openCount }}</p>
                </div>
                <div class="{{ $metricCardClass }}">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">In Progress</p>
                    <p class="mt-3 text-2xl font-bold text-slate-900">{{ $inProgressCount }}</p>
                </div>
                <div class="{{ $metricCardClass }}">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Awaiting Response</p>
                    <p class="mt-3 text-2xl font-bold text-slate-900">{{ $awaitingResponseCount }}</p>
                </div>
                <div class="{{ $metricCardClass }}">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Closed</p>
                    <p class="mt-3 text-2xl font-bold text-slate-900">{{ $closedCount }}</p>
                </div>
            </div>
        </x-slot:metrics>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_20rem]">
            <div class="{{ $panelClass }}">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">{{ $canCreateTicket ? 'Create Ticket' : 'Ticket Creation Restricted' }}</h2>
                    <p class="mt-1 text-sm text-slate-500">Use the live support workflow fields and upload optional supporting files.</p>
                </div>

                @if ($canCreateTicket)
                    <form method="POST" action="{{ route('support-tickets.store') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <label for="category" class="text-sm font-semibold text-slate-700">Category</label>
                                <select id="category" name="category" class="{{ $inputClass }}" required>
                                    <option value="">Select category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category }}" @selected(old('category') === $category)>{{ ucwords(str_replace('_', ' ', $category)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label for="priority" class="text-sm font-semibold text-slate-700">Priority</label>
                                <select id="priority" name="priority" class="{{ $inputClass }}" required>
                                    <option value="">Select priority</option>
                                    @foreach ($priorities as $priority)
                                        <option value="{{ $priority }}" @selected(old('priority') === $priority)>{{ strtoupper($priority) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="description" class="text-sm font-semibold text-slate-700">Description</label>
                            <textarea id="description" name="description" rows="5" class="{{ $textareaClass }}" required>{{ old('description') }}</textarea>
                        </div>

                        <x-ui.file-upload
                            id="attachments"
                            name="attachments[]"
                            label="Attachments"
                            hint="Upload up to 5 files, 5 MB each."
                            multiple
                            error-key="attachments"
                        />

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex h-11 w-full items-center justify-center rounded-xl bg-primary-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 sm:w-auto">Create Ticket</button>
                        </div>
                    </form>
                @else
                    <x-alert type="warning">
                        You are not allowed to create support tickets with your current role or permissions.
                    </x-alert>
                @endif
            </div>

            <div class="{{ $panelClass }} !space-y-4">
                <h2 class="text-lg font-semibold text-slate-900">Support SLA Snapshot</h2>
                <div class="rounded-2xl bg-slate-50 px-4 py-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Response Window</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">Business hours first-response target</p>
                </div>
                <div class="rounded-2xl bg-slate-50 px-4 py-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Attachment Policy</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">Up to 5 files, 5 MB each</p>
                </div>
                <div class="rounded-2xl bg-slate-50 px-4 py-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Scope</p>
                    <p class="mt-2 text-base font-semibold text-slate-900">{{ $portal === 'b2b' ? 'Company-linked support visibility' : 'Self-service ticket visibility' }}</p>
                </div>
            </div>
        </div>

        <div class="{{ $panelClass }}">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Visible Tickets</h2>
                    <p class="mt-1 text-sm text-slate-500">All tickets currently visible to the signed-in scope.</p>
                </div>
                <div class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $tickets->total() }} tickets</div>
            </div>

            <div class="space-y-4">
                @forelse ($tickets as $ticket)
                    @php
                        $activityAt = $ticket->last_activity_at ?? $ticket->updated_at ?? $ticket->created_at;
                    @endphp
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="space-y-3">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-lg font-semibold text-slate-900">{{ $ticket->ticket_number }}</h3>
                                    <x-ui.status-badge
                                        type="status"
                                        :value="$ticket->status"
                                        :label="strtoupper(str_replace('_', ' ', $ticket->status))"
                                        uppercase
                                    />
                                    <x-ui.status-badge type="priority" :value="$ticket->priority" :label="strtoupper($ticket->priority)" uppercase />
                                </div>
                                <div class="grid gap-3 text-sm text-slate-600 sm:grid-cols-2 xl:grid-cols-4">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Owner</p>
                                        <p class="mt-1 font-medium text-slate-900">{{ $ticket->owner_name ?? 'Unknown' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Company</p>
                                        <p class="mt-1 font-medium text-slate-900">{{ $ticket->owner_company_name ?? 'Self' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Category</p>
                                        <p class="mt-1 font-medium text-slate-900">{{ ucwords(str_replace('_', ' ', $ticket->category)) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Comments</p>
                                        <p class="mt-1 font-medium text-slate-900">{{ $ticket->comments_count }}</p>
                                    </div>
                                </div>
                                <p class="text-sm text-slate-500">Last activity: {{ $activityAt ? \Illuminate\Support\Carbon::parse($activityAt)->format('d M Y, h:i A') : 'N/A' }}</p>
                            </div>

                            <a class="inline-flex h-10 w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 sm:w-auto" href="{{ route('support-tickets.show', $ticket->id) }}">View Ticket</a>
                        </div>
                    </article>
                @empty
                    <x-ui.empty-state
                        icon="support"
                        title="No visible tickets"
                        description="There are no support tickets visible for the current user scope."
                    />
                @endforelse
            </div>

            <x-ui.pagination :paginator="$tickets" />
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
                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex h-10 w-full items-center justify-center rounded-xl bg-primary-600 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 sm:w-auto">Add Comment</button>
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
