<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Your Allotment Letter — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #f0f4f8; font-family: 'Inter', sans-serif; min-height: 100vh; padding: 30px 16px; }
        .container { max-width: 760px; margin: 0 auto; }

        .header { background: linear-gradient(135deg, #1a1a2e 0%, #c0392b 100%); border-radius: 16px 16px 0 0; padding: 32px 40px; text-align: center; color: #fff; }
        .header h1 { font-size: 22px; font-weight: 800; }
        .header p  { opacity: 0.8; font-size: 14px; margin-top: 6px; }

        .doc-card { background: #fff; padding: 40px; border-left: 5px solid #c0392b; margin: 0; }
        .doc-card h2 { font-size: 18px; color: #1a1a2e; margin-bottom: 24px; border-bottom: 2px solid #f0f4f8; padding-bottom: 14px; }
        .doc-row { display: flex; margin-bottom: 14px; }
        .doc-row .lbl { width: 180px; font-weight: 600; color: #555; font-size: 14px; flex-shrink: 0; }
        .doc-row .val { color: #1a1a2e; font-size: 14px; }
        .agreement-text { background: #f9fafb; border-radius: 10px; padding: 24px; margin: 24px 0; font-size: 14px; color: #444; line-height: 1.85; }
        .agreement-text strong { color: #1a1a2e; }

        .sign-card { background: #fff; padding: 36px 40px; border-radius: 0 0 16px 16px; border-top: 3px solid #e8e8e8; }
        .sign-card h3 { font-size: 17px; color: #1a1a2e; margin-bottom: 20px; }
        .otp-input-wrap { display: flex; gap: 12px; align-items: center; margin-bottom: 20px; }
        .otp-input { font-size: 28px; font-weight: 800; letter-spacing: 10px; text-align: center; border: 2px solid #ddd; border-radius: 10px; padding: 14px 20px; width: 100%; font-family: 'Courier New', monospace; color: #1a1a2e; transition: border 0.2s; }
        .otp-input:focus { outline: none; border-color: #c0392b; box-shadow: 0 0 0 3px rgba(192,57,43,0.1); }
        .error-msg { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; border-radius: 8px; padding: 12px 16px; font-size: 14px; margin-bottom: 16px; }
        .btn-sign { width: 100%; background: linear-gradient(135deg, #c0392b, #922b21); color: #fff; border: none; border-radius: 10px; padding: 16px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.3s; }
        .btn-sign:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(192,57,43,0.35); }
        .expires-note { font-size: 12px; color: #999; margin-top: 14px; text-align: center; }
        .checkbox-wrap { display: flex; gap: 10px; align-items: flex-start; margin-bottom: 20px; background: #f9fafb; border-radius: 8px; padding: 14px; }
        .checkbox-wrap input { margin-top: 3px; width: 18px; height: 18px; }
        .checkbox-wrap label { font-size: 13px; color: #555; line-height: 1.6; }
        
        .signature-area { margin-bottom: 25px; }
        .signature-container { border: 2px dashed #ccc; border-radius: 10px; background: #fafafa; position: relative; overflow: hidden; margin-top: 10px; }
        canvas { display: block; width: 100%; height: 200px; cursor: crosshair; }
        .clear-btn { position: absolute; bottom: 10px; right: 10px; background: rgba(0,0,0,0.5); color: #fff; border: none; padding: 5px 10px; text-transform: uppercase; font-size: 10px; border-radius: 4px; cursor: pointer; }
        .clear-btn:hover { background: rgba(0,0,0,0.8); }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <p>Secure Digital Allotment Letter — Review & Sign</p>
    </div>

    <div class="doc-card">
        <h2>📄 Allotment Letter</h2>
        <div class="doc-row"><span class="lbl">👤 Employee Name:</span><span class="val"><strong>{{ $agreement->user->name }}</strong></span></div>
        <div class="doc-row"><span class="lbl">📧 Email:</span><span class="val">{{ $agreement->user->email }}</span></div>
        <div class="doc-row"><span class="lbl">📱 Phone:</span><span class="val">{{ $agreement->user->phone ?? 'N/A' }}</span></div>
        <div class="doc-row"><span class="lbl">📝 Agreement Type:</span>
            <span class="val">
                @php $types = ['salary'=>'Salary Based Employee','commission'=>'Commission Based Employee','channel_partner'=>'Channel Partner']; @endphp
                <strong>{{ $types[$agreement->agreement_type] ?? $agreement->agreement_type }}</strong>
            </span>
        </div>
        <div class="doc-row"><span class="lbl">📅 Date:</span><span class="val">{{ $agreement->created_at->format('d M Y') }}</span></div>

        <div class="agreement-text">
            <p>This letter is to confirm that <strong>{{ $agreement->user->name }}</strong> has been engaged by
            <strong>{{ config('app.name') }}</strong> in the capacity of a
            <strong>{{ $types[$agreement->agreement_type] ?? $agreement->agreement_type }}</strong>.</p>
            <br>
            @if($agreement->agreement_type === 'salary')
            <p>The employee agrees to the terms of their fixed salary arrangement, including reporting structure, working hours, and company policies as communicated by the management.</p>
            @elseif($agreement->agreement_type === 'commission')
            <p>The employee acknowledges and agrees to the commission-based earning structure as communicated by the management. Commissions shall be calculated and disbursed as per the mutually agreed commission policy.</p>
            @else
            <p>The Channel Partner acknowledges the terms of the partnership agreement, including referral commissions, data privacy obligations, and company representation policies.</p>
            @endif
            <br>
            <p>By digitally signing this letter with the OTP provided, you confirm that you have read, understood, and agreed to all the stated terms with your full consent.</p>
        </div>
    </div>

    <div class="sign-card">
        <h3>✍️ Digitally Sign this Document</h3>

        @if($errors->any())
        <div class="error-msg">❌ {{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('agreements.submit', $token) }}">
            @csrf
            <p style="font-size:14px; color:#555; margin-bottom:16px;">Enter the 6-digit OTP sent to <strong>{{ $agreement->user->email }}</strong></p>
            
            <div class="otp-input-wrap">
                <input type="text" name="otp" class="otp-input" placeholder="• • • • • •" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" required autofocus>
            </div>

            <div class="signature-area">
                <label style="font-size:14px; font-weight:600; color:#1a1a2e;">Draw Your Digital Signature <span style="color:#c0392b;">*</span></label>
                <div class="signature-container">
                    <canvas id="signaturePad"></canvas>
                    <button type="button" class="clear-btn" id="clearBtn">Clear</button>
                </div>
                <!-- Hidden input to store base64 string -->
                <input type="hidden" name="signature" id="signatureInput" required>
            </div>

            <div class="checkbox-wrap">
                <input type="checkbox" id="consent" required>
                <label for="consent">I, <strong>{{ $agreement->user->name }}</strong>, confirm that I have read and understood this allotment letter and I voluntarily and digitally sign this document with my full consent. I understand this is legally binding.</label>
            </div>

            <button type="submit" class="btn-sign">✅ I Agree & Sign Digitally</button>
            <p class="expires-note">⏱ OTP valid until {{ $agreement->otp_expires_at->format('d M Y, h:i A') }}</p>
        </form>
    </div>
</div>

<script>
    const canvas = document.getElementById('signaturePad');
    const ctx = canvas.getContext('2d');
    const clearBtn = document.getElementById('clearBtn');
    const signatureInput = document.getElementById('signatureInput');
    const form = document.querySelector('form');

    // Resize canvas
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        ctx.scale(ratio, ratio);
        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.strokeStyle = '#000000';
    }
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    let isDrawing = false;
    let lastX = 0; let lastY = 0;
    
    function getPointerPos(e) {
        const rect = canvas.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return { x: clientX - rect.left, y: clientY - rect.top };
    }

    function startDraw(e) {
        e.preventDefault();
        isDrawing = true;
        const pos = getPointerPos(e);
        lastX = pos.x; lastY = pos.y;
    }

    function draw(e) {
        if(!isDrawing) return;
        e.preventDefault();
        const pos = getPointerPos(e);
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        lastX = pos.x; lastY = pos.y;
    }

    function stopDraw(e) { 
        if(isDrawing) { e.preventDefault(); isDrawing = false; } 
    }

    canvas.addEventListener('mousedown', startDraw);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDraw);
    canvas.addEventListener('mouseout', stopDraw);

    canvas.addEventListener('touchstart', startDraw, { passive: false });
    canvas.addEventListener('touchmove', draw, { passive: false });
    canvas.addEventListener('touchend', stopDraw, { passive: false });

    clearBtn.addEventListener('click', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        signatureInput.value = '';
    });

    // Check if canvas has content
    function isCanvasBlank() {
        const blank = document.createElement('canvas');
        blank.width = canvas.width;
        blank.height = canvas.height;
        return canvas.toDataURL() === blank.toDataURL();
    }

    form.addEventListener('submit', function(e) {
        if(isCanvasBlank()) {
            e.preventDefault();
            alert('Please provide your digital signature before agreeing.');
            return false;
        }
        // Save the signature to the hidden input
        signatureInput.value = canvas.toDataURL('image/png');
    });
</script>

</body>
</html>
