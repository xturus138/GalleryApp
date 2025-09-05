@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Profile</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group text-center">
                    <img src="{{ $user->photo ? asset('storage/' . $user->photo) : '/placeholder-user.jpg' }}" alt="Current Photo" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                    <div>
                        <label for="photo">Profile Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                </div>
                <div class="form-group">
                    <label for="pin">PIN</label>
                    <input type="password" class="form-control" id="pin" name="pin" value="{{ $user->pin }}">
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <a href="{{ route('profile.show') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
