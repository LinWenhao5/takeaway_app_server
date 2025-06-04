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
    <h1>Invite a User</h1>
    @if (session('link'))
        <div class="alert alert-success">
            Invitation link: <a href="{{ session('link') }}" target="_blank">{{ session('link') }}</a>
        </div>
    @endif
    <form method="POST" action="{{ route('admin.invite.store') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">User Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter user email" required>
        </div>
        <button type="submit" class="btn btn-primary">Generate Invitation Link</button>
    </form>
</div>
@endsection