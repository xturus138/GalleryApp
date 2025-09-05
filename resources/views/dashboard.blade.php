<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .dashboard-container {
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            animation: fadeInUp 0.8s ease-out;
        }
        .dashboard-container h1 {
            color: #4a5568;
        }
        .dashboard-container p {
            color: #718096;
        }
        .btn-logout {
            margin-top: 1.5rem;
            padding: 0.75rem 1.5rem;
            background-color: #e53e3e;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-logout:hover {
            background-color: #c53030;
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
            border-left-color: #e53e3e;
            animation: spin 1s ease infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-logout:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        @media (max-width: 480px) {
            .dashboard-container {
                width: 90%;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Selamat datang, {{ Auth::user()->name }}!</h1>
        <p>Anda telah berhasil masuk ke dashboard.</p>
        <button type="button" class="btn-logout" id="logoutBtn" onclick="handleLogout()">Logout</button>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="spinner"></div>
    </div>

    <script>
        function handleLogout() {
            const logoutBtn = document.getElementById('logoutBtn');
            const loadingOverlay = document.getElementById('loadingOverlay');

            logoutBtn.disabled = true;
            logoutBtn.textContent = 'Logging out...';
            loadingOverlay.style.display = 'flex';

            document.getElementById('logout-form').submit();
        }
    </script>
</body>
</html>
