@extends('layouts.app')

@section('content')
    <div class="page-shell !space-y-4 md:!space-y-6">
    <div class="card">
        <h1>Support Tickets</h1>
        <p class="muted">Create and track support tickets. Internal support users can manage ticket lifecycle.</p>
    </div>

    <div class="card">
        @if ($canCreateTicket)
            <h2>Create Ticket</h2>
            <form method="POST" action="{{ route('support-tickets.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="field">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected(old('category') === $category)>
                                {{ ucwords(str_replace('_', ' ', $category)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="priority">Priority</label>
                    <select id="priority" name="priority" required>
                        <option value="">Select priority</option>
                        @foreach ($priorities as $priority)
                            <option value="{{ $priority }}" @selected(old('priority') === $priority)>
                                {{ strtoupper($priority) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                </div>

                <div class="field">
                    <label for="attachments">Attachments (optional)</label>
                    <input id="attachments" name="attachments[]" type="file" multiple>
                </div>

                <button type="submit" class="btn">Create Ticket</button>
            </form>
        @else
            <p class="muted">You are not allowed to create support tickets with your current role/permissions.</p>
        @endif
    </div>

    <div class="card">
        <h2>Visible Tickets</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Owner</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Comments</th>
                        <th>Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->ticket_number }}</td>
                            <td>
                                {{ $ticket->owner_name ?? 'Unknown' }}
                                @if ($ticket->owner_company_name)
                                    <div class="muted">{{ $ticket->owner_company_name }}</div>
                                @endif
                            </td>
                            <td>{{ ucwords(str_replace('_', ' ', $ticket->category)) }}</td>
                            <td>{{ strtoupper($ticket->priority) }}</td>
                            <td>{{ strtoupper(str_replace('_', ' ', $ticket->status)) }}</td>
                            <td>{{ $ticket->comments_count }}</td>
                            <td>{{ $ticket->last_activity_at ?? $ticket->updated_at ?? $ticket->created_at }}</td>
                            <td>
                                <a class="btn secondary" href="{{ route('support-tickets.show', $ticket->id) }}">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No support tickets visible for your scope.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $tickets->links() }}
        </div>
    </div>

    @if ($selectedTicket)
        @php
            $attachmentsByComment = $ticketAttachments->groupBy(function ($attachment) {
                return $attachment->support_ticket_comment_id ?? 'ticket';
            });
            $ticketLevelAttachments = $attachmentsByComment->get('ticket', collect());
        @endphp

        <div class="card">
            <h2>Ticket Detail: {{ $selectedTicket->ticket_number }}</h2>
            <p><strong>Status:</strong> {{ strtoupper(str_replace('_', ' ', $selectedTicket->status)) }}</p>
            <p><strong>Category:</strong> {{ ucwords(str_replace('_', ' ', $selectedTicket->category)) }}</p>
            <p><strong>Priority:</strong> {{ strtoupper($selectedTicket->priority) }}</p>
            <p><strong>Description:</strong><br>{{ $selectedTicket->description }}</p>

            @if ($canUpdateStatus)
                <form method="POST" action="{{ route('support-tickets.status.update', $selectedTicket->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="field">
                        <label for="status">Update Status</label>
                        <select id="status" name="status" required>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected($selectedTicket->status === $status)>
                                    {{ strtoupper(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn">Update Status</button>
                </form>
            @elseif ($canHandleTickets)
                <p class="muted">Your current permissions do not allow status updates.</p>
            @endif
        </div>

        <div class="card">
            <h2>Attachments</h2>
            @if ($ticketLevelAttachments->isEmpty())
                <p class="muted">No attachments were uploaded on ticket creation.</p>
            @else
                <ul>
                    @foreach ($ticketLevelAttachments as $attachment)
                        <li>{{ $attachment->original_file_name }} ({{ $attachment->mime_type ?? 'unknown' }}, {{ $attachment->file_size }} bytes)</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="card">
            <h2>Comments</h2>
            <div class="section-list">
            @forelse ($ticketComments as $comment)
                <div class="section-list-item">
                    <p><strong>{{ $comment->commenter_name ?? 'System' }}</strong> ({{ $comment->created_at }})</p>
                    <p>{{ $comment->comment }}</p>

                    @php
                        $commentAttachments = $attachmentsByComment->get($comment->id, collect());
                    @endphp

                    @if ($commentAttachments->isNotEmpty())
                        <ul>
                            @foreach ($commentAttachments as $attachment)
                                <li>{{ $attachment->original_file_name }} ({{ $attachment->mime_type ?? 'unknown' }}, {{ $attachment->file_size }} bytes)</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @empty
                <p class="muted">No comments yet.</p>
            @endforelse
            </div>

            @if ($canAddComment)
                <form method="POST" action="{{ route('support-tickets.comments.store', $selectedTicket->id) }}" enctype="multipart/form-data" class="mt-3">
                    @csrf

                    <div class="field">
                        <label for="comment">Add Comment</label>
                        <textarea id="comment" name="comment" rows="3" required></textarea>
                    </div>

                    <div class="field">
                        <label for="comment_attachments">Comment Attachments (optional)</label>
                        <input id="comment_attachments" name="comment_attachments[]" type="file" multiple>
                    </div>

                    <button type="submit" class="btn">Add Comment</button>
                </form>
            @else
                <p class="muted">You are not allowed to comment on this ticket.</p>
            @endif
        </div>

        <div class="card">
            <h2>Ticket History</h2>
            <div class="section-list">
            @forelse ($ticketHistory as $history)
                <div class="section-list-item">
                    <p>
                        <strong>{{ strtoupper(str_replace('_', ' ', $history->event_type)) }}</strong>
                        by {{ $history->actor_name ?? 'System' }} at {{ $history->created_at }}
                    </p>

                    @if ($history->event_type === 'status_changed')
                        <p>
                            {{ strtoupper(str_replace('_', ' ', $history->from_status ?? '-')) }}
                            to
                            {{ strtoupper(str_replace('_', ' ', $history->to_status ?? '-')) }}
                        </p>
                    @endif

                    @if ($history->comment_text)
                        <p class="muted">{{ $history->comment_text }}</p>
                    @endif

                    @if ($history->message)
                        <p class="muted">{{ $history->message }}</p>
                    @endif
                </div>
            @empty
                <p class="muted">No history available.</p>
            @endforelse
            </div>
        </div>
    @endif
    </div>
@endsection
