<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GalleryApp</title>
    <style>
        :root {
            --bg-color: #eef2f5;
            --card-bg: #ffffff;
            --primary-color: #4a5568;
            --accent-color: #6366f1;
            --text-color: #333333;
            --border-color: #e2e8f0;
            --shadow-light: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--shadow-light);
            text-align: center;
        }

        .login-container h2 {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5em;
        }

        .login-container p {
            color: #718096;
            margin-bottom: 2em;
        }

        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        .btn {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: var(--accent-color);
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: -0.5rem;
            margin-bottom: 1rem;
            text-align: left;
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
            border-left-color: var(--accent-color);
            animation: spin 1s ease infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>GalleryApp</h2>
        <p>Login dengan nama dan PIN Anda.</p>
        
        <form method="POST" action="{{ url('/login') }}">
            @csrf
            
            @if ($errors->has('login'))
                <div class="error-message">{{ $errors->first('login') }}</div>
            @endif
            
            <div class="form-group">
                <input type="text" name="name" placeholder="Nama Pengguna" required autofocus>
            </div>
            
            <div class="form-group">
                <input type="password" name="pin" placeholder="PIN" required>
            </div>
            
            <button type="submit" class="btn" id="loginBtn">Login</button>
        </form>
    </div>

    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="spinner"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('loginBtn');
            const loadingOverlay = document.getElementById('loadingOverlay');

            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Logging in...';
                loadingOverlay.style.display = 'flex';
            });
        });
    </script>
</body>
</html>
