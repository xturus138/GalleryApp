@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="header">
        <h1><span id="current-view-title">All Media</span></h1>
        <div class="storage-info">
            <p>Penyimpanan: {{ $totalStorageFormatted }} / {{ $totalStorageLimit }} ({{ $percentageUsed }}%)</p>
        </div>
        <div class="action-buttons">
            <button onclick="showUploadModal()">Unggah</button>
            <button onclick="window.location.href='{{ route('profile.show') }}'">Profile</button>
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

    <!-- Pagination for Assets -->
    <div id="assets-pagination" class="pagination-container" style="display: none;">
        <button id="assets-prev" class="pagination-btn"><</button>
        <div id="assets-page-numbers" class="page-numbers"></div>
        <button id="assets-next" class="pagination-btn">></button>
    </div>

    <!-- Pagination for Folders -->
    <div id="folders-pagination" class="pagination-container" style="display: none;">
        <button id="folders-prev" class="pagination-btn"><</button>
        <div id="folders-page-numbers" class="page-numbers"></div>
        <button id="folders-next" class="pagination-btn">></button>
    </div>

    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="hideUploadModal()">&times;</span>
            <h2>Unggah File</h2>
            <form id="uploadForm" action="{{ route('asset.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="fileInput">Pilih File</label>
                    <input type="file" name="files[]" id="fileInput" multiple required>
                </div>
                <div class="form-group">
                    <label for="title">Judul</label>
                    <input type="text" name="title" id="title" placeholder="Tambahkan judul untuk file Anda">
                </div>
                <div class="form-group">
                    <label for="caption">Keterangan (Opsional)</label>
                    <textarea name="caption" id="caption" placeholder="Tambahkan keterangan untuk foto atau video Anda..."></textarea>
                </div>
                <div class="form-group">
                    <label for="folder">Pilih Folder (Opsional)</label>
                    <select name="folder" id="folder">
                        <option value="none">Tidak ada folder</option>
                        </select>
                </div>
                <button type="submit">Unggah File</button>
            </form>
        </div>
    </div>

    <div id="assetViewerModal" class="modal">
        <div class="modal-content-viewer" id="assetContent">
            <span class="close-button" onclick="hideAssetViewer()">&times;</span>
            <div id="assetViewerTitle" style="font-weight: bold; margin-bottom: 10px; color: black;"></div>
            <div id="mediaContainer"></div>
            <div id="assetViewerComments" class="comments-section" style="margin-top: 20px;">
                <h4>Komentar</h4>
                <div id="commentsList" class="comments-list"></div>
                <form id="commentForm" class="comment-form">
                    <input type="text" placeholder="Tambahkan komentar..." required maxlength="1000">
                    <button type="submit">Kirim</button>
                </form>
            </div>
        </div>
    </div>

    <div id="createFolderModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="hideCreateFolderModal()">&times;</span>
            <h2>Buat Folder Baru</h2>
            <form id="createFolderForm" action="{{ route('folder.create') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="folderName">Nama Folder</label>
                    <input type="text" name="name" id="folderName" required>
                </div>
                <button type="submit">Buat Folder</button>
            </form>
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
        .gallery-item-info .info-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .gallery-item-info .truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 70%;
        }
        .view-button {
            padding: 5px 10px;
            background-color: #4a5568;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
        }
        .view-button:hover {
            background-color: #6366f1;
        }
        .like-button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .like-button:hover .like-icon {
            transform: scale(1.2);
        }
        .like-icon {
            transition: transform 0.2s, color 0.2s;
        }
        .like-count {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .dropdown-toggle {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
        }
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1000;
            min-width: 100px;
        }
        .dropdown-menu button {
            display: block;
            width: 100%;
            padding: 8px 12px;
            background: none;
            border: none;
            text-align: left;
            cursor: pointer;
            font-size: 0.8rem;
        }
        .dropdown-menu button:hover {
            background-color: #f8f9fa;
        }
        .gallery-item {
            position: relative;
            animation: fadeInUp 0.6s ease-out both;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .dropdown {
            position: absolute;
            top: 5px;
            right: 5px;
            z-index: 10;
        }
        .gallery-item img,
        .gallery-item video {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }
        .asset-details {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
            line-height: 1.4;
        }
        .asset-details strong {
            color: #333;
        }
        .caption {
            font-style: italic;
            color: #6c757d;
            font-size: 0.8rem;
            margin-top: 5px;
        }
        /* Style untuk modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.7);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            position: relative;
        }
        .modal-content form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .modal-content label {
            font-weight: bold;
            display: block;
            margin-bottom: 0.5rem;
        }
        .modal-content input,
        .modal-content textarea,
        .modal-content select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-sizing: border-box;
        }
        .modal-content textarea {
            min-height: 80px;
            resize: vertical;
        }
        .modal-content button {
            margin-top: 1rem;
            width: 100%;
            padding: 10px;
            background-color: #4a5568;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .modal-content button:hover {
            background-color: #6366f1;
        }

        .modal-content-viewer {
            background-color: #ffffff;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 1000px;
            position: relative;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            max-height: 95vh;
            overflow-y: auto;
        }
        #mediaContainer {
            width: 100%;
            height: auto;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 200px;
        }
        #mediaContainer img,
        #mediaContainer video {
            max-width: 100%;
            max-height: 70vh;
            object-fit: contain;
            display: block;
            border-radius: 4px;
        }
        .close-button {
            position: absolute;
            top: 10px;
            right: 20px;
            color: #333;
            font-size: 30px;
            font-weight: bold;
            transition: 0.3s;
            cursor: pointer;
        }
        .close-button:hover,
        .close-button:focus {
            color: #aaa;
            text-decoration: none;
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

        /* Pagination Styles */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            padding: 10px;
        }
        .pagination-btn {
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s;
            min-width: 40px;
        }
        .pagination-btn:hover:not(:disabled) {
            background-color: #0056b3;
        }
        .pagination-btn:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }
        .page-numbers {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        .comments-section {
            margin-top: 10px;
        }
        .comments-list {
            max-height: 150px;
            overflow-y: auto;
            margin-bottom: 10px;
        }
        .comment {
            background-color: #f8f9fa;
            padding: 5px 10px;
            margin-bottom: 5px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        .comment strong {
            color: #495057;
        }
        .comment-form {
            display: flex;
            gap: 5px;
        }
        .comment-form input {
            flex: 1;
            padding: 5px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        .comment-form button {
            padding: 5px 10px;
            background-color: #4a5568;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.8rem;
        }
        .comment-form button:hover {
            background-color: #6366f1;
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
                margin: 10% auto;
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

            // Determine per_page based on screen width
            const perPage = window.innerWidth < 768 ? 5 : 21;

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
                if (asset.file_type.startsWith('image/')) {
                    mediaHtml = `<img src="${asset.blob_url}" alt="${asset.title || asset.original_filename}">`;
                } else if (asset.file_type.startsWith('video/')) {
                    mediaHtml = `<video poster="/video-thumbnail.png" src="${asset.blob_url}" controls></video>`;
                } else {
                    mediaHtml = `<img src="/file-icon.png" alt="File">`;
                }



                const assetHtml = `
                    <div class="gallery-item" style="animation-delay: ${index * 0.1}s;">
                        <div class="dropdown">
                            <button class="dropdown-toggle" onclick="toggleDropdown('${asset.id}')">&#8230;</button>
                            <div class="dropdown-menu" id="dropdown-${asset.id}" style="display: none;">
                                <button onclick="showEditModal('${asset.id}', '${asset.title || ''}', '${asset.caption || ''}', '${asset.folder_id || 'none'}')">Edit</button>
                                <button onclick="deleteAsset('${asset.id}')">Hapus</button>
                            </div>
                        </div>
                        ${mediaHtml}
                        <div class="gallery-item-info">
                            <div class="info-top">
                                <h4 class="truncate">${asset.title || asset.original_filename}</h4>
                                <button class="view-button" onclick="showAssetViewer('${asset.blob_url}', '${asset.file_type}', '${asset.title || asset.original_filename}', '${asset.id}')">Lihat</button>
                            </div>
                            <div class="asset-details">
                                <p><strong>Ukuran:</strong> ${asset.formatted_size}</p>
                                ${asset.caption ? `<p><strong>Keterangan:</strong> ${asset.caption}</p>` : ''}
                                <p>
                                    <button class="like-button" data-asset-id="${asset.id}" aria-label="Like button">
                                        <span class="like-icon" style="color: ${asset.is_liked_by_user ? 'red' : 'gray'};">&#10084;</span>
                                        <span class="like-count">${asset.likes_count}</span>
                                    </button>
                                </p>

                            </div>
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

            // Add page numbers
            const pages = [];
            for (let i = 1; i <= last; i++) pages.push(i);

            pages.forEach(page => {
                const pageBtn = document.createElement('button');
                pageBtn.textContent = page;
                pageBtn.className = 'pagination-btn';
                if (page === current) {
                    pageBtn.disabled = true;
                }
                pageBtn.onclick = () => fetchAssets(currentFolderId, page);
                pageNumbersContainer.appendChild(pageBtn);
            });

            paginationContainer.style.display = 'flex';
        }

        function showUploadModal() {
            document.getElementById('uploadModal').style.display = 'block';
        }

        function hideUploadModal() {
            document.getElementById('uploadModal').style.display = 'none';
        }

        function showCreateFolderModal() {
            document.getElementById('createFolderModal').style.display = 'block';
        }
        
        function hideCreateFolderModal() {
            document.getElementById('createFolderModal').style.display = 'none';
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
                mediaContainer.appendChild(video);
            }

            // Load comments for this asset
            loadComments(assetId);

            // Set asset ID on modal for comment submission
            document.getElementById('assetViewerModal').setAttribute('data-asset-id', assetId);

            document.getElementById('assetViewerModal').style.display = 'block';
        }

        function hideAssetViewer() {
            const mediaContainer = document.getElementById('mediaContainer');
            mediaContainer.innerHTML = '';
            // Clear comments
            document.getElementById('commentsList').innerHTML = '';
            document.getElementById('assetViewerModal').style.display = 'none';
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

            folders.forEach(folder => {
                const li = document.createElement('li');
                li.style.cssText = 'display: flex; justify-content: space-between; align-items: center; padding: 5px 0;';

                const a = document.createElement('a');
                a.href = '#';
                a.innerText = folder.name;
                a.style.cssText = 'flex-grow: 1; text-decoration: none; color: #495057;';
                a.onclick = (e) => {
                    e.preventDefault();
                    document.getElementById('current-view-title').innerText = folder.name;
                    fetchAssets(folder.id, 1); // Reset to page 1 when switching folders
                };

                const buttonContainer = document.createElement('div');
                buttonContainer.style.cssText = 'display: flex; gap: 5px;';

                const editButton = document.createElement('button');
                editButton.innerText = '✏️';
                editButton.style.cssText = 'background: none; border: none; cursor: pointer; font-size: 0.8rem;';
                editButton.onclick = (e) => {
                    e.stopPropagation();
                    showEditFolderModal(folder.id, folder.name);
                };

                const deleteButton = document.createElement('button');
                deleteButton.innerText = '🗑️';
                deleteButton.style.cssText = 'background: none; border: none; cursor: pointer; font-size: 0.8rem;';
                deleteButton.onclick = (e) => {
                    e.stopPropagation();
                    deleteFolder(folder.id, folder.name);
                };

                buttonContainer.appendChild(editButton);
                buttonContainer.appendChild(deleteButton);

                li.appendChild(a);
                li.appendChild(buttonContainer);
                folderSidebar.appendChild(li);
            });

            // Handle pagination
            if (paginationData && paginationData.last_page > 1) {
                renderFoldersPagination(paginationData);
            } else {
                document.getElementById('folders-pagination').style.display = 'none';
            }
        }

        function renderFoldersPagination(paginationData) {
            const paginationContainer = document.getElementById('folders-pagination');
            // const pageInfo = document.getElementById('folders-page-info');
            const pageNumbersContainer = document.getElementById('folders-page-numbers');
            const prevBtn = document.getElementById('folders-prev');
            const nextBtn = document.getElementById('folders-next');

            // pageInfo.textContent = `Page ${paginationData.current_page} of ${paginationData.last_page}`;
            prevBtn.disabled = paginationData.current_page <= 1;
            nextBtn.disabled = paginationData.current_page >= paginationData.last_page;

            prevBtn.onclick = () => fetchFolders(paginationData.current_page - 1);
            nextBtn.onclick = () => fetchFolders(paginationData.current_page + 1);

            // Clear previous page numbers
            pageNumbersContainer.innerHTML = '';

            const current = paginationData.current_page;
            const last = paginationData.last_page;

            // Add page numbers
            const pages = [];
            for (let i = 1; i <= last; i++) pages.push(i);

            pages.forEach(page => {
                const pageBtn = document.createElement('button');
                pageBtn.textContent = page;
                pageBtn.className = 'pagination-btn';
                if (page === current) {
                    pageBtn.disabled = true;
                }
                pageBtn.onclick = () => fetchFolders(page);
                pageNumbersContainer.appendChild(pageBtn);
            });

            paginationContainer.style.display = 'flex';
        }
        
        // Memuat semua aset dan folder saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            fetchAssets(null, 1);
            fetchFolders(1);

            // Add live search for folders
            const searchInput = document.getElementById('folder-search');
            searchInput.addEventListener('input', () => {
                const query = searchInput.value.toLowerCase();
                const filteredFolders = allFolders.filter(folder => folder.name.toLowerCase().includes(query));
                renderFolders(filteredFolders);
            });
        });

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
            if (event.target.closest('.like-button')) {
                const button = event.target.closest('.like-button');
                const assetId = button.getAttribute('data-asset-id');
                const likeIcon = button.querySelector('.like-icon');
                const likeCount = button.querySelector('.like-count');

                const isLiked = likeIcon.style.color === 'red';
                const url = isLiked ? `/assets/${assetId}/like` : `/assets/${assetId}/like`;
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
                        likeIcon.style.color = isLiked ? 'gray' : 'red';
                        likeCount.textContent = data.likes_count;
                    } else {
                        alert('Error: ' + (data.error || 'Something went wrong'));
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
                <div class="modal-content">
                    <span class="close-button" onclick="hideEditModal()">&times;</span>
                    <h2>Edit Media</h2>
                    <form id="editAssetForm">
                        <input type="hidden" name="id" value="${id}">
                        <div class="form-group">
                            <label for="editTitle">Judul</label>
                            <input type="text" id="editTitle" name="title" value="${title}">
                        </div>
                        <div class="form-group">
                            <label for="editCaption">Keterangan</label>
                            <textarea id="editCaption" name="caption">${caption}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="editFolder">Folder</label>
                            <select id="editFolder" name="folder_id">
                                <option value="none">Tidak ada folder</option>
                            </select>
                        </div>
                        <button type="submit">Simpan Perubahan</button>
                    </form>
                </div>
            `;

            const modal = document.createElement('div');
            modal.id = 'editModal';
            modal.className = 'modal';
            modal.innerHTML = modalContent;
            document.body.appendChild(modal);

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
                <div class="modal-content">
                    <span class="close-button" onclick="hideEditFolderModal()">&times;</span>
                    <h2>Edit Folder</h2>
                    <form id="editFolderForm">
                        <input type="hidden" name="id" value="${id}">
                        <div class="form-group">
                            <label for="editFolderName">Nama Folder</label>
                            <input type="text" id="editFolderName" name="name" value="${name}" required>
                        </div>
                        <button type="submit">Simpan Perubahan</button>
                    </form>
                </div>
            `;

            const modal = document.createElement('div');
            modal.id = 'editFolderModal';
            modal.className = 'modal';
            modal.innerHTML = modalContent;
            document.body.appendChild(modal);

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
                const commentHtml = `
                    <div class="comment">
                        <strong>${comment.user.name}:</strong> ${comment.comment}
                        <small style="color: #6c757d;">${new Date(comment.created_at).toLocaleString()}</small>
                    </div>
                `;
                commentsList.insertAdjacentHTML('beforeend', commentHtml);
            });
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
