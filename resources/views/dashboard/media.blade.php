@extends('layouts.app')

@section('body-class', 'media-full-page')

@section('title', 'View Media')

@section('content')
<div class="media-detail-page">
    <div class="media-header">
        <div class="header-content">
            <!-- Removed back button as per user request -->
            <div class="media-info" style="flex: 1; text-align: center;">
                <h1 class="media-title">{{ $asset->title ?: $asset->original_filename }}</h1>
                <div class="media-meta">
                    <span class="meta-item">{{ $asset->formatted_size }}</span>
                    <span class="meta-item">{{ \Carbon\Carbon::parse($asset->created_at)->format('M j, Y') }}</span>
                </div>
            </div>
            <div class="media-actions">
                <button class="action-btn download-btn" onclick="downloadAsset('{{ $asset->blob_url }}', '{{ $asset->original_filename }}')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7,10 12,15 17,10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Download
                </button>
                <button class="action-btn share-btn" onclick="shareAsset('{{ $asset->blob_url }}', '{{ $asset->title ?: $asset->original_filename }}')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="18" cy="5" r="3"></circle>
                        <circle cx="6" cy="12" r="3"></circle>
                        <circle cx="18" cy="19" r="3"></circle>
                        <path d="m8.59 13.51 6.83 3.98"></path>
                        <path d="m15.41 6.51-6.82 3.98"></path>
                    </svg>
                    Share
                </button>
            </div>
        </div>
    </div>

    <div class="media-content">
        <div class="media-display">
            @if(str_starts_with($asset->file_type, 'image/'))
                <img src="{{ $asset->blob_url }}" alt="{{ $asset->title ?: $asset->original_filename }}" class="media-image">
            @elseif(str_starts_with($asset->file_type, 'video/'))
                <video controls class="media-video" preload="metadata">
                    <source src="{{ $asset->blob_url }}" type="{{ $asset->file_type }}">
                    Your browser does not support the video tag.
                </video>
            @endif
        </div>

        <div class="media-sidebar">
            <div class="caption-section">
                @if($asset->caption)
                    <p class="media-caption">{{ $asset->caption }}</p>
                @endif
            </div>

            <div class="interactions-section">
                <div class="like-section">
                    <button class="like-btn" data-asset-id="{{ $asset->id }}" data-is-liked="{{ $asset->is_liked_by_user ? 'true' : 'false' }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="{{ $asset->is_liked_by_user ? 'red' : 'none' }}" stroke="currentColor" stroke-width="2" class="heart-icon">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        <span class="like-count">{{ $asset->likes_count }}</span>
                    </button>
                </div>
            </div>

            <div class="comments-section">
                <h3 class="comments-title">Comments</h3>
                <div class="comments-list" id="commentsList">
                    @foreach($comments as $comment)
                        <div class="comment">
                            <div class="comment-avatar">
                                @if($comment->user->photo)
                                    <img src="/storage/{{ $comment->user->photo }}" alt="{{ $comment->user->name }}">
                                @else
                                    <span>{{ substr($comment->user->name, 0, 2) }}</span>
                                @endif
                            </div>
                            <div class="comment-content">
                                <div class="comment-header">
                                    <strong>{{ $comment->user->name }}</strong>
                                    <span class="comment-time">{{ \Carbon\Carbon::parse($comment->created_at)->diffForHumans() }}</span>
                                </div>
                                <p class="comment-text">{{ $comment->comment }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <form class="comment-form" id="commentForm">
                    <div class="comment-input-group">
                        <input type="text" placeholder="Add a comment..." required maxlength="1000" class="comment-input">
                        <button type="submit" class="comment-submit-btn">Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    :root {
        --bg-primary: #ffffff;
        --bg-secondary: #f8fafc;
        --bg-hover: #f9fafb;
        --border-color: #e5e7eb;
        --text-primary: #111827;
        --text-secondary: #374151;
        --text-muted: #6b7280;
        --brand-primary: #6366f1;
        --brand-secondary: #4f46e5;
        --success: #059669;
        --error: #ef4444;
    }

    [data-theme="dark"] {
        --bg-primary: #1f2937;
        --bg-secondary: #111827;
        --bg-hover: #374151;
        --border-color: #374151;
        --text-primary: #f9fafb;
        --text-secondary: #e5e7eb;
        --text-muted: #9ca3af;
    }

    .media-detail-page {
        min-height: 100vh;
        background: var(--bg-secondary);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
        max-width: 72rem; /* max-w-6xl */
        margin: 0 auto;
        box-sizing: border-box;
    }

    .media-header {
        background: var(--bg-primary);
        border-bottom: 1px solid var(--border-color);
        padding: 20px 0;
        width: 100%;
        max-width: 72rem;
        box-sizing: border-box;
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .media-info {
        flex: 1;
        text-align: center;
    }

    .media-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 8px 0;
    }

    .media-meta {
        display: flex;
        justify-content: center;
        gap: 16px;
    }

    .meta-item {
        font-size: 14px;
        color: var(--text-muted);
        font-weight: 500;
    }

    .media-actions {
        display: flex;
        gap: 12px;
    }

    .action-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: var(--bg-primary);
        border: 2px solid var(--border-color);
        border-radius: 12px;
        color: var(--text-secondary);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        background: var(--bg-hover);
        border-color: var(--brand-primary);
        color: var(--brand-primary);
    }

    .media-content {
        flex-grow: 1;
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
        padding: 40px 20px;
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 48px;
        box-sizing: border-box;
    }

    .media-display {
        background: var(--bg-primary);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 600px;
        transition: all 0.3s ease;
    }

    .media-display:hover {
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .media-image, .media-video {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .media-sidebar {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .caption-section {
        background: var(--bg-primary);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .media-caption {
        margin: 0;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    .interactions-section {
        background: var(--bg-primary);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .like-section {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .like-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 12px 20px;
        border-radius: 12px;
        transition: all 0.2s ease;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .like-btn:hover {
        background: var(--bg-hover);
    }

    .heart-icon {
        transition: all 0.2s ease;
    }

    .like-count {
        font-size: 16px;
    }

    .comments-section {
        background: var(--bg-primary);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .comments-title {
        margin: 0 0 16px 0;
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .comments-list {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 20px;
        max-height: 400px;
        overflow-y: auto;
    }

    .comment {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .comment-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--brand-primary);
        color: white;
        font-weight: 600;
    }

    .comment-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .comment-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .comment-header strong {
        color: var(--text-primary);
        font-weight: 600;
    }

    .comment-time {
        font-size: 12px;
        color: var(--text-muted);
    }

    .comment-text {
        margin: 0;
        color: var(--text-secondary);
        line-height: 1.5;
    }

    .comment-form {
        margin-top: auto;
    }

    .comment-input-group {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .comment-input {
        flex: 1;
        padding: 12px 16px;
        border: 2px solid var(--border-color);
        border-radius: 24px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: var(--bg-primary);
        color: var(--text-primary);
    }

    .comment-input:focus {
        outline: none;
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .comment-submit-btn {
        background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
        border: none;
        color: white;
        padding: 12px 20px;
        border-radius: 24px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .comment-submit-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 16px;
            padding: 0 20px;
        }
    
        .media-title {
            font-size: 20px;
        }
    
        .media-meta {
            justify-content: center;
        }
    
        .media-actions {
            justify-content: center;
        }
    
        .media-content {
            grid-template-columns: 1fr;
            gap: 32px;
            padding: 40px 20px;
        }
    
        .media-display {
            min-height: 400px;
            border-radius: 16px;
        }
    
        .comments-list {
            max-height: 300px;
        }
    }

    @media (max-width: 480px) {
        .media-header {
            padding: 16px 0;
        }
    
        .header-content {
            padding: 0 16px;
        }
    
        .media-title {
            font-size: 18px;
        }
    
        .media-meta {
            flex-direction: column;
            gap: 8px;
        }
    
        .media-actions {
            flex-direction: column;
            width: 100%;
        }
    
        .action-btn {
            justify-content: center;
        }
    
        .media-content {
            padding: 24px 16px;
            gap: 24px;
        }
    
        .media-display {
            min-height: 300px;
        }
    
        .comments-section {
            padding: 16px;
        }
    
        .comment-input-group {
            flex-direction: column;
            gap: 8px;
        }
    
        .comment-submit-btn {
            width: 100%;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle like/unlike functionality
    const likeBtn = document.querySelector('.like-btn');
    if (likeBtn) {
        likeBtn.addEventListener('click', function() {
            const assetId = this.getAttribute('data-asset-id');
            const isLiked = this.getAttribute('data-is-liked') === 'true';
            const url = `/assets/${assetId}/like`;
            const method = isLiked ? 'DELETE' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const newLiked = !isLiked;
                    this.setAttribute('data-is-liked', newLiked.toString());
                    const heartIcon = this.querySelector('.heart-icon');
                    const likeCount = this.querySelector('.like-count');
                    heartIcon.setAttribute('fill', newLiked ? 'red' : 'none');
                    likeCount.textContent = data.likes_count || 0;
                } else {
                    alert('Error: ' + (data.error || data.message || 'Something went wrong'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your request.');
            });
        });
    }

    // Handle comment form submission
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const input = this.querySelector('.comment-input');
            const commentText = input.value.trim();

            if (!commentText) return;

            const assetId = '{{ $asset->id }}';

            fetch(`/assets/${assetId}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ comment: commentText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    input.value = '';
                    // Reload comments
                    loadComments(assetId);
                } else {
                    alert('Failed to add comment.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding your comment.');
            });
        });
    }

    function loadComments(assetId) {
        fetch(`/assets/${assetId}/comments`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(comments => {
            const commentsList = document.getElementById('commentsList');
            commentsList.innerHTML = '';

            comments.forEach(comment => {
                const commentHtml = `
                    <div class="comment">
                        <div class="comment-avatar">
                            ${comment.user.photo ? `<img src="/storage/${comment.user.photo}" alt="${comment.user.name}">` : `<span>${comment.user.name.substring(0, 2)}</span>`}
                        </div>
                        <div class="comment-content">
                            <div class="comment-header">
                                <strong>${comment.user.name}</strong>
                                <span class="comment-time">${new Date(comment.created_at).toLocaleString()}</span>
                            </div>
                            <p class="comment-text">${comment.comment}</p>
                        </div>
                    </div>
                `;
                commentsList.insertAdjacentHTML('beforeend', commentHtml);
            });
        })
        .catch(error => {
            console.error('Error loading comments:', error);
        });
    }
});

function downloadAsset(url, filename) {
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function shareAsset(url, filename) {
    if (navigator.share) {
        navigator.share({
            title: filename,
            url: url
        }).catch(console.error);
    } else {
        // Fallback: copy URL to clipboard
        navigator.clipboard.writeText(url).then(() => {
            alert('Link copied to clipboard!');
        }).catch(() => {
            alert('Share not supported on this device');
        });
    }
}
</script>
@endsection
