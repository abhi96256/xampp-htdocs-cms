<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: #1a1a2e; color: #fff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p  { margin: 5px 0 0; color: #aaa; font-size: 13px; }
        .body { padding: 30px; color: #333; }
        .body p { line-height: 1.7; }
        .note-box { background: #f0f7ff; border-left: 4px solid #007bff; padding: 15px 20px; border-radius: 4px; margin: 20px 0; font-style: italic; color: #444; }
        .btn { display: inline-block; background: #007bff; color: #fff; padding: 12px 28px; border-radius: 6px; text-decoration: none; font-weight: bold; margin: 20px 0; }
        .footer { background: #f8f9fa; text-align: center; padding: 15px; font-size: 12px; color: #999; }
        .doc-icon { font-size: 48px; text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📄 Document Shared With You</h1>
        <p>{{ config('app.name') }}</p>
    </div>
    <div class="body">
        <p>Dear <strong>{{ $agreement->user->name ?? 'User' }}</strong>,</p>
        <p>A new document has been shared with you by the admin. Please find it attached to this email.</p>

        @if($note)
        <div class="note-box">
            <strong>📝 Note from Admin:</strong><br>
            {{ $note }}
        </div>
        @endif

        <p>You can also download the document directly by clicking the button below:</p>

        <div style="text-align:center;">
            <a href="{{ $downloadUrl }}" class="btn">⬇️ Download Document</a>
        </div>

        <p>If you have any questions, please contact your manager.</p>
        <p>Regards,<br><strong>{{ config('app.name') }} Team</strong></p>
    </div>
    <div class="footer">
        This is an automated email. Please do not reply directly to this message.
    </div>
</div>
</body>
</html>
