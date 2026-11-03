<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email OTP | A2P Realtech CRM</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary: #e62e2d;
            --primary-dark: #cc0000;
            --dark: #121212;
            --white: #ffffff;
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
            --text: #333333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url("{{ asset('imgs/V1.jpeg') }}") center/cover no-repeat fixed;
            color: var(--text);
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(0, 74, 153, 0.7) 0%, rgba(230, 46, 45, 0.5) 100%);
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 500px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--glass-shadow);
            background: var(--glass-bg);
            animation: fadeIn 0.8s ease-out;
            padding: 40px;
            text-align: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-header img {
            max-height: 70px;
            margin-bottom: 15px;
        }

        .form-header h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8rem;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .form-header p {
            color: #666;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .input-group {
            margin-bottom: 25px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            color: #333;
            text-transform: uppercase;
        }

        .input-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e1e5ee;
            border-radius: 10px;
            font-size: 1.1rem;
            text-align: center;
            letter-spacing: 5px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .input-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(230, 46, 45, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(230, 46, 45, 0.3);
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(230, 46, 45, 0.4);
        }
        
        #error-msg {
            color: #dc2626;
            background: #fef2f2;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="form-header">
            <img src="{{ asset('imgs/logo.png') }}" alt="A2P Realtech Logo">
            <h2>Verify Your Email</h2>
            <p>An OTP has been sent to your email <strong>{{ session('register_email') }}</strong></p>
        </div>

        <div id="error-msg"></div>

        <form id="otpForm">
            @csrf
            <div class="input-group">
                <label>Enter 6-Digit OTP</label>
                <input type="text" id="otp" name="otp" maxlength="6" required autofocus>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">Verify Profile</button>
        </form>
    </div>

    <script>
        document.getElementById('otpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('submitBtn');
            const errorMsg = document.getElementById('error-msg');
            const otp = document.getElementById('otp').value;
            const token = document.querySelector('input[name="_token"]').value;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
            errorMsg.style.display = 'none';

            fetch("{{ route('register.otp.verify') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token
                },
                body: JSON.stringify({ otp: otp })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Email verified! Your account is now Pending Admin Approval. You can login once an admin has accepted your registration.',
                        icon: 'success',
                        timer: 5000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "{{ route('signin.index') }}";
                    });
                } else {
                    errorMsg.innerHTML = data.message || 'Invalid OTP. Please try again.';
                    errorMsg.style.display = 'block';
                    btn.disabled = false;
                    btn.innerHTML = 'Verify Profile';
                }
            })
            .catch(error => {
                errorMsg.innerHTML = 'Something went wrong. Please try again.';
                errorMsg.style.display = 'block';
                btn.disabled = false;
                btn.innerHTML = 'Verify Profile';
            });
        });
    </script>
</body>
</html>
