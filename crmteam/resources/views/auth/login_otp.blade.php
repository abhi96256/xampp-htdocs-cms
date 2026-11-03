<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login OTP Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {font-family: 'Inter', sans-serif; background: #f4f7f9; display:flex; justify-content:center; align-items:center; height:100vh; margin:0;}
        .container {background:#fff; padding:2rem; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.1); width:320px;}
        h2 {margin-top:0; font-weight:600; color:#333;}
        .input-group {margin-bottom:1rem;}
        label {display:block; margin-bottom:0.5rem; color:#555;}
        input {width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:4px;}
        button {width:100%; padding:0.6rem; background:#0066ff; color:#fff; border:none; border-radius:4px; cursor:pointer; font-weight:600;}
        button:hover {background:#0052cc;}
        .message {margin-top:1rem; color:#d00;}
    </style>
</head>
<body>
<div class="container">
    <h2>Enter OTP</h2>
    @if(session('error'))
        <div class="message">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="message">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('login.otp.verify') }}">
        @csrf
        <div class="input-group">
            <label for="otp">One‑Time Password</label>
            <input type="text" name="otp" id="otp" required autocomplete="off" />
        </div>
        <button type="submit">Verify OTP</button>
    </form>
</div>
</body>
</html>
