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
            flex: 1;
        }
        .sidebar {
            width: 300px;
            max-width: 320px;
            min-width: 280px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-right: 1px solid #e2e8f0;
            padding: 24px;
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            transition: all 0.3s ease;
            position: relative;
            animation: slideInLeft 0.5s ease-out;
            border-radius: 0 16px 16px 0;
            overflow-y: auto;
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
            position: relative;
        }
        .sidebar-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #6366f1, #4f46e5);
            border-radius: 2px;
        }
        .sidebar-header {
            position: relative;
        }
        .sidebar-close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 8px;
            padding: 8px;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s ease;
            display: none;
            z-index: 10;
        }
        .sidebar-close-btn:hover {
            background: rgba(0, 0, 0, 0.2);
            color: #374151;
        }
        .sidebar-header img {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
        }
        .sidebar-header img:hover {
            transform: scale(1.05) rotate(5deg);
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            margin-bottom: 28px;
            border-radius: 16px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .user-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #6366f1, #4f46e5, #7c3aed);
        }
        .user-info:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
            border-color: #6366f1;
        }
        .user-info img {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            border: 3px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }
        .user-info:hover img {
            transform: scale(1.1);
        }
        .user-info .user-details {
            flex: 1;
        }
        .user-info .user-name {
            font-weight: 700;
            color: #1f2937;
            font-size: 17px;
            margin-bottom: 4px;
            letter-spacing: -0.025em;
        }
        .user-info .user-role {
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }
        .sidebar-menu li {
            margin-bottom: 4px;
        }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            text-decoration: none;
            color: #495057;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            font-size: 14px;
            position: relative;
            overflow: hidden;
        }
        .sidebar-menu a::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
            border-radius: 0 4px 4px 0;
        }
        .sidebar-menu a:hover {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(79, 70, 229, 0.05) 100%);
            color: #6366f1;
            transform: translateX(4px);
        }
        .sidebar-menu a:hover::before {
            transform: scaleY(1);
        }
        .sidebar-menu a.active {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }
        .search-container {
            position: relative;
            margin-bottom: 16px;
        }

        .search-input {
            width: 100%;
            padding: 12px 40px 12px 40px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            box-sizing: border-box;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            background: var(--bg-primary);
            transition: all 0.3s ease;
            position: relative;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            transform: translateY(-1px);
        }

        .search-input::placeholder {
            color: var(--text-light);
            font-weight: 400;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            width: 18px;
            height: 18px;
            pointer-events: none;
        }

        .clear-search-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            border-radius: 50%;
            color: var(--text-light);
            display: none;
            transition: all 0.2s ease;
        }

        .clear-search-btn:hover {
            background: var(--bg-hover);
            color: var(--text-muted);
        }

        .search-input:not(:placeholder-shown) + .clear-search-btn {
            display: block;
        }
        .logout-button {
            margin-top: auto;
            padding: 14px 18px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: calc(100% - 40px);
            align-self: center;
            margin-bottom: 24px;
        }
        .logout-button:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
        }
        .logout-button:active {
            transform: translateY(0);
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
                width: 280px;
                height: calc(100vh - 60px);
                z-index: 1500;
                transform: translateX(-100%);
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                padding: 20px;
                box-sizing: border-box;
                border-radius: 0 16px 16px 0;
                max-width: 80vw;
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
                backdrop-filter: blur(2px);
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
                overflow-y: auto;
            }
            .content {
                padding: 20px;
                margin-left: 0;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .hamburger-menu {
                display: block;
                z-index: 1600;
                background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
                border: none;
                padding: 12px;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
                transition: all 0.3s ease;
            }
            .hamburger-menu:hover {
                transform: scale(1.05);
                box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
            }
            .hamburger-menu:active {
                transform: scale(0.95);
            }
            .sidebar-close-btn {
                display: block !important;
            }
            .sidebar-header img {
                width: 56px;
                height: 56px;
            }
            .user-info {
                padding: 16px;
                margin-bottom: 20px;
            }
            .user-info img {
                width: 48px;
                height: 48px;
            }
            .user-info .user-name {
                font-size: 15px;
            }
            .user-info .user-role {
                font-size: 12px;
            }
            .sidebar-menu a {
                padding: 12px 16px;
                font-size: 13px;
            }
            .logout-button {
                padding: 12px 16px;
                font-size: 13px;
                width: calc(100% - 32px);
                margin-bottom: 20px;
                align-self: center;
            }
        }
    </style>
</head>
    <body>
        <div class="hamburger-menu" onclick="toggleSidebar()" title="Toggle Menu">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </div>
        <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
        <div class="main-container">
            <aside class="sidebar">
                <div class="sidebar-header">
                    <button class="sidebar-close-btn" onclick="toggleSidebar()" title="Close Menu">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="user-info">
                    <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : '/placeholder-user.jpg' }}" alt="User Avatar">
                    <div class="user-details">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">Gallery User</div>
                    </div>
                </div>
                <ul class="sidebar-menu">
                    <li><a href="{{ route('dashboard') }}" id="all-media-link">🏠 All Media</a></li>
                    <li><a href="{{ route('profile.show') }}">👤 Profile</a></li>
                    <li><a href="#" onclick="showCreateFolderModal()">📂 Folder Baru</a></li>
                    <li style="margin-top: 10px; font-weight: bold; color: #495057;">Daftar Folder:</li>
                    <div class="search-container">
                        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" class="search-input" placeholder="Search folders..." id="folder-search">
                        <button class="clear-search-btn" id="clear-search" title="Clear search" aria-label="Clear search">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div id="folders-list-container">
                        </div>
                    <!-- Pagination for Folders -->
                    <div id="folders-pagination" class="modern-pagination" style="display: none; margin-top: 16px;">
                        <button id="folders-prev" class="pagination-arrow" aria-label="Previous page">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15,18 9,12 15,6"></polyline>
                            </svg>
                        </button>
                        <div id="folders-page-numbers" class="pagination-numbers"></div>
                        <button id="folders-next" class="pagination-arrow" aria-label="Next page">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9,18 15,12 9,6"></polyline>
                            </svg>
                        </button>
                    </div>
                    <div id="folders-page-info" class="page-info" style="display: none; text-align: center; margin-top: 8px; font-size: 12px; color: #6b7280;"></div>
                </ul>
        <a href="{{ route('logout') }}" class="logout-button" id="logoutLink">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16,17 21,12 16,7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            Logout
        </a>
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
                    const sidebar = document.querySelector('.sidebar');
                    const overlay = document.querySelector('.sidebar-overlay');
                    const isMobile = window.innerWidth <= 768;
    
                    if (isMobile) {
                        sidebar.classList.toggle('show');
                        overlay.classList.toggle('show');
                        document.body.classList.toggle('no-scroll');
    
                        // Store sidebar state in localStorage
                        const isOpen = sidebar.classList.contains('show');
                        localStorage.setItem('sidebarOpen', isOpen);
                    }
                }
    
                // Initialize sidebar state on page load
                document.addEventListener('DOMContentLoaded', function() {
                    const sidebar = document.querySelector('.sidebar');
                    const overlay = document.querySelector('.sidebar-overlay');
                    const isMobile = window.innerWidth <= 768;
    
                    if (isMobile) {
                        const sidebarOpen = localStorage.getItem('sidebarOpen') === 'true';
                        if (sidebarOpen) {
                            sidebar.classList.add('show');
                            overlay.classList.add('show');
                            document.body.classList.add('no-scroll');
                        }
                    }
                });
    
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
