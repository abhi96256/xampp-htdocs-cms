<?php
error_reporting(0);
include('./function/function.php');

$umessage = '';
check_login();
if (isset($_POST['login_me'])) {
    $umessage = login_me();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Superadmin Login | <?php echo $siteTitle ?? 'Admin'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 50px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }
        .brand {
            text-align: center;
            margin-bottom: 35px;
        }
        .brand h1 {
            color: #fff;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .brand p {
            color: rgba(255,255,255,0.5);
            font-size: 13px;
        }
        .badge-super {
            display: inline-block;
            background: linear-gradient(135deg, #e53e3e, #c53030);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .alert-danger { background: rgba(229,62,62,0.15); color: #fc8181; border: 1px solid rgba(229,62,62,0.3); }
        .alert-success { background: rgba(72,187,120,0.15); color: #9ae6b4; border: 1px solid rgba(72,187,120,0.3); }
        .alert-info { background: rgba(66,153,225,0.15); color: #90cdf4; border: 1px solid rgba(66,153,225,0.3); }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            color: rgba(255,255,255,0.7);
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 13px 16px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 10px;
            color: #fff;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-control:focus { border-color: #e53e3e; }
        .form-control::placeholder { color: rgba(255,255,255,0.3); }
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #e53e3e, #c53030);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 5px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(229,62,62,0.4);
        }
        .footer-text {
            text-align: center;
            color: rgba(255,255,255,0.3);
            font-size: 12px;
            margin-top: 25px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">
            <div class="badge-super">SUPERADMIN</div>
            <h1>Welcome Back 👋</h1>
            <p>Sign in to your superadmin account</p>
        </div>

        <?php if (!empty($umessage)) echo $umessage; ?>

        <form method="post" action="">
            <div class="form-group">
                <label>Username</label>
                <input class="form-control" type="text" name="email" placeholder="Enter username" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input class="form-control" type="password" name="pass" placeholder="Enter password" required>
            </div>
            <button type="submit" name="login_me" class="btn-login">Sign In</button>
        </form>

        <div class="footer-text">&copy; <?php echo date('Y'); ?> <?php echo $siteTitle ?? 'Admin Panel'; ?></div>
    </div>
</body>
</html>