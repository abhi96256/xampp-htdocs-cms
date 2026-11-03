<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Your Allotment Letter</title>
    <style>
        body { margin: 0; padding: 0; background: #f4f6f9; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.10); }
        .header { background: linear-gradient(135deg, #1a1a2e 0%, #c0392b 100%); padding: 36px 40px; text-align: center; }
        .header img { height: 48px; margin-bottom: 16px; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; letter-spacing: 0.5px; }
        .header p  { color: rgba(255,255,255,0.8); margin: 8px 0 0; font-size: 14px; }
        .body { padding: 40px; color: #333; }
        .body h2 { font-size: 20px; margin: 0 0 12px; color: #1a1a2e; }
        .body p  { font-size: 15px; line-height: 1.7; margin: 0 0 16px; color: #555; }
        .otp-box { background: #f0f4ff; border: 2px dashed #4a6fa5; border-radius: 10px; padding: 20px; text-align: center; margin: 24px 0; }
        .otp-box .label { font-size: 13px; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .otp-box .otp  { font-size: 42px; font-weight: 800; letter-spacing: 12px; color: #c0392b; font-family: 'Courier New', monospace; }
        .otp-box .expires { font-size: 12px; color: #999; margin-top: 10px; }
        .btn-wrap { text-align: center; margin: 28px 0 16px; }
        .btn { display: inline-block; background: linear-gradient(135deg, #c0392b, #922b21); color: #fff !important; text-decoration: none; padding: 14px 36px; border-radius: 8px; font-size: 16px; font-weight: 700; letter-spacing: 0.5px; }
        .info-box { background: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 6px; padding: 14px 18px; font-size: 13px; color: #92400e; margin-bottom: 20px; }
        .steps { background: #f9fafb; border-radius: 8px; padding: 20px 24px; margin-bottom: 20px; }
        .steps ol { margin: 0; padding-left: 20px; color: #555; font-size: 14px; line-height: 2; }
        .footer { background: #f4f6f9; padding: 24px 40px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #e8e8e8; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <p>Digital Allotment Letter — Action Required</p>
    </div>

    <div class="body">
        <h2>Hello, {{ $recipientName }}!</h2>
        <p>You have been sent an <strong>Allotment Letter</strong> ({{ ucwords(str_replace('_', ' ', $agreement->agreement_type)) }}) from the management. To digitally sign it, please follow the steps below.</p>

        <div class="otp-box">
            <div class="label">Your One-Time Password (OTP)</div>
            <div class="otp">{{ $agreement->otp_code }}</div>
            <div class="expires">⏱ Valid for 24 hours — Expires at {{ $agreement->otp_expires_at->format('d M Y, h:i A') }}</div>
        </div>

        <div class="btn-wrap">
            <a href="{{ $signUrl }}" class="btn">📝 View & Sign Letter</a>
        </div>

        <div class="info-box">
            ⚠️ <strong>Important:</strong> This link expires in 24 hours. You will need to enter the OTP above on the signing page to confirm your consent.
        </div>

        <div class="steps">
            <strong>How to sign:</strong>
            <ol>
                <li>Click the <em>"View & Sign Letter"</em> button above</li>
                <li>Read your allotment letter carefully</li>
                <li>Enter the OTP shown above</li>
                <li>Click <em>"I Agree & Sign Digitally"</em></li>
            </ol>
        </div>

        <p>If you did not expect this email, please ignore it or contact the admin team.</p>
    </div>

    <div class="footer">
        This is an automated message from {{ config('app.name') }}. Please do not reply to this email.<br>
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
</div>
</body>
</html>
