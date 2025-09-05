@extends('layouts.app')

@section('content')
<style>
    .profile-edit-container {
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
    }

    .profile-title {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        text-align: center;
        margin-bottom: 32px;
    }

    .profile-photo-section {
        text-align: center;
        margin-bottom: 32px;
    }

    .profile-photo-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto 20px;
        display: block;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        border: 3px solid #f8f9fa;
    }

    .photo-upload-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-block;
        position: relative;
        overflow: hidden;
    }

    .photo-upload-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .photo-upload-btn:active {
        transform: translateY(0);
    }

    .file-input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        top: 0;
        left: 0;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 16px 20px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 400;
        color: #1f2937;
        background: #ffffff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        box-sizing: border-box;
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-input::placeholder {
        color: #9ca3af;
    }

    .action-buttons {
        display: flex;
        gap: 16px;
        margin-top: 40px;
    }

    .btn-primary {
        flex: 1;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 16px 24px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        flex: 1;
        background: transparent;
        color: #6b7280;
        border: 2px solid #d1d5db;
        border-radius: 12px;
        padding: 16px 24px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        color: #374151;
    }

    @media (max-width: 480px) {
        .profile-card {
            padding: 24px;
            margin: 16px;
        }

        .profile-title {
            font-size: 24px;
        }

        .profile-photo-preview {
            width: 100px;
            height: 100px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
        }
    }
</style>

<div class="profile-edit-container">
    <div class="profile-card">
        <h1 class="profile-title">Edit Profile</h1>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="profile-photo-section">
                <img src="{{ $user->photo ? asset('storage/' . $user->photo) : '/placeholder-user.jpg' }}"
                     alt="Profile Photo"
                     class="profile-photo-preview"
                     id="photo-preview">
                <button type="button" class="photo-upload-btn">
                    Change Photo
                    <input type="file"
                           class="file-input"
                           id="photo"
                           name="photo"
                           accept="image/*"
                           onchange="previewImage(event)">
                </button>
            </div>

            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text"
                       class="form-input"
                       id="name"
                       name="name"
                       value="{{ $user->name }}"
                       placeholder="Enter your name"
                       required>
            </div>

            <div class="form-group">
                <label for="pin" class="form-label">PIN</label>
                <input type="password"
                       class="form-input"
                       id="pin"
                       name="pin"
                       value="{{ $user->pin }}"
                       placeholder="Enter your PIN">
            </div>

            <div class="action-buttons">
                <button type="submit" class="btn-primary">Update Profile</button>
                <a href="{{ route('profile.show') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('photo-preview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
