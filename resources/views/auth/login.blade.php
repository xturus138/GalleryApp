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

            /* Responsive Modal Styles */
            .modal {
                padding: 2rem; /* increased padding for margin on mobile */
            }

            .modal-content {
                margin: 0;
                padding: 1.5rem;
                width: 100%;
                border-radius: 0.75rem;
                box-sizing: border-box; /* added for proper sizing */
                max-width: 360px; /* slightly smaller max width for mobile */
            }

            .modal-content h2 {
                font-size: 1.125rem;
                margin-bottom: 0.75rem;
            }

            .modal p {
                font-size: 0.9375rem;
                line-height: 1.5;
                margin: 0.75rem 0 1rem 0;
                white-space: normal;
                word-wrap: break-word;
            }

            .modal-content .close {
                font-size: 24px;
                right: 0.75rem;
                top: 0.75rem;
            }

            .modal-content > div.modal-buttons {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
                align-items: stretch;
            }

            .modal-content > div.modal-buttons button {
                width: 100%;
                padding: 0.875rem;
                font-size: 0.9375rem;
                border-radius: 0.5rem;
                cursor: pointer;
                font-weight: 600;
                border: none;
                transition: background-color 0.3s;
            }

            .modal-content > div.modal-buttons button.continue-btn {
                background-color: var(--primary-color);
                color: white;
            }

            .modal-content > div.modal-buttons button.continue-btn:hover {
                background-color: #333333;
            }

            .modal-content > div.modal-buttons button.cancel-btn {
                background-color: #6c757d;
                color: white;
            }

            .modal-content > div.modal-buttons button.cancel-btn:hover {
                background-color: #5a6268;
            }
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease-out;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-content {
            background-color: var(--card-bg);
            margin: 0;
            padding: 2rem;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: var(--shadow-light);
            position: relative;
        }

        .close {
            background: none;
            border: none;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            right: 1rem;
            top: 1rem;
            padding: 0;
            line-height: 1;
        }

        .close:hover,
        .close:focus {
            color: var(--text-color);
            text-decoration: none;
            outline: none;
        }

        .modal p {
            margin: 1rem 0 0 0;
            font-size: 1rem;
            color: var(--text-color);
        }

        .modal-content h2 {
            margin: 0 0 1rem 0;
            font-size: 1.25rem;
            color: var(--text-color);
            font-weight: 600;
        }

        .beta-note {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 1rem;
            text-align: center;
            font-style: italic;
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
        <p class="beta-note">Note: This app is still in beta and may contain bugs, but your data storage is secure.</p>
        <p id="mobileNote" class="beta-note" style="display: none; margin-top: 1rem; font-style: normal;">You are currently viewing this website on a mobile device. For the best experience, we recommend using a desktop or tablet. Some features may not be fully optimized on mobile.</p>
    </div>

    <!-- Forgot Password Modal -->
    <div id="forgotModal" class="modal">
        <div class="modal-content">
            <button class="close" aria-label="Close modal">&times;</button>
            <h2>Forgot Your PIN?</h2>
            <p>Untuk reset password, hubungi orang paling ganteng!</p>
        </div>
    </div>

    <!-- Mobile Detection Modal -->
    <div id="mobileModal" class="modal">
        <div class="modal-content">
            <button class="close" aria-label="Close">&times;</button>
            <h2>Mobile Device Detected</h2>
            <p>You are currently viewing this website on a mobile device.<br>
            For the best experience, we recommend using a desktop or tablet. Some features may not be fully optimized on mobile.</p>
            <div class="modal-buttons" style="margin-top: 1rem;">
                <button id="continueLogin" class="continue-btn">Continue Login</button>
                <button id="cancelLogin" class="cancel-btn">Cancel</button>
            </div>
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
            const mobileNote = document.getElementById('mobileNote');

            // Show mobile note if on mobile
            if (mobileNote && window.innerWidth <= 768) {
                mobileNote.style.display = 'block';
            }

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

            // Modal functionality
            const forgotLink = document.querySelector('.forgot-link');
            const forgotModal = document.getElementById('forgotModal');
            const forgotCloseBtn = forgotModal ? forgotModal.querySelector('.close') : null;

            if (forgotLink && forgotModal && forgotCloseBtn) {
                forgotLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    forgotModal.style.display = 'flex';
                });

                forgotCloseBtn.addEventListener('click', function() {
                    forgotModal.style.display = 'none';
                });

                window.addEventListener('click', function(e) {
                    if (e.target === forgotModal) {
                        forgotModal.style.display = 'none';
                    }
                });
            }

            // Mobile login popup functionality
            const mobileModal = document.getElementById('mobileModal');
            const mobileCloseBtn = mobileModal ? mobileModal.querySelector('.close') : null;
            const continueBtn = document.getElementById('continueLogin');
            const cancelBtn = document.getElementById('cancelLogin');

            if (mobileModal && continueBtn && cancelBtn && submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768) {
                        e.preventDefault();
                        mobileModal.style.display = 'flex';
                        return false;
                    }
                });

                if (mobileCloseBtn) {
                    mobileCloseBtn.addEventListener('click', function() {
                        mobileModal.style.display = 'none';
                    });
                }

                continueBtn.addEventListener('click', function() {
                    mobileModal.style.display = 'none';
                    form.submit();
                });

                cancelBtn.addEventListener('click', function() {
                    mobileModal.style.display = 'none';
                });

                // Close on outside click for mobile modal
                window.addEventListener('click', function(e) {
                    if (e.target === mobileModal) {
                        mobileModal.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>
