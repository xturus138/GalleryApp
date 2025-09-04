@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="header">
        <h1>Gallery</h1>
        <div class="action-buttons">
            <button onclick="showUploadModal()">Unggah</button>
            <button>Folder Baru</button>
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
        </div>
    </div>

    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="spinner"></div>
    </div>

    <style>
        .gallery-item-info {
            display: flex;
            flex-direction: column;
            padding: 10px;
        }
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
        .gallery-item {
            position: relative;
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
            width: 80%;
            max-width: 900px;
            position: relative;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        #mediaContainer {
            width: 100%;
            height: auto;
            display: flex;
            justify-content: center;
        }
        #mediaContainer img,
        #mediaContainer video {
            max-width: 100%;
            height: auto;
            display: block;
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
        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border-left-color: #6366f1;
            animation: spin 1s ease infinite;
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetchAssets();
        });

        async function fetchAssets() {
            // Tampilkan skeleton loader
            document.getElementById('loading-skeleton').style.display = 'grid';
            document.getElementById('gallery-container').style.display = 'none';

            try {
                const response = await fetch('{{ route("assets.list") }}');
                const assets = await response.json();
                
                // Sembunyikan skeleton dan tampilkan konten
                document.getElementById('loading-skeleton').style.display = 'none';
                document.getElementById('gallery-container').style.display = 'grid';
                renderAssets(assets);
            } catch (error) {
                console.error('Error fetching assets:', error);
                document.getElementById('loading-skeleton').innerHTML = '<p>Gagal memuat galeri. Silakan coba lagi.</p>';
            }
        }

        function renderAssets(assets) {
            const container = document.getElementById('gallery-container');
            container.innerHTML = ''; // Hapus konten lama
            
            if (assets.length === 0) {
                container.innerHTML = '<p>Belum ada file yang diunggah.</p>';
                return;
            }

            assets.forEach(asset => {
                let mediaHtml = '';
                if (asset.file_type.startsWith('image/')) {
                    mediaHtml = `<img src="${asset.blob_url}" alt="${asset.title || asset.original_filename}">`;
                } else if (asset.file_type.startsWith('video/')) {
                    mediaHtml = `<video poster="/video-thumbnail.png" src="${asset.blob_url}"></video>`;
                } else {
                    mediaHtml = `<img src="/file-icon.png" alt="File">`;
                }
                
                const fileSizeInKb = (asset.file_size / 1024).toFixed(2);
                
                const assetHtml = `
                    <div class="gallery-item">
                        ${mediaHtml}
                        <div class="gallery-item-info">
                            <div class="info-top">
                                <h4 class="truncate">${asset.title || asset.original_filename}</h4>
                                <button class="view-button" onclick="showAssetViewer('${asset.blob_url}', '${asset.file_type}', '${asset.title || asset.original_filename}')">Lihat</button>
                            </div>
                            <div class="asset-details">
                                <p><strong>Ukuran:</strong> ${fileSizeInKb} KB</p>
                                ${asset.caption ? `<p><strong>Keterangan:</strong> ${asset.caption}</p>` : ''}
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', assetHtml);
            });
        }

        function showUploadModal() {
            document.getElementById('uploadModal').style.display = 'block';
        }

        function hideUploadModal() {
            document.getElementById('uploadModal').style.display = 'none';
        }

        function showAssetViewer(url, fileType, filename) {
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

            document.getElementById('assetViewerModal').style.display = 'block';
        }

        function hideAssetViewer() {
            const mediaContainer = document.getElementById('mediaContainer');
            mediaContainer.innerHTML = '';
            document.getElementById('assetViewerModal').style.display = 'none';
        }

        document.getElementById('uploadForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            document.getElementById('loadingOverlay').style.display = 'flex';

            const form = event.target;
            const formData = new FormData();
            const fileInput = document.getElementById('fileInput');
            const titleInput = document.getElementById('title');
            const captionInput = document.getElementById('caption');


            if (fileInput.files.length === 0) {
                alert('Pilih setidaknya satu file.');
                document.getElementById('loadingOverlay').style.display = 'none';
                return;
            }

            for (const file of fileInput.files) {
                formData.append('files[]', file);
            }
            
            formData.append('title', titleInput.value);
            formData.append('caption', captionInput.value);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                const data = await response.json();

                if(data.success) {
                    alert('File berhasil diunggah!');
                    hideUploadModal();
                    await fetchAssets();
                } else {
                    alert('Unggah gagal: ' + JSON.stringify(data.errors));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengunggah file.');
            } finally {
                document.getElementById('loadingOverlay').style.display = 'none';
            }
        });
    </script>
@endsection