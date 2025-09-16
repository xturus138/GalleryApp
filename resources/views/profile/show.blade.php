@extends('layouts.app')

@section('body-class')
    profile-page
@endsection

@section('content')
<style>
    .profile-view-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 20px;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        padding: 40px;
        width: 100%;
        max-width: 480px;
        box-sizing: border-box;
        text-align: center;
    }

    .profile-title {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 32px;
    }

    .profile-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 24px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        border: 3px solid #f8f9fa;
        display: block;
    }

    .profile-name {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 16px;
    }

    .profile-pin {
        display: inline-block;
        background: #f3f4f6;
        color: #4b5563;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 32px;
    }

    .edit-profile-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px 28px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .edit-profile-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    @media (max-width: 480px) {
        .profile-card {
            padding: 24px;
            margin: 16px;
        }

        .profile-title {
            font-size: 24px;
        }

        .profile-photo {
            width: 100px;
            height: 100px;
        }

        .profile-name {
            font-size: 20px;
        }

        .profile-pin {
            font-size: 14px;
            padding: 8px 16px;
        }
    }
</style>

<a href="{{ route('dashboard') }}" class="back-button">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="15,18 9,12 15,6"></polyline>
    </svg>
    Back to Dashboard
</a>

<div class="profile-view-container">
    <div class="profile-card">
        <h1 class="profile-title">Profile</h1>
        <img src="{{ $user->photo ? asset('storage/' . $user->photo) : '/placeholder-user.jpg' }}" alt="Profile Photo" class="profile-photo">
        <div class="profile-name">{{ $user->name }}</div>
        <div class="profile-pin">PIN: *****</div>
        <a href="{{ route('profile.edit') }}" class="edit-profile-btn">Edit Profile</a>
    </div>
</div>
@endsection
