@extends('customer.layout')

@php
    $portal = auth()->user()?->user_type ?? request('user_type', request('portal', 'b2c'));
    $portal = $portal === 'b2b' ? 'b2b' : 'b2c';
    $statusColors = [
        'open' => 'text-primary-600',
        'in_progress' => 'text-amber-600',
        'closed' => 'text-emerald-600',
        'awaiting_response' => 'text-sky-600',
    ];
    $statusDots = [
        'open' => 'bg-primary-500',
        'in_progress' => 'bg-amber-500',
        'closed' => 'bg-emerald-500',
        'awaiting_response' => 'bg-sky-500',
    ];
    $categoryBg = [
        'security' => 'bg-slate-100 text-slate-700',
        'logistics' => 'bg-slate-100 text-slate-700',
        'technical' => 'bg-slate-100 text-slate-700',
        'returns' => 'bg-slate-100 text-slate-700',
        'billing' => 'bg-slate-100 text-slate-700',
        'product_inquiry' => 'bg-slate-100 text-slate-700',
    ];
    $previewTickets = isset($tickets) && $tickets->count()
        ? collect($tickets->items())->map(function ($ticket) use ($statusColors, $statusDots, $categoryBg) {
            return [
                'id' => $ticket->ticket_number,
                'date' => \Illuminate\Support\Carbon::parse($ticket->created_at)->format('M d, Y'),
                'subject' => \Illuminate\Support\Str::limit($ticket->description, 40),
                'category' => ucfirst(str_replace('_', ' ', $ticket->category)),
                'catClass' => $categoryBg[$ticket->category] ?? 'bg-slate-100 text-slate-700',
                'status' => ucfirst(str_replace('_', ' ', $ticket->status)),
                'statusColor' => $statusColors[$ticket->status] ?? 'text-slate-600',
                'dotColor' => $statusDots[$ticket->status] ?? 'bg-slate-400',
                'href' => route('support-tickets.show', $ticket->id),
            ];
        })->values()
        : collect([
            ['id' => '#TK-8955', 'date' => 'Nov 02, 2023', 'subject' => 'Account Access Issue', 'category' => 'Security', 'catClass' => 'bg-slate-100 text-slate-700', 'status' => 'Open', 'statusColor' => 'text-primary-600', 'dotColor' => 'bg-primary-500', 'href' => null],
            ['id' => '#TK-8902', 'date' => 'Oct 24, 2023', 'subject' => 'Shipping Delay Inquiry', 'category' => 'Logistics', 'catClass' => 'bg-slate-100 text-slate-700', 'status' => 'In Progress', 'statusColor' => 'text-amber-600', 'dotColor' => 'bg-amber-500', 'href' => null],
            ['id' => '#TK-8841', 'date' => 'Oct 12, 2023', 'subject' => 'Product Storage Guidelines', 'category' => 'Technical', 'catClass' => 'bg-slate-100 text-slate-700', 'status' => 'Resolved', 'statusColor' => 'text-emerald-600', 'dotColor' => 'bg-emerald-500', 'href' => null],
            ['id' => '#TK-8712', 'date' => 'Sep 28, 2023', 'subject' => 'Damaged Packaging', 'category' => 'Returns', 'catClass' => 'bg-slate-100 text-slate-700', 'status' => 'Resolved', 'statusColor' => 'text-emerald-600', 'dotColor' => 'bg-emerald-500', 'href' => null],
        ]);
@endphp

@section('title', 'Support Tickets')
@section('customer_active', 'support')
@section('customer_minimal', 'minimal')

@section('customer_content')
<div class="mx-auto w-full max-w-[1120px] pb-12">
    <div class="rounded-[34px] border border-slate-200 bg-white/70 p-4 shadow-sm sm:p-5">
        <div class="grid w-full gap-8 lg:grid-cols-[15.5rem_minmax(0,1fr)]">
            @include('customer.partials.account-sidebar', ['portal' => $portal, 'active' => 'support'])

            <div class="space-y-6 lg:border-l lg:border-slate-200 lg:pl-10">
        {{-- Page header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 md:text-3xl">Support Tickets</h1>
                <p class="mt-1 text-sm text-slate-500">Manage and track your inquiries and technical support requests.</p>
            </div>
            <button type="button" class="inline-flex h-11 shrink-0 items-center gap-2 rounded-xl bg-primary-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700" onclick="document.getElementById('newTicketModal').classList.remove('hidden')">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New Ticket
            </button>
        </div>

        {{-- Stat cards --}}
        <div class="grid gap-4 sm:grid-cols-3">
            @php
                $stats = [
                    ['label' => 'Open Tickets', 'count' => isset($tickets) ? collect($tickets->items())->where('status', 'open')->count() : 2, 'color' => 'primary', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['label' => 'In Progress', 'count' => isset($tickets) ? collect($tickets->items())->where('status', 'in_progress')->count() : 1, 'color' => 'amber', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['label' => 'Resolved', 'count' => isset($tickets) ? collect($tickets->items())->where('status', 'closed')->count() : 14, 'color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
            @endphp
            @foreach ($stats as $stat)
                <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-{{ $stat['color'] }}-50">
                        <svg class="h-6 w-6 text-{{ $stat['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/></svg>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $stat['count'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Ticket History --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-lg font-bold text-slate-900">Ticket History</h2>
                <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row sm:items-center">
                    <div class="relative w-full sm:w-56">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                        <input type="text" placeholder="Search tickets..." id="ticketSearchInput" class="h-10 w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20" oninput="filterTickets(this.value)">
                    </div>
                    <button type="button" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-slate-400 transition hover:bg-slate-50">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    </button>
                </div>
            </div>

            <div class="space-y-3 md:hidden">
                @foreach ($previewTickets as $ticket)
                    <article class="ticket-item rounded-2xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-primary-600">{{ $ticket['id'] }}</p>
                                    <p class="mt-1 text-sm font-medium text-slate-900">{{ $ticket['subject'] }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 {{ $ticket['statusColor'] }}">
                                    <span class="h-2 w-2 rounded-full {{ $ticket['dotColor'] }}"></span>
                                    <span class="text-sm font-medium">{{ $ticket['status'] }}</span>
                                </span>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Date</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $ticket['date'] }}</p>
                                </div>
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Category</p>
                                    <span class="mt-1 inline-flex rounded-full {{ $ticket['catClass'] }} px-3 py-1 text-xs font-medium">{{ $ticket['category'] }}</span>
                                </div>
                            </div>
                            @if ($ticket['href'])
                                <a href="{{ $ticket['href'] }}" class="inline-flex h-10 w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 no-underline transition hover:bg-slate-50 hover:text-slate-900">
                                    View Details
                                </a>
                            @else
                                <span class="inline-flex h-10 w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-primary-600">
                                    View Details
                                </span>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Table --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[42rem] text-left text-sm" id="ticketsTable">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="pb-3 pr-4 text-xs font-semibold uppercase tracking-wide text-slate-400">Ticket ID</th>
                            <th class="pb-3 pr-4 text-xs font-semibold uppercase tracking-wide text-slate-400">Date</th>
                            <th class="pb-3 pr-4 text-xs font-semibold uppercase tracking-wide text-slate-400">Subject</th>
                            <th class="pb-3 pr-4 text-xs font-semibold uppercase tracking-wide text-slate-400">Category</th>
                            <th class="pb-3 pr-4 text-xs font-semibold uppercase tracking-wide text-slate-400">Status</th>
                            <th class="pb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="ticketsBody">
                        @foreach ($previewTickets as $ticket)
                                <tr class="ticket-item">
                                    <td class="py-4 pr-4">
                                        @if ($ticket['href'])
                                            <a href="{{ $ticket['href'] }}" class="font-semibold text-primary-600 no-underline hover:text-primary-700">{{ $ticket['id'] }}</a>
                                        @else
                                            <span class="font-semibold text-primary-600">{{ $ticket['id'] }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 pr-4 text-slate-600">{{ $ticket['date'] }}</td>
                                    <td class="py-4 pr-4 font-medium text-slate-900">{{ $ticket['subject'] }}</td>
                                    <td class="py-4 pr-4">
                                        <span class="rounded-full {{ $ticket['catClass'] }} px-3 py-1 text-xs font-medium">{{ $ticket['category'] }}</span>
                                    </td>
                                    <td class="py-4 pr-4">
                                        <span class="flex items-center gap-1.5 {{ $ticket['statusColor'] }}">
                                            <span class="h-2 w-2 rounded-full {{ $ticket['dotColor'] }}"></span>
                                            {{ $ticket['status'] }}
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        @if ($ticket['href'])
                                            <a href="{{ $ticket['href'] }}" class="text-sm font-semibold text-primary-600 no-underline hover:text-primary-700">View Details</a>
                                        @else
                                            <span class="text-sm font-semibold text-primary-600">View Details</span>
                                        @endif
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex flex-col items-center justify-between gap-3 border-t border-slate-100 pt-4 sm:flex-row">
                <p class="text-sm text-slate-500">
                    @if (isset($tickets))
                        Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} tickets
                    @else
                        Showing 1 to 4 of 17 tickets
                    @endif
                </p>
                <div class="flex items-center gap-2">
                    @if (isset($tickets) && $tickets->hasPages())
                        {{ $tickets->links() }}
                    @else
                        <button type="button" class="h-9 rounded-lg border border-slate-200 bg-white px-4 text-sm font-medium text-slate-600 transition hover:bg-slate-50">Previous</button>
                        <button type="button" class="h-9 rounded-lg border border-slate-200 bg-white px-4 text-sm font-medium text-slate-600 transition hover:bg-slate-50">Next</button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Need immediate help section --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Need immediate help?</h2>
                    <p class="mt-2 max-w-md text-sm leading-7 text-slate-500">Our knowledge base contains answers to 90% of technical questions regarding bio-storage and shipping protocols.</p>
                    <a href="{{ route('faq') }}" class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-primary-600 no-underline hover:text-primary-700">
                        Visit Help Center
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
                <div class="flex flex-wrap gap-4">
                    <div class="flex flex-col items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-8 py-5 text-center">
                        <svg class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Documentation</p>
                        <p class="text-sm font-bold text-slate-900">Storage Guide</p>
                    </div>
                    <div class="flex flex-col items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-8 py-5 text-center">
                        <svg class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Live Support</p>
                        <p class="text-sm font-bold text-slate-900">24/7 Chat</p>
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>

{{-- New Ticket Modal --}}
<div id="newTicketModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('newTicketModal').classList.add('hidden')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-xl md:p-8">
            <button type="button" class="absolute right-4 top-4 rounded-lg p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" onclick="document.getElementById('newTicketModal').classList.add('hidden')">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h2 class="text-xl font-bold text-slate-900">Create New Ticket</h2>
            <p class="mt-1 text-sm text-slate-500">Submit a support request and our team will respond promptly.</p>

            <form method="POST" action="{{ route('support-tickets.store') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label for="modal_category" class="text-sm font-semibold text-slate-700">Category</label>
                        <select id="modal_category" name="category" class="h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 outline-none transition focus:ring-2 focus:ring-primary-500/40" required>
                            <option value="">Select category</option>
                            @if (isset($categories))
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}">{{ ucwords(str_replace('_', ' ', $category)) }}</option>
                                @endforeach
                            @else
                                <option value="security">Security</option>
                                <option value="logistics">Logistics</option>
                                <option value="technical">Technical</option>
                                <option value="returns">Returns</option>
                                <option value="billing">Billing</option>
                            @endif
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="modal_priority" class="text-sm font-semibold text-slate-700">Priority</label>
                        <select id="modal_priority" name="priority" class="h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 outline-none transition focus:ring-2 focus:ring-primary-500/40" required>
                            <option value="">Select priority</option>
                            @if (isset($priorities))
                                @foreach ($priorities as $priority)
                                    <option value="{{ $priority }}">{{ strtoupper($priority) }}</option>
                                @endforeach
                            @else
                                <option value="low">LOW</option>
                                <option value="medium">MEDIUM</option>
                                <option value="high">HIGH</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="space-y-2">
                    <label for="modal_description" class="text-sm font-semibold text-slate-700">Description</label>
                    <textarea id="modal_description" name="description" rows="4" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-2 focus:ring-primary-500/40" placeholder="Describe your issue in detail..." required></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" class="h-11 rounded-xl border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50" onclick="document.getElementById('newTicketModal').classList.add('hidden')">Cancel</button>
                    <button type="submit" class="h-11 rounded-xl bg-primary-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700">Create Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function filterTickets(query) {
    var rows = document.querySelectorAll('.ticket-item');
    var lowerQuery = query.toLowerCase();
    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        row.classList.toggle('hidden', lowerQuery !== '' && text.indexOf(lowerQuery) === -1);
    });
}
</script>
@endpush
@endsection
