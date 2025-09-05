@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Profile</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Your Profile Information</h5>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>PIN:</strong> *****</p> <!-- Masked for security -->
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
            <form action="{{ route('profile.destroy') }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account?')">Delete Account</button>
            </form>
        </div>
    </div>
</div>
@endsection
