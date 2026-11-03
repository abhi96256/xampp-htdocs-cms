<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | A2P Realtech CRM</title>

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Forgot Password | A2P Realtech CRM">
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
           LEFT: Form Container
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
            max-width: 460px;
        }

        /* Brand Logo */
        .logo-box {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 24px;
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
            margin-bottom: 8px;
            letter-spacing: -0.02em;
            text-align: center;
        }

        .page-subheadline {
            text-align: center;
            font-size: 0.94rem;
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        /* Alerts */
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
            margin-bottom: 20px;
            animation: shake 0.35s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .success-alert-banner {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: #f0fdf4;
            border-left: 4px solid #22c55e;
            border-radius: 10px;
            color: #166534;
            font-size: 0.86rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* Security Note Box */
        .security-notice-card {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-left: 4px solid #f59e0b;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 0.86rem;
            color: #92400e;
            line-height: 1.5;
            margin-bottom: 22px;
        }

        .security-notice-card strong {
            color: #78350f;
        }

        /* Form Inputs */
        .input-group-item {
            margin-bottom: 20px;
        }

        .label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .label-row label {
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--text-body);
        }

        .pill-field {
            width: 100%;
            height: 52px;
            background: var(--input-bg);
            border: 1.5px solid transparent;
            border-radius: 14px;
            padding: 0 18px;
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

        /* Multi-OTP Step 2 Card */
        .otp-verification-box {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            padding: 18px;
            margin-bottom: 20px;
        }

        .otp-box-header {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--primary-brand);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .single-otp-row {
            margin-bottom: 14px;
        }

        .single-otp-row:last-child {
            margin-bottom: 0;
        }

        .otp-label-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
        }

        .resend-btn-link {
            color: var(--primary-brand);
            font-weight: 700;
            text-decoration: none;
            font-size: 0.78rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        .resend-btn-link:hover {
            color: var(--primary-brand-dark);
            text-decoration: underline;
        }

        .otp-input-mini {
            width: 100%;
            height: 44px;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 0 14px;
            font-size: 0.92rem;
            font-weight: 600;
            color: #0f172a;
            transition: all 0.2s;
        }

        .otp-input-mini:focus {
            outline: none;
            border-color: var(--primary-brand);
            box-shadow: 0 0 0 3px var(--primary-brand-glow);
        }

        /* Action Pill Button */
        .btn-submit-main {
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
            margin-top: 10px;
        }

        .btn-submit-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(230, 46, 45, 0.45);
        }

        .btn-submit-main:active {
            transform: translateY(0);
            box-shadow: 0 6px 16px var(--primary-brand-glow);
        }

        /* Bottom Back Links */
        .back-nav-row {
            margin-top: 24px;
            text-align: center;
        }

        .back-nav-row a {
            color: #64748b;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-nav-row a:hover {
            color: var(--primary-brand);
        }

        /* ========================================================
           RIGHT: Full-Height Arch 3D Portal
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

        /* Responsive Breakpoints */
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
            }

            .page-subheadline {
                font-size: 0.86rem;
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
        
        <!-- LEFT: Forgot Password Form Pane -->
        <div class="form-pane-container">
            <div class="form-inner-wrapper">
                
                <!-- A2P Realtech Logo -->
                <div class="logo-box">
                    <img src="{{ asset('imgs/logo.png') }}" alt="A2P Realtech Logo">
                </div>

                <!-- Form Heading -->
                <h1 class="page-headline">Forgot Password</h1>
                <p class="page-subheadline">
                    @if(!session('fp_email'))
                        Enter your registered email to receive recovery OTPs
                    @else
                        Enter recovery OTPs to reset your password
                    @endif
                </p>

                <!-- Errors / Success Messages -->
                @if($errors->any())
                    <div class="error-alert-banner">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{!! implode('<br>', $errors->all()) !!}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="success-alert-banner">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(!session('fp_email'))
                <!-- Step 1: Request OTP -->
                <form action="{{ route('forgot-password.send') }}" method="POST">
                    @csrf
                    
                    <div class="input-group-item">
                        <div class="label-row">
                            <label for="email">Registered Email</label>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="pill-field" 
                            value="{{ old('email') }}" 
                            placeholder="Enter your email address" 
                            required 
                            autofocus
                        >
                    </div>

                    <div class="security-notice-card">
                        <i class="fa-solid fa-shield-halved"></i> 
                        Security Note: OTPs will be sent to your email, your Team Leader's email, and the Admin. <strong>You will need any 2 of these OTPs to reset your password.</strong>
                    </div>

                    <button type="submit" class="btn-submit-main">
                        <span>Send Recovery OTPs</span>
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>

                    <div class="back-nav-row">
                        <a href="{{ route('signin.index') }}">
                            <i class="fa-solid fa-arrow-left"></i> Back to Sign In
                        </a>
                    </div>
                </form>

                @else
                <!-- Step 2: Verify OTPs and set new password -->
                <form action="{{ route('forgot-password.verify') }}" method="POST">
                    @csrf
                    
                    <div class="otp-verification-box">
                        <div class="otp-box-header">
                            <i class="fa-solid fa-key"></i>
                            <span>Enter At Least 2 of the 3 OTPs:</span>
                        </div>
                        
                        <div class="single-otp-row">
                            <div class="otp-label-line">
                                <span>1. OTP from Your Email ({{ session('fp_email') }})</span>
                                <a href="javascript:void(0)" onclick="resendOtp('user')" class="resend-btn-link">Resend OTP</a>
                            </div>
                            <input type="text" id="otp_user" name="otp_user" class="otp-input-mini" placeholder="6-digit OTP" autofocus>
                        </div>

                        <div class="single-otp-row">
                            <div class="otp-label-line">
                                <span>2. OTP from Team Leader @if(session('tl_email')) ({{ session('tl_email') }}) @endif</span>
                                @if(session('tl_email'))
                                <a href="javascript:void(0)" onclick="resendOtp('tl')" class="resend-btn-link">Resend OTP</a>
                                @endif
                            </div>
                            <input type="text" id="otp_tl" name="otp_tl" class="otp-input-mini" placeholder="6-digit OTP">
                        </div>

                        <div class="single-otp-row">
                            <div class="otp-label-line">
                                <span>3. OTP from Admin</span>
                                <a href="javascript:void(0)" onclick="resendOtp('admin')" class="resend-btn-link">Resend OTP</a>
                            </div>
                            <input type="text" id="otp_admin" name="otp_admin" class="otp-input-mini" placeholder="6-digit OTP">
                        </div>
                    </div>

                    <div class="input-group-item">
                        <div class="label-row">
                            <label for="password">New Security Key (Password)</label>
                        </div>
                        <input type="password" id="password" name="password" class="pill-field" placeholder="Enter new password" required>
                    </div>
                    
                    <div class="input-group-item">
                        <div class="label-row">
                            <label for="password_confirmation">Confirm New Security Key</label>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="pill-field" placeholder="Confirm new password" required>
                    </div>

                    <button type="submit" class="btn-submit-main">
                        <span>Reset & Unlock Access</span>
                        <i class="fa-solid fa-lock-open"></i>
                    </button>

                    <div class="back-nav-row">
                        <a href="{{ route('signin.index') }}">
                            <i class="fa-solid fa-arrow-left"></i> Cancel
                        </a>
                    </div>
                </form>
                @endif

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

    <!-- OTP Resend Functionality -->
    <script>
        function resendOtp(target) {
            if(!confirm('Are you sure you want to resend the OTP for ' + target.toUpperCase() + '?')) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("forgot-password.resend") }}';
            
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';
            form.appendChild(token);
            
            const tgt = document.createElement('input');
            tgt.type = 'hidden';
            tgt.name = 'target';
            tgt.value = target;
            form.appendChild(tgt);
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>