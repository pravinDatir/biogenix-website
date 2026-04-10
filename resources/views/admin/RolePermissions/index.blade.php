@extends('admin.layout')

@section('title', 'Roles & Permissions Center - Biogenix Admin')

@section('admin_content')


    @php
        $tabs = [
            ['label' => 'Role Management', 'active' => true],
            ['label' => 'Permission Mapping', 'active' => false],
            ['label' => 'User Overrides', 'active' => false],
            ['label' => 'Department Access', 'active' => false],
            ['label' => 'Delegation & Impersonation', 'active' => false],
        ];

        $systemRoles = [
            ['name' => 'Super Admin', 'summary' => 'Unrestricted system-wide access', 'users' => 4, 'status' => 'Active'],
            ['name' => 'Sales Manager', 'summary' => 'Regional sales & pipeline tools', 'users' => 12, 'status' => 'Active'],
            ['name' => 'Lab Technician', 'summary' => 'R&D and inventory management', 'users' => 48, 'status' => 'Active'],
        ];

        $permissions = [
            ['code' => 'billing.invoice.create', 'description' => 'Generate customer invoices'],
            ['code' => 'lab.sample.approve', 'description' => 'Sign off on biological tests'],
            ['code' => 'user.profile.export', 'description' => 'Export CSV of user data'],
        ];

        $matrixRoles = [
            ['key' => 'super_admin', 'label' => 'Role: Super Admin', 'actions' => ['View', 'Create', 'Edit', 'Del', 'Exp']],
            ['key' => 'sales_manager', 'label' => 'Role: Sales Manager', 'actions' => ['View', 'Create', 'Edit', 'Del']],
        ];

        $departmentAccess = [
            ['name' => 'Hematology', 'role' => 'Lab Lead', 'users' => 8],
            ['name' => 'Molecular R&D', 'role' => 'Researcher', 'users' => 14],
        ];

        $delegations = [
            ['name' => 'Sarah Connor', 'role' => 'Sales Lead', 'scope' => 'Global Pharma Inc.'],
        ];

        $statusBadge = "inline-flex items-center rounded-full bg-primary-50 px-2.5 py-0.5 text-[11px] font-bold text-primary-700 border border-primary-200/60";
    @endphp

    <div class="space-y-6">
        <!-- Header with Tabs -->
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Access Control Center</h1>
                <p class="mt-1 text-sm text-slate-500">Global role hierarchy and security clearance management.</p>
            </div>
        </div>

        <nav class="flex gap-8 border-b border-slate-200">
            @foreach ($tabs as $tab)
                <a href="#" class="border-b-2 px-0.5 pb-4 text-[14px] font-semibold transition {{ $tab['active'] ? 'border-primary-600 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </nav>

        <!-- Role Management Grid -->
        <div class="grid gap-6 lg:grid-cols-3">
            <section class="lg:col-span-2 overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-[var(--ui-shadow-soft)]">
                <div class="mb-6 flex items-center justify-between border-b border-slate-50 bg-slate-50/30 p-5">
                    <div>
                        <h2 class="text-[17px] font-extrabold text-slate-900">System Roles</h2>
                        <p class="text-[12px] font-medium text-slate-500 mt-0.5">Define core organizational access levels.</p>
                    </div>
                    <a href="{{ route('admin.role-permission.add-role') }}" class="ajax-link inline-flex items-center justify-center gap-2 rounded-xl bg-primary-600 hover:bg-primary-700 px-4 py-2.5 text-[12px] font-extrabold text-white shadow-md shadow-primary-600/20 transition cursor-pointer">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Role
                    </a>
                </div>

                <div class="p-5 pt-0">
                    <div class="grid gap-4">
                        @foreach ($systemRoles as $role)
                            <div class="group flex items-center justify-between rounded-xl border border-slate-100 bg-white p-4 transition hover:border-primary-200 hover:bg-slate-50/50">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition group-hover:bg-primary-50 group-hover:text-primary-600">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 1-2.625.372 9.337 9.337 0 0 1-4.121-.952 4.125 4.125 0 0 0-7.533 2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-[14px] font-extrabold text-slate-900">{{ $role['name'] }}</div>
                                        <div class="text-[12px] font-medium text-slate-400 mt-0.5">{{ $role['summary'] }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="hidden sm:block text-right">
                                        <div class="text-[13px] font-bold text-slate-900">{{ $role['users'] }}</div>
                                        <div class="text-[11px] font-bold text-slate-400 uppercase">Users</div>
                                    </div>
                                    <span class="{{ $statusBadge }} shrink-0">{{ $role['status'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-[var(--ui-shadow-soft)]">
                <div class="mb-5 border-b border-slate-50 bg-slate-50/30 p-5">
                    <h2 class="text-[17px] font-extrabold text-slate-900">Permissions</h2>
                    <p class="text-[12px] font-medium text-slate-500 mt-0.5">Granular action-level access tokens.</p>
                </div>
                <div class="p-5 pt-0 space-y-4">
                    <div class="grid gap-2">
                        @foreach ($permissions as $perm)
                            <div class="rounded-xl border border-slate-100 p-3.5 transition hover:border-secondary-200">
                                <div class="text-[11px] font-black uppercase tracking-widest text-slate-400 font-mono">{{ $perm['code'] }}</div>
                                <div class="mt-1 text-[13px] font-semibold text-slate-600">{{ $perm['description'] }}</div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('admin.role-permission.add-permission') }}" class="ajax-link inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary-600 hover:bg-primary-700 px-4 py-3 text-[13px] font-extrabold text-white shadow-md shadow-primary-600/20 transition cursor-pointer">
                         <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        New Permission
                    </a>
                </div>
            </section>
        </div>

        <!-- Permission Matrix -->
        <section class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-[var(--ui-shadow-soft)]">
            <div class="mb-6 flex items-center justify-between border-b border-slate-50 bg-slate-50/30 p-5">
                <div>
                    <h2 class="text-[17px] font-extrabold text-slate-900">Permission Mapping Matrix</h2>
                    <p class="text-[12px] font-medium text-slate-500 mt-0.5">Cross-reference roles with specific action capabilities.</p>
                </div>
                <div class="flex gap-2">
                    <button class="rounded-lg border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-50">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto p-5 pt-0">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="px-4 py-4 text-[11px] font-black uppercase tracking-widest text-slate-400">Permission Module \ Roles</th>
                            @foreach ($matrixRoles as $mr)
                                <th class="px-4 py-4 text-center text-[12px] font-bold text-slate-900">{{ $mr['label'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach (['Billing', 'Lab Ops', 'Inventory', 'CRM'] as $mod)
                            <tr>
                                <td class="px-4 py-4">
                                    <div class="text-[13px] font-extrabold text-slate-900">{{ $mod }}</div>
                                    <div class="text-[11px] font-medium text-slate-400">Core {{ strtolower($mod) }} access</div>
                                </td>
                                @foreach ($matrixRoles as $mr)
                                    <td class="px-4 py-4">
                                        <div class="flex flex-wrap items-center justify-center gap-1.5">
                                            @foreach ($mr['actions'] as $act)
                                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-primary-50 text-[10px] font-black text-primary-600 border border-primary-100">{{ substr($act, 0, 1) }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-8 flex justify-end">
                    <button type="button" class="inline-flex items-center justify-center rounded-xl bg-primary-600 hover:bg-primary-700 px-7 py-3 text-[14px] font-extrabold text-white shadow-lg shadow-primary-600/20 transition cursor-pointer">
                        Update Global Matrix
                    </button>
                </div>
            </div>
        </section>

        <!-- Overrides & Delegation -->
        <div class="grid gap-6 lg:grid-cols-2">
            <section class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-[var(--ui-shadow-soft)]">
                <div class="mb-5 flex items-center justify-between border-b border-slate-50 bg-slate-50/30 p-5">
                    <div>
                        <h2 class="text-[17px] font-extrabold text-slate-900">User-Based Overrides</h2>
                        <p class="text-[12px] font-medium text-slate-500 mt-0.5">Exceptions to regular role hierarchy.</p>
                    </div>
                    <a href="{{ route('admin.role-permission.add-override') }}" class="ajax-link h-8 w-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition hover:bg-primary-50 hover:text-primary-600 cursor-pointer">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </a>
                </div>
                <div class="p-5 pt-0">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 p-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-slate-100 border border-slate-200"></div>
                                <div>
                                    <div class="text-[14px] font-extrabold text-slate-900">James Wilson</div>
                                    <div class="text-[11px] font-medium text-rose-500">Denied: Billing Export</div>
                                </div>
                            </div>
                            <button class="text-slate-400 hover:text-slate-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-[var(--ui-shadow-soft)]">
                <div class="mb-5 flex items-center justify-between border-b border-slate-50 bg-slate-50/30 p-5">
                    <div>
                        <h2 class="text-[17px] font-extrabold text-slate-900">Group & Department Access</h2>
                        <p class="text-[12px] font-medium text-slate-500 mt-0.5">Departmental scoped role mapping.</p>
                    </div>
                    <a href="{{ route('admin.role-permission.assign-dept-role') }}" class="ajax-link h-8 w-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition hover:bg-primary-50 hover:text-primary-600 cursor-pointer">
                         <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </a>
                </div>
                <div class="p-5 pt-0">
                    <div class="grid gap-3">
                        @foreach ($departmentAccess as $dept)
                            <div class="flex items-center justify-between rounded-xl border border-slate-100 p-4">
                                <div>
                                    <div class="text-[14px] font-extrabold text-slate-900">{{ $dept['name'] }}</div>
                                    <div class="text-[11px] font-medium text-slate-400">Scoped to: {{ $dept['role'] }}</div>
                                </div>
                                <span class="text-[12px] font-bold text-slate-900">{{ $dept['users'] }} Users</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>

        <section class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-[var(--ui-shadow-soft)] pb-5">
                <div class="mb-5 flex items-center justify-between border-b border-slate-50 bg-slate-50/30 p-5">
                    <div>
                        <h2 class="text-[17px] font-extrabold text-slate-900">Delegation & Impersonation</h2>
                        <p class="text-[12px] font-medium text-slate-500 mt-0.5">Allow admins to log in as users for troubleshooting.</p>
                    </div>
                     <a href="{{ route('admin.role-permission.add-delegation') }}" class="ajax-link h-8 w-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition hover:bg-primary-50 hover:text-primary-600 cursor-pointer">
                         <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </a>
                </div>
                <div class="px-5">
                    <div class="grid gap-6 lg:grid-cols-2">
                        <div class="space-y-4">
                            <h3 class="text-[13px] font-black uppercase tracking-widest text-slate-400">Active Delegations</h3>
                            @foreach ($delegations as $admin)
                                <div class="flex items-center justify-between rounded-xl border border-slate-100 p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 flex items-center justify-center rounded-lg bg-primary-50 text-primary-600 font-extrabold text-sm">{{ substr($admin['name'], 0, 1) }}</div>
                                        <div>
                                            <div class="text-[14px] font-extrabold text-slate-900">{{ $admin['name'] }}</div>
                                            <div class="text-[11px] font-medium text-slate-400">{{ $admin['role'] }} @ {{ $admin['scope'] }}</div>
                                        </div>
                                    </div>
                                    <button class="text-primary-600 hover:text-primary-700 text-xs font-bold uppercase tracking-widest cursor-pointer">Revoke</button>
                                </div>
                            @endforeach
                        </div>

                         <div class="flex flex-col items-center justify-center p-8 rounded-2xl bg-slate-50/50 border border-slate-100 border-dashed">
                            <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <p class="text-[13px] font-bold text-slate-600 uppercase tracking-widest">Impersonation Log</p>
                            <p class="text-xs text-slate-400 text-center mt-2 mb-6">Start a secure session to view the portal as a customer or staff member.</p>
                            <a href="{{ route('admin.role-permission.grant-impersonation') }}" class="ajax-link inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary-600 hover:bg-primary-700 px-4 py-3 text-[14px] font-extrabold text-white shadow-lg shadow-primary-600/20 transition cursor-pointer">
                                Audit Impersonations
                            </a>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection

