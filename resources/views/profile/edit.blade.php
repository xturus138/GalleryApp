@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Profile</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
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
