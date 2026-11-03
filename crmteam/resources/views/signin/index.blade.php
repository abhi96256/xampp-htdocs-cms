<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | A2P Realtech CRM</title>

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Sign In | A2P Realtech CRM">
    <meta property="og:description" content="A2P Realtech Private Limited - Enterprise Real Estate CRM Platform.">
    <meta property="og:image" content="{{ asset('imgs/logo.png') }}">

    <!-- Google Fonts: Poppins & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --primary-brand: #e62e2d;
            --primary-brand-dark: #c51414;
            --primary-brand-glow: rgba(230, 46, 45, 0.32);
            --primary-gradient: linear-gradient(135deg, #ff4141 0%, #e62e2d 60%, #b81414 100%);
            --text-title: #111827;
            --text-body: #374151;
            --text-muted: #6b7280;
            --input-bg: #f3f5fa;
            --input-focus: #e62e2d;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
        }

        html, body {
            width: 100vw;
            height: 100vh;
            min-height: 100vh;
            overflow: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #ffffff;
        }

        /* Full Screen Split Viewport */
        .fullscreen-layout {
            display: grid;
            grid-template-columns: 1fr 1.05fr;
            width: 100vw;
            height: 100vh;
            position: relative;
            background: #ffffff;
        }

        /* ========================================================
           LEFT: Form Container (Spacious, Clean, Vertically Centered)
           ======================================================== */
        .form-pane-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 60px 40px 80px;
            background: #ffffff;
            height: 100vh;
            overflow-y: auto;
            position: relative;
            z-index: 2;
        }

        .form-inner-wrapper {
            width: 100%;
            max-width: 440px;
        }

        /* Brand Logo (Enlarged & Centered) */
        .logo-box {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 28px;
        }

        .logo-box img {
            max-height: 75px;
            width: auto;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .logo-box img:hover {
            transform: scale(1.03);
        }

        /* Title */
        .page-headline {
            font-family: 'Poppins', sans-serif;
            font-size: 2.15rem;
            font-weight: 700;
            color: var(--text-title);
            line-height: 1.15;
            margin-bottom: 30px;
            letter-spacing: -0.02em;
            text-align: center;
        }

        /* Error Banner */
        .error-alert-banner {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            border-radius: 10px;
            color: #b91c1c;
            font-size: 0.86rem;
            font-weight: 500;
            margin-bottom: 22px;
            animation: shake 0.35s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Form Inputs */
        .input-group-item {
            margin-bottom: 22px;
        }

        .label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .label-row label {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-body);
        }

        .forgot-pass-link {
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--primary-brand);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-pass-link:hover {
            color: var(--primary-brand-dark);
            text-decoration: underline;
        }

        .input-wrapper-pill {
            position: relative;
            display: flex;
            align-items: center;
        }

        .pill-field {
            width: 100%;
            height: 52px;
            background: var(--input-bg);
            border: 1.5px solid transparent;
            border-radius: 14px;
            padding: 0 46px 0 18px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            color: #111827;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .pill-field::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .pill-field:hover {
            background: #e9edf5;
        }

        .pill-field:focus {
            outline: none;
            background: #ffffff;
            border-color: var(--primary-brand);
            box-shadow: 0 0 0 4px var(--primary-brand-glow);
        }

        .eye-toggle-btn {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #9ca3af;
            font-size: 1.05rem;
            cursor: pointer;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .eye-toggle-btn:hover {
            color: var(--primary-brand);
            transform: translateY(-50%) scale(1.15);
        }

        /* Checkbox */
        .remember-wrap {
            margin-top: 4px;
            margin-bottom: 28px;
        }

        .custom-checkbox {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none;
        }

        .custom-checkbox input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 19px;
            height: 19px;
            border: 1.8px solid #cbd5e1;
            border-radius: 5px;
            outline: none;
            background: #ffffff;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }

        .custom-checkbox input[type="checkbox"]:checked {
            background: var(--primary-brand);
            border-color: var(--primary-brand);
        }

        .custom-checkbox input[type="checkbox"]:checked::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: #ffffff;
            font-size: 10px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .custom-checkbox span {
            font-size: 0.88rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .custom-checkbox:hover span {
            color: var(--text-title);
        }

        /* Action Pill Button */
        .btn-signin-main {
            width: 100%;
            height: 52px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 14px;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            font-size: 0.98rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 10px 25px var(--primary-brand-glow);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-signin-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(230, 46, 45, 0.45);
        }

        .btn-signin-main:active {
            transform: translateY(0);
            box-shadow: 0 6px 16px var(--primary-brand-glow);
        }

        /* Bottom Register text */
        .register-prompt {
            margin-top: 26px;
            text-align: center;
            font-size: 0.88rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .register-prompt a {
            color: var(--primary-brand);
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .register-prompt a:hover {
            color: var(--primary-brand-dark);
            text-decoration: underline;
        }

        /* ========================================================
           RIGHT: Full-Screen 3D Circular Arch Portal
           ======================================================== */
        .visual-pane-container {
            width: 100%;
            height: 100vh;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 24px 24px 24px 0;
        }

        /* The iconic large semi-circular / arch portal container */
        .arch-portal-canvas {
            width: 100%;
            height: calc(100vh - 48px);
            border-radius: 400px 32px 32px 400px;
            background: radial-gradient(circle at 50% 50%, #201a68 0%, #0c0e29 70%, #070919 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 20px 60px rgba(17, 24, 39, 0.15), inset 0 0 80px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .arch-portal-canvas img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: inherit;
            animation: floatGently 6s ease-in-out infinite alternate;
        }

        @keyframes floatGently {
            0% { transform: scale(1.02) translateY(0); }
            100% { transform: scale(1.05) translateY(-8px); }
        }



        /* ========================================================
           RESPONSIVE BREAKPOINTS
           ======================================================== */
        @media (max-width: 1024px) {
            html, body {
                overflow-y: auto;
                height: auto;
                min-height: 100vh;
            }

            .fullscreen-layout {
                grid-template-columns: 1fr;
                height: auto;
                min-height: 100vh;
            }

            .form-pane-container {
                padding: 28px 24px 40px;
                height: auto;
                min-height: auto;
                justify-content: flex-start;
            }

            .visual-pane-container {
                padding: 16px 20px 0;
                height: 280px;
                order: -1;
            }

            .arch-portal-canvas {
                height: 100%;
                border-radius: 28px;
                box-shadow: 0 12px 36px rgba(0, 0, 0, 0.18);
            }

            .arch-portal-canvas img {
                object-fit: cover;
                object-position: center center;
            }
        }

        @media (max-width: 576px) {
            .page-headline {
                font-size: 1.55rem;
                margin-bottom: 18px;
            }

            .logo-box {
                margin-bottom: 16px;
            }

            .logo-box img {
                max-height: 52px;
            }

            .form-pane-container {
                padding: 20px 18px 36px;
            }

            .visual-pane-container {
                height: 240px;
                padding: 12px 14px 0;
            }

            .arch-portal-canvas {
                border-radius: 24px;
            }

            .arch-portal-canvas img {
                object-fit: cover;
                object-position: center center;
            }

            .pill-field {
                font-size: 16px;
                height: 48px;
            }

            .btn-signin-main {
                height: 48px;
                font-size: 0.92rem;
            }
        }

        @media (max-width: 400px) {
            .visual-pane-container {
                height: 210px;
                padding: 8px 10px 0;
            }
            .arch-portal-canvas {
                border-radius: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="fullscreen-layout">
        
        <!-- LEFT: Sign In Form Pane -->
        <div class="form-pane-container">
            <div class="form-inner-wrapper">
                
                <!-- A2P Realtech Logo -->
                <div class="logo-box">
                    <img src="{{ asset('imgs/logo.png') }}" alt="A2P Realtech Logo">
                </div>

                <!-- Form Heading -->
                <h1 class="page-headline">Sign In to your Account</h1>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="error-alert-banner">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('signin') }}" method="POST">
                    @csrf
                    
                    <!-- Email Field -->
                    <div class="input-group-item">
                        <div class="label-row">
                            <label for="email">Email</label>
                        </div>
                        <div class="input-wrapper-pill">
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="pill-field" 
                                value="{{ old('email') }}" 
                                placeholder="Enter Email" 
                                required 
                                autofocus
                                autocomplete="email"
                            >
                        </div>
                    </div>

                    <!-- Password Field with Forgot Link -->
                    <div class="input-group-item">
                        <div class="label-row">
                            <label for="password">Password</label>
                            <a href="{{ route('forgot-password.index') }}" class="forgot-pass-link">Forgot password?</a>
                        </div>
                        <div class="input-wrapper-pill">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="pill-field" 
                                placeholder="Enter Password" 
                                required
                                autocomplete="current-password"
                            >
                            <button type="button" class="eye-toggle-btn" id="togglePassword" aria-label="Toggle password visibility">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="remember-wrap">
                        <label class="custom-checkbox" for="remember">
                            <input type="checkbox" id="remember" name="remember">
                            <span>Remember me</span>
                        </label>
                    </div>

                    <!-- Submit Pill Button -->
                    <button type="submit" class="btn-signin-main">
                        SIGN IN
                    </button>

                    <!-- Register Link -->
                    <p class="register-prompt">
                        Not registered yet? <a href="{{ route('register') }}">Create an account</a>
                    </p>
                </form>

            </div>
        </div>

        <!-- RIGHT: Full-Height Arch 3D Portal -->
        <div class="visual-pane-container">
            <div class="arch-portal-canvas">
                <img 
                    src="{{ asset('imgs/crm_illustration.jpg') }}" 
                    alt="A2P CRM 3D Portal"
                >
            </div>
        </div>

    </div>

    <!-- Password Visibility Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('togglePassword');
            const pwdInput = document.getElementById('password');

            if (toggleBtn && pwdInput) {
                toggleBtn.addEventListener('click', function () {
                    const isPassword = pwdInput.type === 'password';
                    pwdInput.type = isPassword ? 'text' : 'password';
                    
                    const icon = toggleBtn.querySelector('i');
                    if (icon) {
                        if (isPassword) {
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>