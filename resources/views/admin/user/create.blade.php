@extends('layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="{{ route('admin.invite.create') }}">Invitations</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Invitation</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header text-white">
            <h4 class="mb-0">Invite a User</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.invite.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter user email" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Send Invitation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection