@extends('layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">User Management</h1>
        <a href="{{ route('admin.invite.create') }}" class="btn btn-primary">Create Invitation</a>
    </div>

    <!-- Pending Invitations -->
    <h2 class="mt-5">Pending Invitations</h2>
    <x-table class="mt-4">
        <x-slot:head>
            <tr>
                <th>#</th>
                <th>Email</th>
                <th>Token</th>
                <th>Invitation Sent At</th>
                <th class="text-center">Actions</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @forelse ($pendingInvitations as $invitation)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $invitation->email }}</td>
                <td class="text-truncate" style="max-width: 150px;">{{ $invitation->token }}</td>
                <td>{{ $invitation->created_at }}</td>
                <td class="text-center">
                    <form method="POST" action="{{ route('admin.invite.store', $invitation) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="email" value="{{ $invitation->email }}">
                        <input type="hidden" name="role" value="{{ $invitation->role }}">
                        <button type="submit" class="btn btn-outline-primary btn-sm">Resend</button>
                    </form>
                    <form method="POST" action="{{ route('admin.invite.cancel', $invitation) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">Cancel</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No pending invitations.</td>
            </tr>
            @endforelse
        </x-slot:body>
    </x-table>

    <!-- Registered Users -->
    <h2 class="mt-5">Registered Users</h2>
    <x-table class="mt-4">
        <x-slot:head>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th class="text-center">Actions</th>
            </tr>
        </x-slot:head>
        <x-slot:body>
            @foreach ($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                <td class="text-center">
                    <x-delete-confirm
                        :action="route('admin.users.destroy', $user)"
                        title="Delete User?"
                        text="Are you sure you want to delete the user '{{ $user->email }}'?"
                        confirm-button-text="Yes, delete it!"
                        success-message="User deleted successfully!"
                        error-message="Failed to delete the user."
                        button-class="btn btn-outline-danger btn-sm"
                    >
                        <button type="button" class="btn btn-outline-danger btn-sm">Delete</button>
                    </x-delete-confirm>
                </td>
            </tr>
            @endforeach
        </x-slot:body>
    </x-table>
</div>
@endsection