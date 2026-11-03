<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User | A2P Realtech CRM</title>

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Create User | A2P Realtech CRM">
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
            grid-template-columns: 1.15fr 0.9fr;
            width: 100vw;
            height: 100vh;
            position: relative;
            background: #ffffff;
        }

        /* ========================================================
           LEFT: Form Container (Spacious, Scrollable, Clean)
           ======================================================== */
        .form-pane-container {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            padding: 40px 50px;
            background: #ffffff;
            height: 100vh;
            overflow-y: auto;
            position: relative;
            z-index: 2;
        }

        /* Custom smooth scrollbar */
        .form-pane-container::-webkit-scrollbar {
            width: 6px;
        }
        .form-pane-container::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .form-inner-wrapper {
            width: 100%;
            max-width: 620px;
            padding-bottom: 30px;
        }

        /* Brand Logo */
        .logo-box {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo-box img {
            max-height: 70px;
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
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-title);
            line-height: 1.15;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
            text-align: center;
        }

        .page-subheadline {
            text-align: center;
            font-size: 0.92rem;
            color: var(--text-muted);
            margin-bottom: 26px;
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

        /* Form Grid */
        .register-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px 20px;
        }

        .input-group-item {
            display: flex;
            flex-direction: column;
        }

        .input-group-item.full-span {
            grid-column: 1 / -1;
        }

        .label-row {
            margin-bottom: 6px;
        }

        .label-row label {
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-body);
        }

        .pill-field {
            width: 100%;
            height: 48px;
            background: var(--input-bg);
            border: 1.5px solid transparent;
            border-radius: 12px;
            padding: 0 16px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.92rem;
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

        /* File Input Styling */
        input[type="file"].pill-field {
            padding: 10px 14px;
            font-size: 0.85rem;
            color: #4b5563;
            cursor: pointer;
        }

        input[type="file"]::file-selector-button {
            border: none;
            background: #e2e8f0;
            padding: 4px 10px;
            border-radius: 6px;
            color: #334155;
            font-weight: 600;
            font-size: 0.8rem;
            margin-right: 10px;
            cursor: pointer;
            transition: background 0.2s;
        }

        input[type="file"]::file-selector-button:hover {
            background: #cbd5e1;
        }

        textarea.pill-field {
            height: auto;
            padding: 12px 16px;
            resize: vertical;
        }

        /* Bottom Actions */
        .actions-row {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
            padding-top: 10px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #4b5563;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--primary-brand);
        }

        .btn-submit-action {
            height: 50px;
            padding: 0 36px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            font-size: 0.96rem;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 10px 25px var(--primary-brand-glow);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(230, 46, 45, 0.45);
        }

        .btn-submit-action:active {
            transform: translateY(0);
            box-shadow: 0 6px 16px var(--primary-brand-glow);
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
                padding: 28px 20px 40px;
                height: auto;
                min-height: auto;
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

        @media (max-width: 640px) {
            .register-form-grid {
                grid-template-columns: 1fr !important;
                gap: 14px;
            }

            .page-headline {
                font-size: 1.55rem;
            }

            .logo-box {
                margin-bottom: 14px;
            }

            .logo-box img {
                max-height: 50px;
            }

            .form-pane-container {
                padding: 20px 16px 36px;
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

            .pill-field, .role-select {
                font-size: 16px;
                height: 46px;
            }

            .actions-row {
                flex-direction: column-reverse;
                gap: 14px;
                align-items: stretch;
            }

            .btn-submit-action {
                width: 100%;
                height: 48px;
            }

            .back-link {
                justify-content: center;
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
        
        <!-- LEFT: Create User Form Pane -->
        <div class="form-pane-container">
            <div class="form-inner-wrapper">
                
                <!-- A2P Realtech Logo -->
                <div class="logo-box">
                    <img src="{{ asset('imgs/logo.png') }}" alt="A2P Realtech Logo">
                </div>

                <!-- Form Heading -->
                <h1 class="page-headline">Create New User</h1>
                <p class="page-subheadline">Fill in details to register a new CRM account</p>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="error-alert-banner">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" class="register-form-grid">
                    @csrf
                    
                    <div class="input-group-item">
                        <div class="label-row"><label>Name*</label></div>
                        <input type="text" name="name" class="pill-field" placeholder="Full Name" value="{{ old('name') }}" required>
                    </div>

                    <div class="input-group-item">
                        <div class="label-row"><label>Email*</label></div>
                        <input type="email" name="email" class="pill-field" placeholder="Email Address" value="{{ old('email') }}" required>
                    </div>

                    <div class="input-group-item">
                        <div class="label-row"><label>Photo</label></div>
                        <input type="file" name="photo" class="pill-field" accept="image/*">
                    </div>

                    <div class="input-group-item">
                        <div class="label-row"><label>Role*</label></div>
                        <select name="role" class="pill-field" required>
                            <option value="">Select Role</option>
                            <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Team Lead" {{ old('role') == 'Team Lead' ? 'selected' : '' }}>Team Lead</option>
                            <option value="Executive" {{ old('role') == 'Executive' ? 'selected' : '' }}>Executive</option>
                        </select>
                    </div>

                    <div class="input-group-item">
                        <div class="label-row"><label>Password*</label></div>
                        <input type="password" name="password" class="pill-field" placeholder="Enter password" required>
                    </div>

                    <div class="input-group-item">
                        <div class="label-row"><label>Confirm Password*</label></div>
                        <input type="password" name="password_confirmation" class="pill-field" placeholder="Confirm password" required>
                    </div>

                    <div class="input-group-item">
                        <div class="label-row"><label>Phone*</label></div>
                        <input type="text" name="phone" class="pill-field" placeholder="Enter Phone number" value="{{ old('phone') }}" required>
                    </div>

                    <div class="input-group-item">
                        <div class="label-row"><label>Aadhar Card Front</label></div>
                        <input type="file" name="aadhar_front" class="pill-field" accept=".pdf, image/*">
                    </div>

                    <div class="input-group-item">
                        <div class="label-row"><label>Aadhar Card Back</label></div>
                        <input type="file" name="aadhar_back" class="pill-field" accept=".pdf, image/*">
                    </div>

                    <div class="input-group-item">
                        <div class="label-row"><label>Pan Card Front</label></div>
                        <input type="file" name="pan_card_front" class="pill-field" accept=".pdf, image/*">
                    </div>

                    <div class="input-group-item">
                        <div class="label-row"><label>Pan Card Back</label></div>
                        <input type="file" name="pan_card_back" class="pill-field" accept=".pdf, image/*">
                    </div>

                    <div class="input-group-item">
                        <div class="label-row"><label>Resume Attach</label></div>
                        <input type="file" name="resume" class="pill-field" accept=".pdf, image/*">
                    </div>

                    <div class="input-group-item">
                        <div class="label-row"><label>Police Verification</label></div>
                        <input type="file" name="police_verification" class="pill-field" accept=".pdf, image/*">
                    </div>

                    <div class="input-group-item">
                        <div class="label-row"><label>User Verification Document</label></div>
                        <input type="file" name="user_verification_document" class="pill-field" accept=".pdf, image/*">
                    </div>

                    <div class="input-group-item full-span">
                        <div class="label-row"><label>Address</label></div>
                        <textarea name="address" rows="2" class="pill-field" placeholder="Enter full address">{{ old('address') }}</textarea>
                    </div>

                    <div class="actions-row">
                        <a href="{{ route('signin.index') }}" class="back-link">
                            <i class="fa-solid fa-arrow-left"></i> Back to Sign In
                        </a>
                        <button type="submit" class="btn-submit-action">
                            <span>Create User</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
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

</body>
</html>
