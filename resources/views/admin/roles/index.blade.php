@extends('layouts.app')

@section('title', 'Role And Permission Management')

@section('content')
    <div class="card">
        <h1>Role And Permission Management</h1>
        <p class="muted">
            Add, update, and delete roles and permissions from one minimal page.
        </p>

        @if (session('status'))
            <p>{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <div>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>

    <div class="card">
        <h2>{{ $editingRole ? 'Update Role' : 'Add Role' }}</h2>

        <form method="POST" action="{{ $editingRole ? route('admin.roles.update', $editingRole->id) : route('admin.roles.store') }}">
            @csrf

            @if ($editingRole)
                @method('PUT')
            @endif

            <div class="field">
                <label for="role_name">Role Name</label>
                <input
                    id="role_name"
                    name="name"
                    value="{{ old('name', $editingRole->name ?? '') }}"
                    required
                >
            </div>

            <div class="field">
                <label for="role_slug">Role Slug</label>
                <input
                    id="role_slug"
                    name="slug"
                    value="{{ old('slug', $editingRole->slug ?? '') }}"
                >
                <p class="muted">Leave blank to auto-create a slug from the role name.</p>
            </div>

            <button class="btn" type="submit">
                {{ $editingRole ? 'Update Role' : 'Add Role' }}
            </button>

            @if ($editingRole)
                <a class="btn secondary" href="{{ route('admin.roles.index') }}">Cancel</a>
            @endif
        </form>
    </div>

    <div class="card">
        <h2>Existing Roles</h2>

        @if ($roles->count())
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Users</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->slug }}</td>
                            <td>{{ $role->users_count }}</td>
                            <td>{{ $role->permissions_count }}</td>
                            <td>
                                <a class="btn secondary" href="{{ route('admin.roles.show', $role->id) }}">Edit</a>
                                <a class="btn secondary" href="{{ route('admin.roles.show', $role->id) }}">Manage Permissions</a>

                                <form method="POST" action="{{ route('admin.roles.delete', $role->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn secondary" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No roles found.</p>
        @endif
    </div>

    <div class="card">
        <h2>{{ $editingPermission ? 'Update Permission' : 'Add Permission' }}</h2>

        <form method="POST" action="{{ $editingPermission ? route('admin.roles.permissions.update', $editingPermission->id) : route('admin.roles.permissions.store') }}">
            @csrf

            @if ($editingPermission)
                @method('PUT')
            @endif

            <div class="field">
                <label for="permission_name">Permission Name</label>
                <input
                    id="permission_name"
                    name="name"
                    value="{{ old('name', $editingPermission->name ?? '') }}"
                    required
                >
            </div>

            <div class="field">
                <label for="permission_slug">Permission Slug</label>
                <input
                    id="permission_slug"
                    name="slug"
                    value="{{ old('slug', $editingPermission->slug ?? '') }}"
                >
                <p class="muted">Leave blank to auto-create a slug from the permission name.</p>
            </div>

            <button class="btn" type="submit">
                {{ $editingPermission ? 'Update Permission' : 'Add Permission' }}
            </button>

            @if ($editingPermission)
                <a class="btn secondary" href="{{ $editingRole ? route('admin.roles.show', $editingRole->id) : route('admin.roles.index') }}">Cancel</a>
            @endif
        </form>
    </div>

    <div class="card">
        <h2>Existing Permissions</h2>

        @if ($permissions->count())
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Roles</th>
                        <th>User Overrides</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->slug }}</td>
                            <td>{{ $permission->roles_count }}</td>
                            <td>{{ $permission->user_overrides_count }}</td>
                            <td>
                                <a
                                    class="btn secondary"
                                    href="{{ $editingRole ? route('admin.roles.show', ['roleId' => $editingRole->id, 'edit_permission_id' => $permission->id]) : route('admin.roles.index', ['edit_permission_id' => $permission->id]) }}"
                                >
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('admin.roles.permissions.delete', $permission->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn secondary" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No permissions found.</p>
        @endif
    </div>

    <div class="card">
        <h2>Permissions For Selected Role</h2>

        @if ($editingRole)
            <p class="muted">
                Checked permissions will be kept for <strong>{{ $editingRole->name }}</strong>.
                Unchecked permissions will be removed when you save.
            </p>

            <form method="POST" action="{{ route('admin.roles.permissions.upsert', $editingRole->id) }}">
                @csrf

                <div class="field">
                    @foreach ($permissions as $permission)
                        <div>
                            <label>
                                <input
                                    type="checkbox"
                                    name="permission_ids[]"
                                    value="{{ $permission->id }}"
                                    {{ in_array($permission->id, $editingRolePermissionIds, true) ? 'checked' : '' }}
                                >
                                {{ $permission->name }} ({{ $permission->slug }})
                            </label>
                        </div>
                    @endforeach
                </div>

                <button class="btn" type="submit">Save Role Permissions</button>
            </form>
        @else
            <p>Select a role from the role table to manage its permissions.</p>
        @endif
    </div>
@endsection
