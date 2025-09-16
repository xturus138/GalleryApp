@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="modern-header">
        <div class="header-content">
            <h1 class="gallery-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="title-icon">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="9" cy="9" r="2"></circle>
                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                </svg>
                <span id="current-view-title">All Media</span>
            </h1>
            <div class="header-actions">
                <button class="modern-btn primary-btn" onclick="showUploadModal()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17,8 12,3 7,8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    Upload
                </button>
            </div>
        </div>
        <div class="storage-progress">
            <div class="storage-label">
                <span>Storage Used</span>
                <span class="storage-text">{{ $totalStorageFormatted }} / {{ $totalStorageLimit }}</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $percentageUsed }}%"></div>
            </div>
            <span class="progress-percentage">{{ $percentageUsed }}%</span>
        </div>
    </div>

    <div id="loading-skeleton" class="gallery-grid">
        @for ($i = 0; $i < 12; $i++)
            <div class="skeleton-item">
                <div class="skeleton-image"></div>
                <div class="skeleton-text-container">
                    <div class="skeleton-text skeleton-text-title"></div>
                    <div class="skeleton-text skeleton-text-date"></div>
                    <div class="skeleton-text skeleton-text-caption"></div>
                </div>
            </div>
        @endfor
    </div>

    <div id="gallery-container" class="gallery-grid" style="display: none;">
    </div>

    <!-- Modern Pagination for Assets -->
    <div id="assets-pagination" class="modern-pagination" style="display: none;">
        <button id="assets-prev" class="pagination-arrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15,18 9,12 15,6"></polyline>
            </svg>
        </button>
        <div id="assets-page-numbers" class="pagination-numbers"></div>
        <button id="assets-next" class="pagination-arrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9,18 15,12 9,6"></polyline>
            </svg>
        </button>
    </div>

    <!-- Modern Pagination for Folders -->
    <div id="folders-pagination" class="modern-pagination" style="display: none;">
        <button id="folders-prev" class="pagination-arrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15,18 9,12 15,6"></polyline>
            </svg>
        </button>
        <div id="folders-page-numbers" class="pagination-numbers"></div>
        <button id="folders-next" class="pagination-arrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9,18 15,12 9,6"></polyline>
            </svg>
        </button>
    </div>

    <div id="uploadModal" class="edit-modal-overlay" style="display: none;">
        <div class="edit-modal-container">
            <div class="edit-modal-card">
                <div class="edit-modal-header">
                    <h2 class="edit-modal-title">Unggah File</h2>
                    <button class="edit-modal-close" onclick="hideUploadModal()" aria-label="Close modal">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="edit-modal-body">
                    <form id="uploadForm" action="{{ route('asset.upload') }}" method="POST" enctype="multipart/form-data" class="edit-form">
                        @csrf
                        <div class="edit-form-group">
                            <label for="fileInput" class="edit-form-label">Pilih File</label>
                            <input type="file" name="files[]" id="fileInput" multiple required class="edit-form-input">
                        </div>
                        <div class="edit-form-group">
                            <label for="title" class="edit-form-label">Judul</label>
                            <input type="text" name="title" id="title" placeholder="Tambahkan judul untuk file Anda" class="edit-form-input">
                        </div>
                        <div class="edit-form-group">
                            <label for="caption" class="edit-form-label">Keterangan (Opsional)</label>
                            <textarea name="caption" id="caption" placeholder="Tambahkan keterangan untuk foto atau video Anda..." class="edit-form-textarea"></textarea>
                        </div>
                        <div class="edit-form-group">
                            <label for="folder" class="edit-form-label">Pilih Folder (Opsional)</label>
                            <select name="folder" id="folder" class="edit-form-select">
                                <option value="none">Tidak ada folder</option>
                                </select>
                        </div>
                    </form>
                </div>
                <div class="edit-modal-footer">
                    <button type="button" class="edit-btn edit-btn-secondary" onclick="hideUploadModal()">Batal</button>
                    <button type="submit" form="uploadForm" class="edit-btn edit-btn-primary">Unggah File</button>
                </div>
            </div>
        </div>
    </div>

    <div id="assetViewerModal" class="edit-modal-overlay" style="display: none;">
        <div class="edit-modal-container" style="width: 95%; max-width: 1200px;">
            <div class="edit-modal-card modal-content-viewer" id="assetContent" style="border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); max-height: 96vh; overflow: hidden; display: flex; flex-direction: column;">
                <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px 16px; border-bottom: 1px solid #e5e7eb; background: #ffffff; border-radius: 12px 12px 0 0;">
                    <div class="modal-title" id="assetViewerTitle" style="font-size: 18px; font-weight: 600; color: #111827; margin: 0; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></div>
                    <div class="modal-actions" style="display: flex; gap: 8px; align-items: center;">
                        <button class="action-btn download-btn" id="downloadBtn" title="Download" style="background: none; border: none; cursor: pointer; padding: 8px; border-radius: 8px; color: #6b7280; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7,10 12,15 17,10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                        </button>
                        <button class="action-btn share-btn" id="shareBtn" title="Share" style="background: none; border: none; cursor: pointer; padding: 8px; border-radius: 8px; color: #6b7280; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="18" cy="5" r="3"></circle>
                                <circle cx="6" cy="12" r="3"></circle>
                                <circle cx="18" cy="19" r="3"></circle>
                                <path d="m8.59 13.51 6.83 3.98"></path>
                                <path d="m15.41 6.51-6.82 3.98"></path>
                            </svg>
                        </button>
                        <button class="close-button edit-modal-close" onclick="hideAssetViewer()" title="Close" style="background: none; border: none; cursor: pointer; padding: 8px; border-radius: 8px; color: #6b7280; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </div>
                <div id="mediaContainer" style="flex: 1; display: flex; justify-content: center; align-items: center; padding: 24px; background: #f8fafc; min-height: 300px; max-height: 70vh; overflow: hidden;"></div>
                <div id="assetViewerComments" class="comments-section" style="margin-top: 10px; padding: 16px 24px; background: #ffffff; border-radius: 0 0 12px 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 12px;">
                    <div class="comments-header" style="border-bottom: 1px solid #e5e7eb; padding-bottom: 8px; margin-bottom: 12px;">
                        <h4 style="margin: 0; font-weight: 600; color: #111827;">Comments</h4>
                    </div>
                    <div id="commentsList" class="comments-list" style="max-height: 180px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px;"></div>
                    <form id="commentForm" class="comment-form" style="display: flex; gap: 12px; align-items: center; margin: 16px 0;">
                        <div class="comment-input-container" style="flex: 1; display: flex; gap: 12px; align-items: center;">
                            <input type="text" placeholder="Add a comment..." required maxlength="1000" style="flex: 1; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 9999px; font-size: 0.875rem; transition: all 0.3s ease;">
                            <button type="submit" class="comment-submit-btn" style="background: linear-gradient(90deg, #3b82f6, #2563eb); border: none; color: white; padding: 12px 20px; border-radius: 9999px; font-weight: 600; cursor: pointer; transition: background 0.3s ease; white-space: nowrap;">Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="createFolderModal" class="edit-modal-overlay" style="display: none;">
        <div class="edit-modal-container">
            <div class="edit-modal-card">
                <div class="edit-modal-header">
                    <h2 class="edit-modal-title">Buat Folder Baru</h2>
                    <button class="edit-modal-close" onclick="hideCreateFolderModal()" aria-label="Close modal">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="edit-modal-body">
                    <form id="createFolderForm" action="{{ route('folder.create') }}" method="POST" class="edit-form">
                        @csrf
                        <div class="edit-form-group">
                            <label for="folderName" class="edit-form-label">Nama Folder</label>
                            <input type="text" name="name" id="folderName" required class="edit-form-input">
                        </div>
                    </form>
                </div>
                <div class="edit-modal-footer">
                    <button type="button" class="edit-btn edit-btn-secondary" onclick="hideCreateFolderModal()">Batal</button>
                    <button type="submit" form="createFolderForm" class="edit-btn edit-btn-primary">Buat Folder</button>
                </div>
            </div>
        </div>
    </div>

    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="loading-content">
            <div class="spinner" id="uploadSpinner"></div>
            <div class="loading-text" id="loadingText">Mengunggah file...</div>
            <div class="loading-details" id="loadingDetails"></div>
        </div>
    </div>

    <style>
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-hover: #f9fafb;
            --bg-selected: #f0f4ff;
            --border-color: #e5e7eb;
            --border-hover: #d1d5db;
            --text-primary: #111827;
            --text-secondary: #374151;
            --text-muted: #6b7280;
            --text-light: #9ca3af;
            --brand-primary: #6366f1;
            --brand-secondary: #4f46e5;
            --success: #059669;
            --error: #ef4444;
        }

        [data-theme="dark"] {
            --bg-primary: #1f2937;
            --bg-secondary: #111827;
            --bg-hover: #374151;
            --bg-selected: #1e1b4b;
            --border-color: #374151;
            --border-hover: #4b5563;
            --text-primary: #f9fafb;
            --text-secondary: #e5e7eb;
            --text-muted: #9ca3af;
            --text-light: #6b7280;
            --brand-primary: #818cf8;
            --brand-secondary: #6366f1;
        }

        /* Modern Header Styles */
        .modern-header {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .gallery-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .title-icon {
            color: #6366f1;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .modern-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .primary-btn {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .primary-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.5);
        }

        .secondary-btn {
            background: #ffffff;
            color: #374151;
            border: 2px solid #e5e7eb;
        }

        .secondary-btn:hover {
            background: #f9fafb;
            border-color: #d1d5db;
            transform: translateY(-1px);
        }

        .storage-progress {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .storage-label {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 120px;
        }

        .storage-label span:first-child {
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .storage-text {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        .progress-bar {
            flex: 1;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .progress-percentage {
            font-size: 14px;
            font-weight: 600;
            color: #059669;
            min-width: 40px;
            text-align: right;
        }

        /* Modern Gallery Grid */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .gallery-card {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out both;
        }

        .gallery-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .card-image-container {
            position: relative;
            overflow: hidden;
            aspect-ratio: 4/3;
        }

        .gallery-card img,
        .gallery-card video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .gallery-card:hover img,
        .gallery-card:hover video {
            transform: scale(1.05);
        }

        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-card:hover .card-overlay {
            opacity: 1;
        }

        .overlay-actions {
            display: flex;
            gap: 12px;
        }

        .overlay-btn {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            backdrop-filter: blur(10px);
        }

        .overlay-btn:hover {
            transform: scale(1.1);
            background: #ffffff;
        }

        .edit-btn:hover {
            color: #3b82f6;
        }

        .delete-btn:hover {
            color: #ef4444;
        }

        .share-btn:hover {
            color: #10b981;
        }

        .card-content {
            padding: 20px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            gap: 12px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin: 0;
            line-height: 1.4;
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .view-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .view-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .meta-item {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }

        .like-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .like-btn:hover {
            background: #f3f4f6;
        }

        .heart-icon {
            transition: all 0.2s ease;
        }

        .like-btn:hover .heart-icon {
            transform: scale(1.2);
        }

        .like-count {
            font-size: 12px;
            color: #6b7280;
            font-weight: 500;
        }

        .card-caption {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        /* Viewer modal styles integrated into edit-modal */
        .edit-modal-overlay.modal-viewer {
            align-items: flex-start;
            padding: 2vh;
        }

        .edit-modal-container.viewer-container {
            width: 100%;
            max-width: none;
            height: 96vh;
        }

        .edit-modal-card.modal-content-viewer {
            margin: 0;
            border-radius: 12px;
            height: 100%;
            max-height: none;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px 16px;
            border-bottom: 1px solid #e5e7eb;
            background: #ffffff;
            border-radius: 12px 12px 0 0;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin: 0;
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .modal-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            color: #6b7280;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn:hover {
            background-color: #f3f4f6;
            color: #374151;
        }

        .download-btn:hover {
            color: #059669;
        }

        .share-btn:hover {
            color: #3b82f6;
        }

        .close-button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            color: #6b7280;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-button:hover {
            background-color: #f3f4f6;
            color: #374151;
        }

        #mediaContainer {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 24px;
            background: #f8fafc;
            min-height: 300px;
            max-height: 70vh;
            overflow: hidden;
        }

        #mediaContainer img,
        #mediaContainer video {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: block;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }
        .loading-content {
            text-align: center;
            padding: 20px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-width: 250px;
        }
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border-left-color: #6366f1;
            animation: spin 1s ease infinite;
            margin: 0 auto 15px;
        }
        .spinner.photo-upload {
            border-left-color: #10b981; /* Green for photos */
        }
        .spinner.video-upload {
            border-left-color: #f59e0b; /* Orange for videos */
        }
        .loading-text {
            font-size: 16px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }
        .loading-details {
            font-size: 14px;
            color: #6b7280;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .skeleton-item {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .skeleton-image {
            width: 100%;
            height: 150px;
            background-color: #e2e8f0;
            animation: pulse-bg 1.5s infinite;
        }
        .skeleton-text-container {
            padding: 10px;
        }
        .skeleton-text {
            height: 12px;
            background-color: #e2e8f0;
            border-radius: 4px;
            animation: pulse-bg 1.5s infinite;
            margin-bottom: 8px;
        }
        .skeleton-text-title {
            width: 80%;
        }
        .skeleton-text-date {
            width: 50%;
        }
        .skeleton-text-caption {
            width: 90%;
            height: 10px;
        }
        @keyframes pulse-bg {
            0% { background-color: #e2e8f0; }
            50% { background-color: #f2f5f8; }
            100% { background-color: #e2e8f0; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Modern Pagination Styles */
        .modern-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
            padding: 16px 0;
        }

        .pagination-arrow {
            background: var(--bg-primary);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            color: var(--text-muted);
        }

        .pagination-arrow:hover:not(:disabled) {
            background: var(--bg-hover);
            border-color: var(--border-hover);
            color: var(--text-secondary);
            transform: translateY(-1px);
        }

        .pagination-arrow:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination-numbers {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-text {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-muted);
            padding: 0 8px;
        }

        .pagination-btn {
            background: var(--bg-primary);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-secondary);
            transition: all 0.2s ease;
        }

        .pagination-btn:hover:not(:disabled) {
            background: var(--bg-hover);
            border-color: var(--border-hover);
            transform: translateY(-1px);
        }

        .pagination-btn.active {
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            border-color: var(--brand-primary);
            color: white;
            cursor: default;
            transform: none;
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-info {
            font-size: 12px;
            color: var(--text-muted);
            text-align: center;
            margin-top: 8px;
        }
        .comments-section {
            margin-top: 10px;
            padding: 16px 24px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .comments-header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .comments-header h4 {
            margin: 0;
            font-weight: 600;
            color: #111827;
        }

        .comments-list {
            max-height: 180px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .comment {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            background: #f9fafb;
            padding: 12px 16px;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            font-size: 0.875rem;
        }

        .comment strong {
            color: #111827;
            font-weight: 600;
        }

        .comment .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #d1d5db;
            flex-shrink: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
            color: #6b7280;
            user-select: none;
        }

        .comment-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .comment-text {
            color: #374151;
        }

        .comment-timestamp {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .comment-form {
            display: flex;
            gap: 12px;
            align-items: center;
            margin: 16px 0;
        }

        .comment-input-container {
            flex: 1;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .comment-form input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 9999px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .comment-form input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        .comment-submit-btn {
            background: linear-gradient(90deg, #3b82f6, #2563eb);
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 9999px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            white-space: nowrap;
        }

        .comment-submit-btn:hover {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
        }
        .pagination-ellipsis {
            padding: 0 5px;
            color: #495057;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
            .gallery-item img,
            .gallery-item video {
                height: 120px;
            }
            .modal-content, .modal-content-viewer {
                width: 95%;
                margin: 2vh auto;
                max-height: 96vh;
            }
            .modal-header {
                padding: 16px 20px 12px;
            }
            .modal-title {
                font-size: 16px;
            }
            #mediaContainer {
                padding: 16px;
                min-height: 250px;
                max-height: 60vh;
            }
            .comments-section {
                padding: 12px 16px;
            }
            .comments-list {
                max-height: 150px;
            }
            .comment {
                padding: 10px 12px;
                font-size: 0.8rem;
            }
            .comment .avatar {
                width: 32px;
                height: 32px;
            }
        }

        @media (max-width: 480px) {
            .gallery-grid {
                grid-template-columns: 1fr;
            }
            .header h1 {
                font-size: 1.5rem;
            }
            .action-buttons button {
                padding: 8px 12px;
            }
            .modal-content-viewer {
                width: 98%;
                margin: 1vh auto;
            }
            .modal-header {
                padding: 12px 16px 8px;
                flex-wrap: wrap;
                gap: 8px;
            }
            .modal-title {
                font-size: 14px;
                order: 1;
                width: 100%;
            }
            .modal-actions {
                order: 2;
                gap: 4px;
            }
            .action-btn {
                padding: 6px;
            }
            #mediaContainer {
                padding: 12px;
                min-height: 200px;
                max-height: 50vh;
            }
            .comments-section {
                padding: 8px 12px;
            }
            .comments-list {
                max-height: 120px;
                gap: 8px;
            }
            .comment {
                padding: 8px 10px;
                gap: 8px;
            }
            .comment .avatar {
                width: 28px;
                height: 28px;
            }
            .comment-form {
                margin: 12px 0;
            }
            .comment-form input {
                padding: 10px 14px;
                font-size: 0.85rem;
            }
            .comment-submit-btn {
                padding: 10px 16px;
                font-size: 0.85rem;
            }
        }

        /* Modern Folder List Styles */
        .modern-folder-item {
            display: flex;
            align-items: center;
            height: 52px;
            margin-bottom: 8px;
            background: var(--bg-primary);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .modern-folder-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-secondary) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 0 4px 4px 0;
        }

        .modern-folder-item:hover {
            background: var(--bg-hover);
            border-color: var(--border-hover);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .modern-folder-item.selected {
            background: var(--bg-selected);
            border-color: var(--brand-primary);
        }

        .modern-folder-item.selected::before {
            opacity: 1;
        }

        .folder-row {
            display: flex;
            align-items: center;
            flex: 1;
            padding: 0 80px 0 16px;
            height: 100%;
            cursor: pointer;
        }

        .folder-icon {
            margin-right: 12px;
            color: var(--brand-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        .folder-name {
            flex: 1;
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            letter-spacing: -0.025em;
        }


        .folder-actions {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 4px;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .modern-folder-item:hover .folder-actions {
            opacity: 1;
        }

        .folder-action-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
        }

        .folder-action-btn:hover {
            background: rgba(0, 0, 0, 0.05);
            transform: scale(1.1);
        }

        .folder-action-btn:active {
            transform: scale(0.95);
        }

        .edit-btn:hover {
            color: var(--success);
            background: rgba(5, 150, 105, 0.1);
        }

        .delete-btn:hover {
            color: var(--error);
            background: rgba(239, 68, 68, 0.1);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 32px 16px;
            color: var(--text-light);
            font-size: 14px;
            font-weight: 500;
        }

        /* Enhanced search input styling */
        .search-input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            margin-bottom: 16px;
            box-sizing: border-box;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            background: #ffffff;
            transition: all 0.3s ease;
            position: relative;
        }

        .search-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            transform: translateY(-1px);
        }

        .search-input::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        /* Mobile adjustments for folder items */
        @media (max-width: 768px) {
            .modern-folder-item {
                padding: 16px 18px;
                margin-bottom: 14px;
            }
        
            .folder-actions {
                opacity: 1; /* Always show on mobile */
                right: 18px;
            }
        
            .folder-row {
                padding: 0 70px 0 16px;
            }

            .folder-icon {
                width: 36px;
                height: 36px;
                margin-right: 12px;
            }

            .folder-name {
                font-size: 14px;
            }

            .search-input {
                padding: 12px 16px;
                font-size: 13px;
            }
        }

        /* Animation for folder items */
        @keyframes folderSlideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .modern-folder-item {
            animation: folderSlideIn 0.4s ease-out both;
        }

        .modern-folder-item:nth-child(1) { animation-delay: 0.1s; }
        .modern-folder-item:nth-child(2) { animation-delay: 0.15s; }
        .modern-folder-item:nth-child(3) { animation-delay: 0.2s; }
        .modern-folder-item:nth-child(4) { animation-delay: 0.25s; }
        .modern-folder-item:nth-child(5) { animation-delay: 0.3s; }

        /* Modern Edit Modal Styles */
        .edit-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            opacity: 0;
            transition: opacity 0.3s ease, backdrop-filter 0.3s ease;
        }

        .edit-modal-overlay.active {
            opacity: 1;
        }

        .edit-modal-container {
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease-out;
        }

        .edit-modal-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .edit-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 28px 20px;
            border-bottom: 1px solid #f1f5f9;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        }

        .edit-modal-title {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            letter-spacing: -0.025em;
        }

        .edit-modal-close {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            color: #64748b;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .edit-modal-close:hover {
            background-color: #f1f5f9;
            color: #334155;
            transform: scale(1.05);
        }

        .edit-modal-body {
            padding: 28px;
        }

        .edit-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .edit-form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .edit-form-label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 4px;
        }

        .edit-form-input,
        .edit-form-textarea,
        .edit-form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            font-family: inherit;
            background-color: #ffffff;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .edit-form-input:focus,
        .edit-form-textarea:focus,
        .edit-form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: translateY(-1px);
        }

        .edit-form-input::placeholder,
        .edit-form-textarea::placeholder {
            color: #9ca3af;
            opacity: 1;
        }

        .edit-form-textarea {
            resize: vertical;
            min-height: 80px;
            line-height: 1.5;
        }

        .edit-form-select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
            appearance: none;
        }

        .edit-modal-footer {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            padding: 20px 28px 24px;
            border-top: 1px solid #f1f5f9;
            background: #f8fafc;
        }

        .edit-btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 100px;
        }

        .edit-btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .edit-btn-primary:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.5);
        }

        .edit-btn-primary:active {
            transform: translateY(0);
        }

        .edit-btn-secondary {
            background: #ffffff;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .edit-btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #475569;
            transform: translateY(-1px);
        }

        /* Modal Animations */
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* Responsive Design for Edit Modal */
        @media (max-width: 640px) {
            .edit-modal-container {
                width: 95%;
                max-height: 95vh;
            }

            .edit-modal-header {
                padding: 20px 20px 16px;
            }

            .edit-modal-title {
                font-size: 18px;
            }

            .edit-modal-body {
                padding: 20px;
            }

            .edit-modal-footer {
                padding: 16px 20px 20px;
                flex-direction: column-reverse;
            }

            .edit-btn {
                width: 100%;
                min-width: unset;
            }

            .edit-form-input,
            .edit-form-textarea,
            .edit-form-select {
                font-size: 16px; /* Prevents zoom on iOS */
            }
        }

        @media (max-width: 480px) {
            .edit-modal-header {
                padding: 16px 16px 12px;
            }

            .edit-modal-title {
                font-size: 16px;
            }

            .edit-modal-body {
                padding: 16px;
            }

            .edit-modal-footer {
                padding: 12px 16px 16px;
            }

            .edit-form {
                gap: 20px;
            }

            .edit-form-group {
                gap: 6px;
            }

            .edit-form-label {
                font-size: 13px;
            }

            .edit-form-input,
            .edit-form-textarea,
            .edit-form-select {
                padding: 10px 14px;
                font-size: 16px;
            }
        }

        /* Focus and Accessibility Improvements */
        .edit-modal-overlay:focus-within .edit-modal-card {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
        }

        .edit-btn:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        /* Loading state for form submission */
        .edit-form.submitting {
            opacity: 0.7;
            pointer-events: none;
        }

        .edit-form.submitting .edit-btn-primary::after {
            content: '';
            width: 16px;
            height: 16px;
            margin-left: 8px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetchAssets();
        });

        let currentAssetPage = 1;
        let currentFolderId = null;

        async function fetchAssets(folderId = null, page = 1) {
            document.getElementById('loading-skeleton').style.display = 'grid';
            document.getElementById('gallery-container').style.display = 'none';

            currentFolderId = folderId;
            currentAssetPage = page;

            // Determine per_page based on screen width - more robust detection
            const isMobile = window.innerWidth < 768 || window.matchMedia('(max-width: 767px)').matches;
            const perPage = isMobile ? 5 : 10;

            let url = '{{ route("assets.list") }}';
            if (folderId) {
                url = `/assets/folder/${folderId}?page=${page}&per_page=${perPage}`;
            } else {
                url = `/assets?page=${page}&per_page=${perPage}`;
            }

            try {
                const response = await fetch(url);
                const data = await response.json();

                document.getElementById('loading-skeleton').style.display = 'none';
                document.getElementById('gallery-container').style.display = 'grid';
                renderAssets(data.data || data, data);
            } catch (error) {
                console.error('Error fetching assets:', error);
                document.getElementById('loading-skeleton').innerHTML = '<p>Gagal memuat galeri. Silakan coba lagi.</p>';
            }
        }

        function renderAssets(assets, paginationData = null) {
            const container = document.getElementById('gallery-container');
            container.innerHTML = '';

            if (assets.length === 0) {
                container.innerHTML = '<p>Belum ada file yang diunggah.</p>';
                document.getElementById('assets-pagination').style.display = 'none';
                return;
            }

            assets.forEach((asset, index) => {
                let mediaHtml = '';
                let thumbnailSrc = asset.thumbnail_url || (asset.file_type && asset.file_type.startsWith('image/') ? asset.blob_url : '/video-thumbnail.png');
                if (asset.file_type.startsWith('image/')) {
                    mediaHtml = `<img src="${thumbnailSrc}" loading="lazy" alt="${asset.title || asset.original_filename}">`;
                } else if (asset.file_type.startsWith('video/')) {
                    mediaHtml = `
                        <div class="video-thumbnail-container" data-asset-url="${asset.blob_url}" data-asset-id="${asset.id}" onclick="showAssetViewer('${asset.blob_url}', '${asset.file_type}', '${asset.title || asset.original_filename}', '${asset.id}')">
                            <img src="${thumbnailSrc}" loading="lazy" alt="${asset.title || asset.original_filename}" style="width: 100%; height: 100%; object-fit: cover;">
                            <div class="play-overlay">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="white" style="opacity: 0.9;">
                                    <polygon points="5,3 19,12 5,21 5,3"></polygon>
                                </svg>
                            </div>
                        </div>
                    `;
                } else {
                    mediaHtml = `<img src="/file-icon.png" alt="File">`;
                }



                const assetHtml = `
                    <div class="gallery-card" style="animation-delay: ${index * 0.1}s;">
                        <div class="card-image-container">
                            ${mediaHtml}
                            <div class="card-overlay">
                                <div class="overlay-actions">
                                    <button class="overlay-btn edit-btn" onclick="showEditModal('${asset.id}', '${asset.title || ''}', '${asset.caption || ''}', '${asset.folder_id || 'none'}')" title="Edit">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </button>
                                    <button class="overlay-btn delete-btn" onclick="deleteAsset('${asset.id}')" title="Delete">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3,6 5,6 21,6"></polyline>
                                            <path d="m19,6v14a2,2 0 0,1-2,2H7a2,2 0 0,1-2-2V6m3,0V4a2,2 0 0,1,2-2h4a2,2 0 0,1,2,2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                    <button class="overlay-btn share-btn" onclick="shareAsset('${asset.blob_url}', '${asset.title || asset.original_filename}')" title="Share">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="18" cy="5" r="3"></circle>
                                            <circle cx="6" cy="12" r="3"></circle>
                                            <circle cx="18" cy="19" r="3"></circle>
                                            <path d="m8.59 13.51 6.83 3.98"></path>
                                            <path d="m15.41 6.51-6.82 3.98"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-header">
                                <h3 class="card-title">${asset.title || asset.original_filename}</h3>
                                <button class="view-btn" onclick="showAssetViewer('${asset.blob_url}', '${asset.file_type}', '${asset.title || asset.original_filename}', '${asset.id}')">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    View
                                </button>
                            </div>
                            <div class="card-meta">
                                <span class="meta-item file-size">${asset.formatted_size}</span>
                                <button class="like-btn" data-asset-id="${asset.id}" data-is-liked="${asset.is_liked_by_user ? 'true' : 'false'}" aria-label="Like button">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="${asset.is_liked_by_user ? 'red' : 'none'}" stroke="currentColor" stroke-width="2" class="heart-icon">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                    <span class="like-count">${asset.likes_count}</span>
                                </button>
                            </div>
                            ${asset.caption ? `<p class="card-caption">${asset.caption}</p>` : ''}
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', assetHtml);
            });

            // Handle pagination
            if (paginationData && paginationData.last_page > 1) {
                renderAssetsPagination(paginationData);
            } else {
                document.getElementById('assets-pagination').style.display = 'none';
            }
        }

        function renderAssetsPagination(paginationData) {
            const paginationContainer = document.getElementById('assets-pagination');
            // const pageInfo = document.getElementById('assets-page-info');
            const pageNumbersContainer = document.getElementById('assets-page-numbers');
            const prevBtn = document.getElementById('assets-prev');
            const nextBtn = document.getElementById('assets-next');

            // pageInfo.textContent = `Page ${paginationData.current_page} of ${paginationData.last_page}`;
            prevBtn.disabled = paginationData.current_page <= 1;
            nextBtn.disabled = paginationData.current_page >= paginationData.last_page;

            prevBtn.onclick = () => fetchAssets(currentFolderId, paginationData.current_page - 1);
            nextBtn.onclick = () => fetchAssets(currentFolderId, paginationData.current_page + 1);

            // Clear previous page numbers
            pageNumbersContainer.innerHTML = '';

            const current = paginationData.current_page;
            const last = paginationData.last_page;

            // Add page numbers with ellipsis
            const delta = 2;
            const pages = [];
            
            // Always add first page
            pages.push(1);
            
            // Add ellipsis if needed
            if (current > delta + 2) {
                pages.push('...');
            }
            
            // Add pages around current
            for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
                pages.push(i);
            }
            
            // Add ellipsis if needed
            if (current < last - delta - 1) {
                pages.push('...');
            }
            
            // Always add last page if not already included
            if (last !== current && last > pages[pages.length - 1]) {
                pages.push(last);
            }
            
            // Remove duplicates (e.g., if current is 1 or last)
            const uniquePages = [...new Set(pages)];
            
            uniquePages.forEach(page => {
                if (page === '...') {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'pagination-ellipsis';
                    pageNumbersContainer.appendChild(ellipsis);
                } else {
                    const pageBtn = document.createElement('button');
                    pageBtn.textContent = page;
                    pageBtn.className = 'pagination-btn';
                    if (page === current) {
                        pageBtn.classList.add('active');
                        pageBtn.disabled = true;
                    }
                    pageBtn.onclick = () => fetchAssets(currentFolderId, page);
                    pageNumbersContainer.appendChild(pageBtn);
                }
            });

            paginationContainer.style.display = 'flex';
        }

        function showUploadModal() {
            window.scrollTo(0, 0);
            document.body.style.overflow = 'hidden';
            const modal = document.getElementById('uploadModal');
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
        }

        function hideUploadModal() {
            document.body.style.overflow = '';
            const modal = document.getElementById('uploadModal');
            modal.classList.remove('active');
            setTimeout(() => modal.style.display = 'none', 300);
        }

        function showCreateFolderModal() {
            window.scrollTo(0, 0);
            document.body.style.overflow = 'hidden';
            const modal = document.getElementById('createFolderModal');
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('active'), 10);
        }
        
        function hideCreateFolderModal() {
            document.body.style.overflow = '';
            const modal = document.getElementById('createFolderModal');
            modal.classList.remove('active');
            setTimeout(() => modal.style.display = 'none', 300);
        }

        function showAssetViewer(url, fileType, filename, assetId) {
            const mediaContainer = document.getElementById('mediaContainer');
            mediaContainer.innerHTML = '';

            document.getElementById('assetViewerTitle').innerText = filename;

            if (fileType.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = url;
                img.alt = filename;
                mediaContainer.appendChild(img);
            } else if (fileType.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = url;
                video.controls = true;
                video.autoplay = true;
                video.preload = 'metadata';
                mediaContainer.appendChild(video);
            }

            // Load comments for this asset
            loadComments(assetId);

            // Set asset ID on modal for comment submission
            document.getElementById('assetViewerModal').setAttribute('data-asset-id', assetId);
            document.getElementById('assetViewerModal').setAttribute('data-asset-url', url);
            document.getElementById('assetViewerModal').setAttribute('data-asset-filename', filename);

            // Set up download functionality
            const downloadBtn = document.getElementById('downloadBtn');
            downloadBtn.onclick = () => downloadAsset(url, filename);

            // Set up share functionality
            const shareBtn = document.getElementById('shareBtn');
            shareBtn.onclick = () => shareAsset(url, filename);

            window.scrollTo(0, 0);
            document.body.style.overflow = 'hidden';
            const viewerModal = document.getElementById('assetViewerModal');
            viewerModal.style.display = 'flex';
            setTimeout(() => viewerModal.classList.add('active'), 10);
        }

        function hideAssetViewer() {
            document.body.style.overflow = '';
            const mediaContainer = document.getElementById('mediaContainer');
            mediaContainer.innerHTML = '';
            // Clear comments
            document.getElementById('commentsList').innerHTML = '';
            const viewerModal = document.getElementById('assetViewerModal');
            viewerModal.classList.remove('active');
            setTimeout(() => viewerModal.style.display = 'none', 300);
        }

        // Helper function to analyze file types
        function analyzeFileTypes(files) {
            let photoCount = 0;
            let videoCount = 0;
            let otherCount = 0;

            for (const file of files) {
                if (file.type.startsWith('image/')) {
                    photoCount++;
                } else if (file.type.startsWith('video/')) {
                    videoCount++;
                } else {
                    otherCount++;
                }
            }

            return { photoCount, videoCount, otherCount, total: files.length };
        }

        // Function to show dynamic loading indicator based on file types
        function showDynamicLoadingIndicator(fileTypes) {
            const spinner = document.getElementById('uploadSpinner');
            const loadingText = document.getElementById('loadingText');
            const loadingDetails = document.getElementById('loadingDetails');

            // Reset classes
            spinner.className = 'spinner';

            if (fileTypes.videoCount > 0 && fileTypes.photoCount === 0) {
                // Only videos
                spinner.classList.add('video-upload');
                loadingText.textContent = 'Mengunggah video...';
                loadingDetails.textContent = `${fileTypes.videoCount} video${fileTypes.videoCount > 1 ? 's' : ''} sedang diproses`;
            } else if (fileTypes.photoCount > 0 && fileTypes.videoCount === 0) {
                // Only photos
                spinner.classList.add('photo-upload');
                loadingText.textContent = 'Mengunggah foto...';
                loadingDetails.textContent = `${fileTypes.photoCount} foto${fileTypes.photoCount > 1 ? 's' : ''} sedang diproses`;
            } else if (fileTypes.photoCount > 0 && fileTypes.videoCount > 0) {
                // Mixed files
                loadingText.textContent = 'Mengunggah media...';
                loadingDetails.textContent = `${fileTypes.photoCount} foto & ${fileTypes.videoCount} video sedang diproses`;
            } else {
                // Other files or fallback
                loadingText.textContent = 'Mengunggah file...';
                loadingDetails.textContent = `${fileTypes.total} file${fileTypes.total > 1 ? 's' : ''} sedang diproses`;
            }

            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        document.getElementById('uploadForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData();
            const fileInput = document.getElementById('fileInput');
            const titleInput = document.getElementById('title');
            const captionInput = document.getElementById('caption');

            if (fileInput.files.length === 0) {
                alert('Pilih setidaknya satu file.');
                return;
            }

            // Analyze file types for dynamic loading indicator
            const fileTypes = analyzeFileTypes(fileInput.files);
            showDynamicLoadingIndicator(fileTypes);

            for (const file of fileInput.files) {
                formData.append('files[]', file);
            }

            formData.append('title', titleInput.value);
            formData.append('caption', captionInput.value);
            formData.append('folder', document.getElementById('folder').value);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if(data.success) {
                    alert('File berhasil diunggah!');
                    hideUploadModal();
                    await fetchAssets(currentFolderId, 1); // Reset to page 1 after upload
                } else {
                    const errorMsg = data.errors ? 'Unggah gagal: ' + JSON.stringify(data.errors) : 'Unggah gagal: ' + (data.message || 'Terjadi kesalahan tidak dikenal.');
                    alert(errorMsg);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengunggah file.');
            } finally {
                document.getElementById('loadingOverlay').style.display = 'none';
            }
        });

        // Event listener untuk form buat folder
        document.getElementById('createFolderForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            document.getElementById('loadingOverlay').style.display = 'flex';
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch('{{ route("folder.create") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    alert('Folder berhasil dibuat!');
                    hideCreateFolderModal();
                    await fetchAssets(currentFolderId, 1); // Reset to page 1 after folder creation
                    await fetchFolders(1); // Reset folders to page 1
                } else {
                    alert('Gagal membuat folder: ' + JSON.stringify(data.errors));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat membuat folder.');
            } finally {
                document.getElementById('loadingOverlay').style.display = 'none';
            }
        });
        
        let allFolders = [];
        let currentFoldersPage = 1;

        async function fetchFolders(page = 1) {
            try {
                currentFoldersPage = page;
                const response = await fetch(`{{ route("folders.list") }}?page=${page}`);
                const data = await response.json();
                const folders = data.data || data;

                allFolders = folders; // Store current page folders
                const folderSelect = document.getElementById('folder');
                folderSelect.innerHTML = '<option value="none">Tidak ada folder</option>';

                folders.forEach(folder => {
                    // Update dropdown list
                    const option = document.createElement('option');
                    option.value = folder.id;
                    option.innerText = folder.name;
                    folderSelect.appendChild(option);
                });

                renderFolders(folders, data); // Render with pagination data
            } catch (error) {
                console.error('Error fetching folders:', error);
            }
        }

        function renderFolders(folders, paginationData = null) {
            const folderSidebar = document.getElementById('folders-list-container');
            folderSidebar.innerHTML = '';
        
            if (folders.length === 0) {
                folderSidebar.innerHTML = '<div class="empty-state">Kosong</div>';
                document.getElementById('folders-pagination').style.display = 'none';
                return;
            }
        
            folders.forEach((folder, index) => {
                const folderItem = document.createElement('div');
                folderItem.className = 'modern-folder-item';
                folderItem.setAttribute('role', 'listitem');
                folderItem.setAttribute('tabindex', '0');
                folderItem.style.animationDelay = `${index * 0.1}s`;
                folderItem.setAttribute('data-folder-id', folder.id);
                folderItem.setAttribute('data-folder-name', folder.name);
                folderItem.innerHTML = `
                    <div class="folder-row" role="button" tabindex="0" aria-label="Select folder ${folder.name}">
                        <div class="folder-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                            </svg>
                        </div>
                        <span class="folder-name">${folder.name}</span>
                    </div>
                    <div class="folder-actions">
                        <button class="folder-action-btn edit-btn folder-edit-btn" title="Edit folder" aria-label="Edit ${folder.name}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                        <button class="folder-action-btn delete-btn folder-delete-btn" title="Delete folder" aria-label="Delete ${folder.name}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <polyline points="3,6 5,6 21,6"></polyline>
                                <path d="m19,6v14a2,2 0 0,1-2,2H7a2,2 0 0,1-2-2V6m3,0V4a2,2 0 0,1,2-2h4a2,2 0 0,1,2,2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                        </button>
                    </div>
                `;
                folderSidebar.appendChild(folderItem);
            });
        
            // Handle pagination
            if (paginationData && paginationData.last_page > 1) {
                renderFoldersPagination(paginationData);
            } else {
                document.getElementById('folders-pagination').style.display = 'none';
            }
        
            // Attach event listeners after rendering
            attachFolderEventListeners();
        }

        function attachFolderEventListeners() {
            const folderSidebar = document.getElementById('folders-list-container');
        
            // Event delegation for folder row clicks and keydown
            folderSidebar.addEventListener('click', function(event) {
                if (event.target.closest('.folder-row')) {
                    const folderItem = event.target.closest('.modern-folder-item');
                    const folderId = folderItem.getAttribute('data-folder-id');
                    const folderName = folderItem.getAttribute('data-folder-name');
                    if (folderId && folderName) {
                        selectFolder(folderId, folderName, event);
                    }
                }
            });
        
            folderSidebar.addEventListener('keydown', function(event) {
                if (event.target.closest('.folder-row')) {
                    const folderItem = event.target.closest('.modern-folder-item');
                    const folderId = folderItem.getAttribute('data-folder-id');
                    const folderName = folderItem.getAttribute('data-folder-name');
                    if (folderId && folderName && (event.key === 'Enter' || event.key === ' ')) {
                        event.preventDefault();
                        selectFolder(folderId, folderName, event);
                    }
                }
            });
        
            // Event delegation for edit buttons
            folderSidebar.addEventListener('click', function(event) {
                if (event.target.closest('.folder-edit-btn')) {
                    event.stopPropagation();
                    const folderItem = event.target.closest('.modern-folder-item');
                    const folderId = folderItem.getAttribute('data-folder-id');
                    const folderName = folderItem.getAttribute('data-folder-name');
                    if (folderId && folderName) {
                        showEditFolderModal(folderId, folderName);
                    }
                }
            });
        
            // Event delegation for delete buttons
            folderSidebar.addEventListener('click', function(event) {
                if (event.target.closest('.folder-delete-btn')) {
                    event.stopPropagation();
                    const folderItem = event.target.closest('.modern-folder-item');
                    const folderId = folderItem.getAttribute('data-folder-id');
                    const folderName = folderItem.getAttribute('data-folder-name');
                    if (folderId && folderName) {
                        deleteFolder(folderId, folderName);
                    }
                }
            });
        
            // Keydown for action buttons
            folderSidebar.addEventListener('keydown', function(event) {
                if (event.target.closest('.folder-action-btn') && (event.key === 'Enter' || event.key === ' ')) {
                    event.preventDefault();
                    event.target.click();
                }
            });
        }
        
        function selectFolder(folderId, folderName, event) {
            document.getElementById('current-view-title').innerText = folderName;
            fetchAssets(folderId, 1); // Reset to page 1 when switching folders
        
            // Update selected state
            document.querySelectorAll('.modern-folder-item').forEach(item => {
                item.classList.remove('selected');
            });
            if (event) {
                event.currentTarget.closest('.modern-folder-item').classList.add('selected');
            }
        }

        function renderFoldersPagination(paginationData) {
            const paginationContainer = document.getElementById('folders-pagination');
            const pageNumbersContainer = document.getElementById('folders-page-numbers');
            const prevBtn = document.getElementById('folders-prev');
            const nextBtn = document.getElementById('folders-next');

            // Update page info text
            const pageInfo = document.getElementById('folders-page-info');
            if (pageInfo) {
                pageInfo.textContent = `Halaman ${paginationData.current_page} dari ${paginationData.last_page}`;
            }

            prevBtn.disabled = paginationData.current_page <= 1;
            nextBtn.disabled = paginationData.current_page >= paginationData.last_page;

            prevBtn.onclick = () => fetchFolders(paginationData.current_page - 1);
            nextBtn.onclick = () => fetchFolders(paginationData.current_page + 1);

            // Clear previous page numbers
            pageNumbersContainer.innerHTML = '';

            const current = paginationData.current_page;
            const last = paginationData.last_page;


            // Add page numbers with ellipsis
            const delta = 2;
            const pages = [];
            
            // Always add first page
            pages.push(1);
            
            // Add ellipsis if needed
            if (current > delta + 2) {
                pages.push('...');
            }
            
            // Add pages around current
            for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
                pages.push(i);
            }
            
            // Add ellipsis if needed
            if (current < last - delta - 1) {
                pages.push('...');
            }
            
            // Always add last page if not already included
            if (last !== current && last > pages[pages.length - 1]) {
                pages.push(last);
            }
            
            // Remove duplicates (e.g., if current is 1 or last)
            const uniquePages = [...new Set(pages)];
            
            uniquePages.forEach(page => {
                if (page === '...') {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'pagination-ellipsis';
                    pageNumbersContainer.appendChild(ellipsis);
                } else {
                    const pageBtn = document.createElement('button');
                    pageBtn.textContent = page;
                    pageBtn.className = 'pagination-btn';
                    if (page === current) {
                        pageBtn.classList.add('active');
                        pageBtn.disabled = true;
                    }
                    pageBtn.onclick = () => fetchFolders(page);
                    pageNumbersContainer.appendChild(pageBtn);
                }
            });


            paginationContainer.style.display = 'flex';
        }
        
        // Memuat semua aset dan folder saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            // Ensure mobile detection works from the start
            setTimeout(() => {
                fetchAssets(null, 1);
                fetchFolders(1);
            }, 100);

            // Add live search for folders
            const searchInput = document.getElementById('folder-search');
            const clearSearchBtn = document.getElementById('clear-search');

            searchInput.addEventListener('input', () => {
                const query = searchInput.value.toLowerCase();
                const filteredFolders = allFolders.filter(folder => folder.name.toLowerCase().includes(query));
                renderFolders(filteredFolders);
            });

            // Clear search functionality
            clearSearchBtn.addEventListener('click', () => {
                searchInput.value = '';
                searchInput.focus();
                renderFolders(allFolders);
            });

            // Add auto-close functionality for mobile menu
            setTimeout(() => {
                addMenuAutoClose();
            }, 500);
        });

        function addMenuAutoClose() {
            // Add click handlers to sidebar menu links for auto-close on mobile
            const sidebarLinks = document.querySelectorAll('.sidebar-menu a, .sidebar-menu li a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    // Keep sidebar open on mobile for better UX
                    // Only close if explicitly clicked outside or on close button
                });
            });
        }

        // Perbarui URL fetch di fungsi fetchAssets
        // ... (fungsi lainnya)

        function toggleDropdown(id) {
            const dropdown = document.getElementById(`dropdown-${id}`);
            const isVisible = dropdown.style.display === 'block';
            // Hide all dropdowns first
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.style.display = 'none');
            // Toggle the clicked one
            dropdown.style.display = isVisible ? 'none' : 'block';
        }

        // Handle like/unlike functionality
        document.addEventListener('click', function(event) {
            if (event.target.closest('.like-btn')) {
                const button = event.target.closest('.like-btn');
                const assetId = button.getAttribute('data-asset-id');
                const likeIcon = button.querySelector('.heart-icon');
                const likeCount = button.querySelector('.like-count');

                const isLiked = button.getAttribute('data-is-liked') === 'true';
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
                        button.setAttribute('data-is-liked', newLiked.toString());
                        likeIcon.setAttribute('fill', newLiked ? 'red' : 'none');
                        likeCount.textContent = data.likes_count || 0;
                    } else {
                        alert('Error: ' + (data.error || data.message || 'Something went wrong'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memproses like.');
                });
            }
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.style.display = 'none');
            }
        });

        function showEditModal(id, title, caption, folderId) {
            const modalContent = `
                <div class="edit-modal-overlay" id="editModalOverlay">
                    <div class="edit-modal-container">
                        <div class="edit-modal-card">
                            <div class="edit-modal-header">
                                <h2 class="edit-modal-title">Edit Media</h2>
                                <button class="edit-modal-close" onclick="hideEditModal()" aria-label="Close modal">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>
                            <div class="edit-modal-body">
                                <form id="editAssetForm" class="edit-form">
                                    <input type="hidden" name="id" value="${id}">
                                    <div class="edit-form-group">
                                        <label for="editTitle" class="edit-form-label">Judul</label>
                                        <input type="text" id="editTitle" name="title" value="${title}" class="edit-form-input" placeholder="Masukkan judul media">
                                    </div>
                                    <div class="edit-form-group">
                                        <label for="editCaption" class="edit-form-label">Keterangan</label>
                                        <textarea id="editCaption" name="caption" class="edit-form-textarea" placeholder="Tambahkan keterangan untuk media Anda..." rows="3">${caption}</textarea>
                                    </div>
                                    <div class="edit-form-group">
                                        <label for="editFolder" class="edit-form-label">Folder</label>
                                        <select id="editFolder" name="folder_id" class="edit-form-select">
                                            <option value="none">Tidak ada folder</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="edit-modal-footer">
                                <button type="button" class="edit-btn edit-btn-secondary" onclick="hideEditModal()">Batal</button>
                                <button type="submit" form="editAssetForm" class="edit-btn edit-btn-primary">Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            const modal = document.createElement('div');
            modal.id = 'editModal';
            modal.innerHTML = modalContent;
            document.body.appendChild(modal);

            window.scrollTo(0, 0);
            document.body.style.overflow = 'hidden';
            // Add fade-in animation
            setTimeout(() => {
                const overlay = document.getElementById('editModalOverlay');
                if (overlay) {
                    overlay.classList.add('active');
                }
            }, 10);

            // Populate folder options
            const editFolderSelect = document.getElementById('editFolder');
            allFolders.forEach(folder => {
                const option = document.createElement('option');
                option.value = folder.id;
                option.textContent = folder.name;
                if (folder.id === folderId) {
                    option.selected = true;
                }
                editFolderSelect.appendChild(option);
            });

            modal.style.display = 'block';

            document.getElementById('editAssetForm').addEventListener('submit', async function(event) {
                event.preventDefault();

                const formData = new FormData(event.target);
                const assetId = formData.get('id');
                const title = formData.get('title');
                const caption = formData.get('caption');
                let folder_id = formData.get('folder_id');
                if (folder_id === 'none') {
                    folder_id = null;
                }

                try {
                    const response = await fetch(`/assets/${assetId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({ title, caption, folder_id })
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert('Media berhasil diperbarui!');
                        hideEditModal();
                        await fetchAssets(currentFolderId, currentAssetPage); // Stay on current page after edit
                    } else {
                        alert('Gagal memperbarui media: ' + JSON.stringify(data.errors));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui media.');
                }
            });
        }

        function hideEditModal() {
            document.body.style.overflow = '';
            const modal = document.getElementById('editModal');
            if (modal) {
                modal.remove();
            }
        }

        async function deleteAsset(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus media ini?')) {
                return;
            }

            const loadingOverlay = document.getElementById('loadingOverlay');
            loadingOverlay.style.display = 'flex';

            try {
                const response = await fetch(`/assets/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('Media berhasil dihapus!');
                    await fetchAssets(currentFolderId, currentAssetPage); // Stay on current page after delete
                } else {
                    alert('Gagal menghapus media: ' + JSON.stringify(data.errors));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus media.');
            } finally {
                loadingOverlay.style.display = 'none';
            }
        }

        function showEditFolderModal(id, name) {
            const modalContent = `
                <div class="edit-modal-overlay" id="editFolderOverlay">
                    <div class="edit-modal-container">
                        <div class="edit-modal-card">
                            <div class="edit-modal-header">
                                <h2 class="edit-modal-title">Edit Folder</h2>
                                <button class="edit-modal-close" onclick="hideEditFolderModal()" aria-label="Close modal">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>
                            <div class="edit-modal-body">
                                <form id="editFolderForm" class="edit-form">
                                    <input type="hidden" name="id" value="${id}">
                                    <div class="edit-form-group">
                                        <label for="editFolderName" class="edit-form-label">Nama Folder</label>
                                        <input type="text" id="editFolderName" name="name" value="${name}" required class="edit-form-input">
                                    </div>
                                </form>
                            </div>
                            <div class="edit-modal-footer">
                                <button type="button" class="edit-btn edit-btn-secondary" onclick="hideEditFolderModal()">Batal</button>
                                <button type="submit" form="editFolderForm" class="edit-btn edit-btn-primary">Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            const modal = document.createElement('div');
            modal.id = 'editFolderModal';
            modal.innerHTML = modalContent;
            document.body.appendChild(modal);

            window.scrollTo(0, 0);
            document.body.style.overflow = 'hidden';
            // Add fade-in animation
            setTimeout(() => {
                const overlay = document.getElementById('editFolderOverlay');
                if (overlay) {
                    overlay.classList.add('active');
                }
            }, 10);

            modal.style.display = 'block';

            document.getElementById('editFolderForm').addEventListener('submit', async function(event) {
                event.preventDefault();

                const formData = new FormData(event.target);
                const folderId = formData.get('id');
                const name = formData.get('name');

                try {
                    const response = await fetch(`/folders/${folderId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({ name })
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert('Folder berhasil diperbarui!');
                        hideEditFolderModal();
                        await fetchFolders();
                    } else {
                        alert('Gagal memperbarui folder: ' + JSON.stringify(data.errors));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui folder.');
                }
            });
        }

        function hideEditFolderModal() {
            document.body.style.overflow = '';
            const modal = document.getElementById('editFolderModal');
            if (modal) {
                modal.remove();
            }
        }

        async function deleteFolder(id, name) {
            if (!confirm(`Apakah Anda yakin ingin menghapus folder "${name}"?`)) {
                return;
            }

            const loadingOverlay = document.getElementById('loadingOverlay');
            loadingOverlay.style.display = 'flex';

            try {
                const response = await fetch(`/folders/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('Folder berhasil dihapus!');
                    await fetchFolders();
                    // If the current view is the deleted folder, switch to "All Media"
                    if (document.getElementById('current-view-title').innerText === name) {
                        document.getElementById('current-view-title').innerText = 'All Media';
                        await fetchAssets(null, 1); // Switch to all media, page 1
                    }
                } else {
                    alert('Gagal menghapus folder: ' + JSON.stringify(data.errors));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus folder.');
            } finally {
                loadingOverlay.style.display = 'none';
            }
        }

        // Comments functionality
        async function loadComments(assetId) {
            try {
                const response = await fetch(`/assets/${assetId}/comments`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });
                const comments = await response.json();
                renderComments(assetId, comments);
            } catch (error) {
                console.error('Error loading comments:', error);
            }
        }

        function renderComments(assetId, comments) {
            const commentsList = document.getElementById('commentsList');
            commentsList.innerHTML = '';

            comments.forEach(comment => {
                const userInitials = comment.user.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
                const avatarUrl = comment.user.photo ? `/storage/${comment.user.photo}` : null;

                const commentHtml = `
                    <div class="comment">
                        <div class="avatar" style="background-image: ${avatarUrl ? `url('${avatarUrl}')` : 'none'}; background-size: cover; background-position: center;">
                            ${!avatarUrl ? userInitials : ''}
                        </div>
                        <div class="comment-content">
                            <div class="comment-text">
                                <strong>${comment.user.name}:</strong> ${comment.comment}
                            </div>
                            <div class="comment-timestamp">${new Date(comment.created_at).toLocaleString()}</div>
                        </div>
                    </div>
                `;
                commentsList.insertAdjacentHTML('beforeend', commentHtml);
            });
        }

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

        // Handle comment form submission
        document.addEventListener('submit', async function(event) {
            if (event.target.id === 'commentForm') {
                event.preventDefault();
                const form = event.target;
                const input = form.querySelector('input');
                const commentText = input.value.trim();

                if (!commentText) return;

                // Get asset ID from the current asset viewer
                const assetId = document.getElementById('assetViewerModal').getAttribute('data-asset-id');

                try {
                    const response = await fetch(`/assets/${assetId}/comments`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ comment: commentText })
                    });

                    const data = await response.json();

                    if (data.success) {
                        input.value = '';
                        loadComments(assetId); // Reload comments
                    } else {
                        alert('Gagal menambahkan komentar.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambahkan komentar.');
                }
            }
        });
    </script>
@endsection
