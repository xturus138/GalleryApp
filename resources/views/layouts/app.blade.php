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
            width: 250px;
            background-color: #ffffff;
            border-right: 1px solid #e9ecef;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            position: relative;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -250px;
                height: 100%;
                z-index: 1500;
            }
            .sidebar.show {
                left: 0;
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
            gap: 10px;
            padding: 10px;
            margin-bottom: 1rem;
            border-radius: 8px;
            background-color: #f1f3f5;
        }
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .user-info .user-name {
            font-weight: bold;
            color: #333;
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
    </style>
</head>
<body>
    <div class="main-container">
        <aside class="sidebar">
            <div class="sidebar-header">
            </div>
            <div class="user-info">
                <img src="/placeholder-user.jpg" alt="User Avatar">
                <span class="user-name">{{ Auth::user()->name }}</span>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('dashboard') }}" id="all-media-link">🏠 All Media</a></li>
                <li><a href="#" onclick="showCreateFolderModal()">📂 Folder Baru</a></li>
                <li style="margin-top: 10px; font-weight: bold; color: #495057;">Daftar Folder:</li>
                <input type="text" class="search-input" placeholder="🔍 Search folders..." id="folder-search">
                <div id="folders-list-container">
                    </div>
            </ul>
            <a href="{{ route('logout') }}" class="logout-button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪 Logout</a>
        </aside>

        <div class="content">
            @yield('content')
        </div>
    </div>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>
</html>