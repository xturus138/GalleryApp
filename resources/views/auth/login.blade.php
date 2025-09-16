<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GalleryApp</title>
    <style>
        :root {
            --bg-gradient-start: #87CEEB;
            --bg-gradient-end: #E0F6FF;
            --card-bg: #ffffff;
            --primary-color: #000000;
            --accent-color: #6366f1;
            --text-color: #333333;
            --border-color: #e2e8f0;
            --shadow-light: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-focus: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
            overflow: hidden;
        }

        /* Subtle cloud illustrations */
        .clouds {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .cloud {
            position: absolute;
            opacity: 0.3;
        }

        .cloud:nth-child(1) {
            top: 10%;
            left: 10%;
            width: 120px;
            height: 80px;
        }

        .cloud:nth-child(2) {
            top: 20%;
            right: 15%;
            width: 100px;
            height: 60px;
        }

        .cloud:nth-child(3) {
            bottom: 15%;
            left: 20%;
            width: 140px;
            height: 90px;
        }

        .cloud:nth-child(4) {
            bottom: 25%;
            right: 10%;
            width: 110px;
            height: 70px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background-color: var(--card-bg);
            border-radius: 1rem; /* rounded-2xl */
            box-shadow: var(--shadow-light);
            text-align: center;
            animation: fadeIn 0.8s ease-out;
            position: relative;
            z-index: 1;
            margin: 1rem;
        }

        .login-container h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .login-container p {
            color: #6b7280;
            margin-bottom: 2rem;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.875rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: var(--shadow-focus);
        }

        .form-group input[type="password"] {
            font-family: 'Courier New', monospace; /* For PIN display */
        }

        .forgot-link {
            display: block;
            text-align: right;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: var(--accent-color);
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        .btn {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s, transform 0.2s;
            margin-bottom: 1.5rem;
        }

        .btn:hover {
            background-color: #333333;
            transform: translateY(-1px);
        }

        .btn:focus {
            outline: none;
            box-shadow: var(--shadow-focus);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: var(--border-color);
        }

        .divider::before {
            margin-right: 1rem;
        }

        .divider::after {
            margin-left: 1rem;
        }

        .social-buttons {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .social-btn {
            flex: 1;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            background-color: white;
            color: var(--text-color);
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: border-color 0.3s, box-shadow 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .social-btn:hover {
            border-color: var(--accent-color);
            box-shadow: var(--shadow-focus);
        }

        .social-btn:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: var(--shadow-focus);
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        @media (max-width: 480px) {
            .login-container {
                width: 90%;
                padding: 1.5rem;
                margin: 1rem;
            }

            .login-container h1 {
                font-size: 1.8rem;
            }

            .social-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="clouds">
        <svg class="cloud" viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg">
            <path d="M60 20 Q40 10 30 30 Q20 50 40 60 Q60 70 80 60 Q100 50 90 30 Q80 10 60 20 Z" fill="#ffffff"/>
        </svg>
        <svg class="cloud" viewBox="0 0 100 60" xmlns="http://www.w3.org/2000/svg">
            <path d="M50 15 Q35 5 25 25 Q15 45 35 55 Q55 65 75 55 Q85 45 75 25 Q65 5 50 15 Z" fill="#ffffff"/>
        </svg>
        <svg class="cloud" viewBox="0 0 140 90" xmlns="http://www.w3.org/2000/svg">
            <path d="M70 25 Q50 15 35 35 Q25 55 45 70 Q65 80 85 70 Q105 60 95 40 Q85 20 70 25 Z" fill="#ffffff"/>
        </svg>
        <svg class="cloud" viewBox="0 0 110 70" xmlns="http://www.w3.org/2000/svg">
            <path d="M55 18 Q40 8 30 28 Q20 48 40 58 Q60 68 80 58 Q90 48 80 28 Q70 8 55 18 Z" fill="#ffffff"/>
        </svg>
    </div>

    <div class="login-container" role="main" aria-labelledby="login-title">
        <h1 id="login-title">Sign in with Username</h1>
        <p>Enter your username and 4-digit PIN to continue</p>

        <form method="POST" action="{{ url('/login') }}" role="form" aria-describedby="login-description">
            @csrf

            @if ($errors->has('login'))
                <div class="error-message" role="alert" aria-live="polite">{{ $errors->first('login') }}</div>
            @endif

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="name" placeholder="Enter your username" required autofocus aria-required="true">
            </div>

            <div class="form-group">
                <label for="pin">PIN</label>
                <input type="password" id="pin" name="pin" placeholder="Enter 4-digit PIN" pattern="[0-9]{4}" maxlength="4" inputmode="numeric" required aria-required="true" aria-describedby="pin-help">
                <small id="pin-help" class="sr-only">Enter a 4-digit numeric PIN</small>
                <a href="#" class="forgot-link" aria-label="Forgot your PIN?">Forgot PIN?</a>
            </div>

            <button type="submit" class="btn" id="loginBtn" aria-describedby="login-description">Login</button>
        </form>

        <div class="divider">or</div>

        <div class="social-buttons">
            <button class="social-btn" aria-label="Login with Google">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Google
            </button>
            <button class="social-btn" aria-label="Login with Apple">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                </svg>
                Apple
            </button>
        </div>
    </div>

    <div id="loadingOverlay" class="loading-overlay" style="display: none;" aria-hidden="true">
        <div class="spinner" aria-label="Loading"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('loginBtn');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const pinInput = document.getElementById('pin');

            // Restrict PIN input to numbers only
            pinInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/\D/g, '');
                if (this.value.length > 4) {
                    this.value = this.value.slice(0, 4);
                }
            });

            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Logging in...';
                loadingOverlay.style.display = 'flex';
                loadingOverlay.setAttribute('aria-hidden', 'false');
            });
        });
    </script>
</body>
</html>
