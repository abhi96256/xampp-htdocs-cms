<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background: #343a40; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-box { background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); width: 100%; max-width: 400px; text-align: center; }
        h2 { margin-bottom: 20px; color: #333; }
        .form-group { margin-bottom: 15px; text-align: left; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { width: 100%; padding: 10px; background: #dc3545; color: #fff; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .btn:hover { background: #c82333; }
        .error { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Superadmin Portal</h2>
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
        <form action="{{ route('superadmin.postLogin') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Enter superadmin username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter password">
            </div>
            <button type="submit" class="btn">Login Securely</button>
        </form>
    </div>
</body>
</html>
