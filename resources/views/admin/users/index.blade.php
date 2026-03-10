@extends('layouts.app')

@section('content')
    <div class="page-shell !space-y-4 md:!space-y-6">
    <div class="card">
        <h1>Admin Access Control Console</h1>
        <p class="muted">
            Manage B2B approvals, create internal users, assign user-level permission overrides, delegated scopes,
            and impersonation with audit.
        </p>
    </div>

    <div class="card">
        <h2>Pending B2B Approvals</h2>
        @if ($pendingB2bUsers->count())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>B2B Type</th>
                            <th>Company</th>
                            <th>Requested At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingB2bUsers as $pending)
                            <tr>
                                <td>{{ $pending->name }}</td>
                                <td>{{ $pending->email }}</td>
                                <td>{{ strtoupper($pending->b2b_type ?? '-') }}</td>
                                <td>{{ $pending->company_name ?? '-' }}</td>
                                <td>{{ $pending->created_at }}</td>
                                <td>
                                    <div class="table-actions">
                                        <form method="POST" action="{{ route('admin.users.b2b.approve', $pending->id) }}" class="inline-form">
                                            @csrf
                                            <button class="btn" type="submit">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.b2b.reject', $pending->id) }}" class="inline-form">
                                            @csrf
                                            <button class="btn secondary" type="submit">Reject</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No pending B2B approvals.</p>
        @endif
    </div>

    <div class="card">
        <h2>Create Internal User</h2>
        <p class="muted">
            Internal users are operational users and can belong to multiple departments.
            Creation is allowed for Admin and Delegated Admin.
        </p>
        <form method="POST" action="{{ route('admin.users.internal.store') }}">
            @csrf
            <div class="field">
                <label for="internal_name">Name</label>
                <input id="internal_name" name="name" required>
            </div>
            <div class="field">
                <label for="internal_email">Email</label>
                <input id="internal_email" type="email" name="email" required>
            </div>
            <div class="field">
                <label for="internal_password">Password</label>
                <input id="internal_password" type="password" name="password" required>
            </div>
            <div class="field">
                <label for="internal_password_confirmation">Confirm Password</label>
                <input id="internal_password_confirmation" type="password" name="password_confirmation" required>
            </div>
            <div class="field">
                <label for="department_ids">Departments (multi-select)</label>
                <select id="department_ids" name="department_ids[]" multiple size="6" required>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn" type="submit">Create Internal User</button>
        </form>
    </div>

    <div class="card">
        <h2>Create Delegated Admin</h2>
        <p class="muted">
            Delegated Admin is a separate role with limited scope and restricted admin-level access by default.
            Creation is allowed for Admin only.
        </p>
        <form method="POST" action="{{ route('admin.users.delegated.store') }}">
            @csrf
            <div class="field">
                <label for="delegated_name">Name</label>
                <input id="delegated_name" name="name" required>
            </div>
            <div class="field">
                <label for="delegated_email">Email</label>
                <input id="delegated_email" type="email" name="email" required>
            </div>
            <div class="field">
                <label for="delegated_password">Password</label>
                <input id="delegated_password" type="password" name="password" required>
            </div>
            <div class="field">
                <label for="delegated_password_confirmation">Confirm Password</label>
                <input id="delegated_password_confirmation" type="password" name="password_confirmation" required>
            </div>
            <button class="btn" type="submit">Create Delegated Admin</button>
        </form>
    </div>

    <div class="card">
        <h2>User-Level Permission Override</h2>
        <form method="POST" action="{{ route('admin.users.permissions.set', 0) }}" id="override-form">
            @csrf
            <div class="field">
                <label for="override_user_id">User</label>
                <select id="override_user_id" name="override_user_id" required>
                    <option value="">Select user</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }}) [{{ $user->user_type }}]</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="permission_id">Permission</label>
                <select id="permission_id" name="permission_id" required>
                    <option value="">Select permission</option>
                    @foreach ($permissions as $permission)
                        <option value="{{ $permission->id }}">{{ $permission->slug }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="grant_type">Override Type</label>
                <select id="grant_type" name="grant_type" required>
                    <option value="allow">Allow</option>
                    <option value="deny">Deny</option>
                </select>
            </div>
            <button class="btn" type="submit">Save Override</button>
        </form>

        <h3>Existing Overrides</h3>
        @if ($userOverrides->count())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Permission</th>
                            <th>Type</th>
                            <th>Granted By</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($userOverrides as $override)
                            <tr>
                                <td>{{ $override->user_name }}<div class="muted">{{ $override->user_email }}</div></td>
                                <td>{{ $override->permission_slug }}</td>
                                <td>{{ strtoupper($override->grant_type) }}</td>
                                <td>{{ $override->granted_by_name ?? '-' }}</td>
                                <td>{{ $override->updated_at }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.users.permissions.delete', $override->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn secondary" type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No user-level overrides yet.</p>
        @endif
    </div>

    <div class="card">
        <h2>Delegated Admin Scope (Company)</h2>
        <form method="POST" action="{{ route('admin.users.scopes.set', 0) }}" id="scope-form">
            @csrf
            <div class="field">
                <label for="scope_user_id">Delegated Admin User</label>
                <select id="scope_user_id" name="scope_user_id" required>
                    <option value="">Select delegated admin</option>
                    @foreach ($delegatedAdmins as $delegatedAdmin)
                        <option value="{{ $delegatedAdmin->id }}">{{ $delegatedAdmin->name }} ({{ $delegatedAdmin->email }})</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="company_id">Company Scope</label>
                <select id="company_id" name="company_id" required>
                    <option value="">Select company</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn" type="submit">Assign Scope</button>
        </form>

        <h3>Existing Delegated Scopes</h3>
        @if ($delegatedScopes->count())
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Delegated Admin</th>
                            <th>Scope</th>
                            <th>Assigned By</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($delegatedScopes as $scope)
                            <tr>
                                <td>{{ $scope->delegated_name }}<div class="muted">{{ $scope->delegated_email }}</div></td>
                                <td>{{ strtoupper($scope->scope_type) }}: {{ $scope->company_name ?? $scope->scope_value }}</td>
                                <td>{{ $scope->assigned_by_name ?? '-' }}</td>
                                <td>{{ $scope->updated_at }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.users.scopes.delete', $scope->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn secondary" type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No delegated scopes configured.</p>
        @endif
    </div>

    <div class="card">
        <h2>Impersonate User (Audited)</h2>
        <p class="muted">Only use for support/debug; all sessions are logged with start/end timestamps.</p>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ strtoupper($user->user_type) }}</td>
                            <td>{{ strtoupper($user->status) }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.impersonation.start', $user->id) }}">
                                    @csrf
                                    <input type="hidden" name="reason" value="Support troubleshooting">
                                    <button class="btn secondary" type="submit">Impersonate</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const adminUsersBase = "{{ url('/admin/users') }}";
        const overrideForm = document.getElementById('override-form');
        const overrideUserSelect = document.getElementById('override_user_id');
        overrideForm.addEventListener('submit', function () {
            const selectedUserId = overrideUserSelect.value;
            overrideForm.action = `${adminUsersBase}/${selectedUserId || '0'}/permissions`;
        });

        const scopeForm = document.getElementById('scope-form');
        const scopeUserSelect = document.getElementById('scope_user_id');
        scopeForm.addEventListener('submit', function () {
            const selectedUserId = scopeUserSelect.value;
            scopeForm.action = `${adminUsersBase}/${selectedUserId || '0'}/scopes/company`;
        });
    </script>
    </div>
@endsection
