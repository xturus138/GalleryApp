@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="header">
        <h1>Gallery</h1>
        <div class="action-buttons">
            <button>Upload</button>
            <button>New Folder</button>
        </div>
    </div>

    <div class="gallery-grid">
        <div class="gallery-item">
            <img src="/placeholder.jpg" alt="Image">
            <div class="gallery-item-info">
                <h4>Image 1</h4>
                <p>12/05/2024</p>
            </div>
        </div>
        <div class="gallery-item">
            <img src="/video-thumbnail.png" alt="Video">
            <div class="gallery-item-info">
                <h4>Video 1</h4>
                <p>12/05/2024</p>
            </div>
        </div>
        <div class="gallery-item">
            <img src="/placeholder.jpg" alt="Image">
            <div class="gallery-item-info">
                <h4>Image 2</h4>
                <p>11/05/2024</p>
            </div>
        </div>
        </div>
@endsection