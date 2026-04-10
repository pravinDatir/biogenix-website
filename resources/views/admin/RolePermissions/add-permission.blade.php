@extends('admin.layout')

@section('title', 'Add Permission - Biogenix Admin')

@section('admin_content')
    <div class="space-y-6 text-slate-900">

        {{-- Back Arrow + Breadcrumb --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.role-permission') }}" class="ajax-link h-8 w-8 flex items-center justify-center rounded-lg border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 transition shrink-0 cursor-pointer" title="Back to Roles & Permissions">
                <svg class="h-4 w-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <nav class="flex items-center text-[13px] text-slate-500 font-medium">
            <a href="{{ route('admin.role-permission') }}" class="ajax-link hover:text-slate-900 transition cursor-pointer">Roles &amp; Permissions</a>
            <span class="mx-2 text-slate-300">/</span>
                <span class="text-slate-900 font-semibold cursor-pointer">Add Global Permission</span>
            </nav>
        </div>

        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Add Global Permission</h1>
            <p class="text-sm text-slate-500 mt-1">Define actionable capabilities to be assigned across roles and teams.</p>
        </div>

        {{-- Form Card --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white shadow-[var(--ui-shadow-soft)] p-6 sm:p-8">
            <div class="space-y-6 max-w-2xl">
                
                {{-- Permission Name --}}
                <div>
                    <label for="permissionName" class="block text-[13px] font-bold text-slate-700 mb-2">Permission Internal Name <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                        </div>
                        <input id="permissionName" type="text" placeholder="e.g. results.export_raw" class="h-12 w-full rounded-xl border border-slate-200 bg-slate-50 pl-11 pr-4 text-[14px] text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-200/50 font-mono">
                    </div>
                    <p class="mt-1.5 text-[12px] text-slate-400">Use dot notation for module grouping. Must be unique.</p>
                </div>

                {{-- Module Association --}}
                <div>
                    <label for="permissionModule" class="block text-[13px] font-bold text-slate-700 mb-2">Module Association</label>
                    <div class="relative inline-block w-full" id="module-dropdown-container">
                        <div onclick="document.getElementById('permission-module-menu').classList.toggle('hidden')" class="border border-slate-200 bg-slate-50 cursor-pointer rounded-xl h-12 w-full px-4 pr-10 text-[14px] text-slate-700 transition flex items-center justify-between">
                            <span id="selected-module-text" class="text-slate-400">Select a target module...</span>
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        
                        <div id="permission-module-menu" class="hidden absolute top-full left-0 z-50 mt-1 w-full overflow-hidden rounded-xl border border-slate-200 bg-white shadow-[var(--ui-shadow-card)]">
                            <div class="p-1">
                                <div onclick="selectModule('lab', 'Lab Management System (LMS)')" class="px-3 py-2.5 text-[14px] font-medium text-slate-700 hover:bg-primary-50 hover:text-primary-700 rounded-lg cursor-pointer transition">Lab Management System (LMS)</div>
                                <div onclick="selectModule('billing', 'Billing & Finance')" class="px-3 py-2.5 text-[14px] font-medium text-slate-700 hover:bg-primary-50 hover:text-primary-700 rounded-lg cursor-pointer transition">Billing & Finance</div>
                                <div onclick="selectModule('users', 'User Profile & Auth')" class="px-3 py-2.5 text-[14px] font-medium text-slate-700 hover:bg-primary-50 hover:text-primary-700 rounded-lg cursor-pointer transition">User Profile & Auth</div>
                                <div onclick="selectModule('inventory', 'Inventory & Reagents')" class="px-3 py-2.5 text-[14px] font-medium text-slate-700 hover:bg-primary-50 hover:text-primary-700 rounded-lg cursor-pointer transition">Inventory & Reagents</div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="permissionModule" value="">
                </div>

                {{-- Detailed Description --}}
                <div>
                    <label for="permissionDesc" class="block text-[13px] font-bold text-slate-700 mb-2">Detailed Description</label>
                    <textarea id="permissionDesc" rows="3" placeholder="Explain what this permission allows a user to do..." class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-[14px] text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-200/50 resize-none"></textarea>
                </div>

                {{-- Tip --}}
                <div class="rounded-xl border border-primary-100 bg-primary-50/50 p-4">
                    <div class="flex gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary-100 text-primary-700">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <div>
                            <h4 class="text-[13px] font-extrabold text-primary-900">Admin Tip</h4>
                            <p class="mt-1 text-[13px] text-primary-700/80 leading-relaxed">After creating this permission, you will still need to map it to the respective roles in the "Permission Mapping" tab.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                <a href="{{ route('admin.role-permission') }}" class="ajax-link px-6 py-3 rounded-xl text-[14px] font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition cursor-pointer">Cancel</a>
                <button type="button" id="addPermSaveBtn" class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary-600 px-5 py-2.5 text-[14px] font-bold text-white shadow-md shadow-primary-600/20 transition hover:bg-primary-700 cursor-pointer">
                    Save Permission
                </button>
            </div>
        </div>
    </div>

@push('scripts')
<script>
window.selectModule = function(val, text) {
    document.getElementById('permissionModule').value = val;
    let span = document.getElementById('selected-module-text');
    span.textContent = text;
    span.classList.remove('text-slate-400');
    span.classList.add('text-slate-900', 'font-medium');
    document.getElementById('permission-module-menu').classList.add('hidden');
};

(function() {
    var savePermBtn = document.getElementById('addPermSaveBtn');
    if (savePermBtn) {
        savePermBtn.addEventListener('click', function() {
            var permName = document.getElementById('permissionName').value.trim();
            if (!permName) {
                document.getElementById('permissionName').focus();
                document.getElementById('permissionName').classList.add('border-rose-400', 'ring-1', 'ring-rose-200');
                setTimeout(function() {
                    document.getElementById('permissionName').classList.remove('border-rose-400', 'ring-1', 'ring-rose-200');
                }, 2000);
                return;
            }
            if (window.AdminToast) {
                window.AdminToast.show('Permission "' + permName + '" created successfully!', 'success');
            } else {
                alert('Permission "' + permName + '" created successfully!');
            }
            var tmpLink = document.createElement('a');
            tmpLink.href = "{{ route('admin.role-permission') }}";
            tmpLink.className = 'ajax-link hidden';
            document.body.appendChild(tmpLink);
            tmpLink.click();
            document.body.removeChild(tmpLink);
        });
    }
})();
</script>
@endpush
@endsection

