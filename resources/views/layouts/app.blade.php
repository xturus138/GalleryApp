<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GalleryApp - @yield('title')</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-container {
            display: flex;
            flex-grow: 1;
        }
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-right: 1px solid #e2e8f0;
            padding: 24px;
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            position: relative;
            animation: slideInLeft 0.5s ease-out;
            border-radius: 0 16px 16px 0;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 60px;
                left: 0;
                width: 100%;
                height: calc(100vh - 60px);
                z-index: 1500;
                transform: translateY(-100%);
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            .sidebar.show {
                transform: translateY(0);
                opacity: 1;
                visibility: visible;
            }
        }
        .sidebar-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .sidebar-header img {
            width: 60px;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            margin-bottom: 24px;
            border-radius: 12px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #d1d5db;
            transition: all 0.2s ease;
        }
        .user-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .user-info img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .user-info .user-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 16px;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }
        .sidebar-menu a {
            display: block;
            padding: 12px 15px;
            text-decoration: none;
            color: #495057;
            border-radius: 8px;
            transition: background-color 0.2s;
        }
        .sidebar-menu a:hover {
            background-color: #e9ecef;
        }
        .search-input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        .logout-button {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            padding: 12px 15px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            transition: background-color 0.2s;
        }
        .logout-button:hover {
            background-color: #c82333;
        }
        .content {
            flex-grow: 1;
            padding: 40px;
            transition: margin-left 0.3s ease;
            animation: slideInRight 0.5s ease-out 0.2s both;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .header h1 {
            font-size: 2rem;
            color: #333;
            margin: 0;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons button {
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        .action-buttons button:hover {
            background-color: #0056b3;
        }
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }
        .gallery-item {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            overflow: hidden;
            position: relative;
        }
        .gallery-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .gallery-item-info {
            padding: 10px;
        }
        .gallery-item-info h4 {
            margin: 0 0 5px 0;
            font-size: 1rem;
        }
        .gallery-item-info p {
            margin: 0;
            font-size: 0.8rem;
            color: #6c757d;
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
            border-left-color: #dc3545;
            animation: spin 1s ease infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes slideInLeft {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .logout-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .hamburger-menu {
            display: none;
            cursor: pointer;
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 1600;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 60px;
                left: 0;
                width: 70%;
                height: calc(100vh - 60px);
                z-index: 1500;
                transform: translateX(-100%);
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                padding: 20px;
                box-sizing: border-box;
                border-radius: 0 0 16px 0;
            }
            .sidebar.show {
                transform: translateX(0);
                opacity: 1;
                visibility: visible;
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1499;
            }
            .sidebar-overlay.show {
                display: block;
            }
            .no-scroll {
                overflow: hidden;
                overflow-x: hidden;
            }
            .sidebar {
                overflow-x: hidden;
            }
            .content {
                padding: 20px;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .hamburger-menu {
                display: block;
            }
        }
    </style>
</head>
    <body>
        <div class="hamburger-menu" onclick="toggleSidebar()">
            &#9776;
        </div>
        <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
        <div class="main-container">
            <aside class="sidebar">
                <div class="sidebar-header">
                </div>
                <div class="user-info">
                    <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : '/placeholder-user.jpg' }}" alt="User Avatar">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                </div>
                <ul class="sidebar-menu">
                    <li><a href="{{ route('dashboard') }}" id="all-media-link">🏠 All Media</a></li>
                    <li><a href="{{ route('profile.show') }}">👤 Profile</a></li>
                    <li><a href="#" onclick="showCreateFolderModal()">📂 Folder Baru</a></li>
                    <li style="margin-top: 10px; font-weight: bold; color: #495057;">Daftar Folder:</li>
                    <input type="text" class="search-input" placeholder="🔍 Search folders..." id="folder-search">
                    <div id="folders-list-container">
                        </div>
                    <!-- Pagination for Folders -->
                    <div id="folders-pagination" class="pagination-container" style="display: none; margin-top: 10px;">
                        <button id="folders-prev" class="pagination-btn"><</button>
                        <div id="folders-page-numbers" class="page-numbers"></div>
                        <button id="folders-next" class="pagination-btn">></button>
                    </div>
                </ul>
        <a href="{{ route('logout') }}" class="logout-button" id="logoutLink">🚪 Logout</a>
    </aside>

    <div class="content">
        @yield('content')
    </div>
        </div>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <div id="loadingOverlay" class="loading-overlay" style="display: none;">
            <div class="spinner"></div>
        </div>

        <script>
            function toggleSidebar() {
                document.querySelector('.sidebar').classList.toggle('show');
                document.querySelector('.sidebar-overlay').classList.toggle('show');
                document.body.classList.toggle('no-scroll');
            }

        document.getElementById('logoutLink').addEventListener('click', function(event) {
            event.preventDefault();

            const logoutLink = this;
            const loadingOverlay = document.getElementById('loadingOverlay');

            logoutLink.disabled = true;
            logoutLink.textContent = 'Logging out...';
            loadingOverlay.style.display = 'flex';

            document.getElementById('logout-form').submit();
        });
    </script>
</body>
</html>
