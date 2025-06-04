<!-- filepath: resources/views/auth/invitation/register.blade.php -->
@extends('layouts.auth')

@section('title', 'Register')

@section('header', 'Complete Your Registration')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('invite.complete') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter your full name" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm your password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Complete Registration</button>
    </form>
</div>
@endsection