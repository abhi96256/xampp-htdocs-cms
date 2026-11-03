<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agreement Signed Successfully</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #f0f4f8; font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 30px; }
        .card { background: #fff; border-radius: 16px; padding: 60px 40px; max-width: 520px; width: 100%; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.10); }
        .icon { font-size: 72px; margin-bottom: 24px; }
        h1 { color: #1a1a2e; font-size: 26px; margin-bottom: 12px; }
        p { color: #666; font-size: 15px; line-height: 1.7; margin-bottom: 10px; }
        .badge { display: inline-block; background: #dcfce7; color: #166534; border-radius: 50px; padding: 8px 20px; font-size: 13px; font-weight: 700; margin: 20px 0; }
        .detail { background: #f9fafb; border-radius: 10px; padding: 16px 20px; text-align: left; font-size: 13px; color: #555; margin-top: 20px; }
        .detail div { margin-bottom: 6px; }
        .detail strong { color: #1a1a2e; }
    </style>
</head>
<body>
<div class="card">
    <div class="icon">✅</div>
    <h1>Document Signed Successfully!</h1>
    <p>Your digital signature has been recorded. This agreement is now legally confirmed.</p>
    <div class="badge">🔒 Digitally Verified</div>
    <div class="detail">
        <div><strong>👤 Name:</strong> {{ $agreement->user->name }}</div>
        <div><strong>📋 Type:</strong> {{ ucwords(str_replace('_', ' ', $agreement->agreement_type)) }}</div>
        <div><strong>🕐 Signed At:</strong> {{ $agreement->signed_at->format('d M Y, h:i:s A') }}</div>
        <div><strong>🌐 IP Address:</strong> {{ $agreement->ip_address }}</div>
    </div>
    <p style="margin-top:20px; font-size:13px; color:#999;">You may close this window. A record has been saved for your agreement.</p>
</div>
</body>
</html>
