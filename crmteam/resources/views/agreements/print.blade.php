<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Allotment Letter - {{ $agreement->user->name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            margin: 0;
            padding: 0;
            background: #e9ecef;
        }
        .document-container {
            width: 210mm; /* A4 width */
            min-height: 297mm; /* A4 height */
            margin: 30px auto;
            padding: 25mm !important;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            box-sizing: border-box;
            position: relative;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 40px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #444;
        }
        .content {
            font-size: 14px;
            line-height: 1.8;
            text-align: justify;
        }
        .content p { margin-bottom: 15px; }
        .details-table {
            width: 100%;
            margin: 30px 0;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        .details-table td strong {
            display: inline-block;
            width: 150px;
        }
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .sign-box {
            width: 45%;
        }
        .digital-stamp {
            border: 2px dashed #28a745;
            color: #155724;
            background: #d4edda;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            font-family: monospace;
            font-size: 12px;
            transform: rotate(-2deg);
        }
        .digital-stamp strong { display: block; font-size: 14px; margin-bottom: 5px; }
        
        .author-sign {
            margin-top: 50px;
            border-top: 1px solid #000;
            padding-top: 10px;
            text-align: center;
            font-weight: bold;
        }

        .no-print {
            text-align: center;
            padding: 20px;
            background: #343a40;
            color: #fff;
        }
        .no-print button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
        }

        @media print {
            body { background: #fff; margin: 0; padding: 0; }
            .document-container { margin: 0; box-shadow: none; border: none; padding: 15mm 20mm !important; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="downloadAsPDF()" style="background:#28a745; margin-right:10px;">⬇️ Download PDF</button>
        <button onclick="window.print()">🖨️ Print</button>
        <p style="margin-top:10px; font-size:12px;">For Download: In the print dialog, choose "Save as PDF" as the destination.</p>
    </div>

    <div class="document-container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>Official Digital Allotment & Agreement Letter</p>
        </div>

        <div class="content">
            <p style="text-align: right;"><strong>Date:</strong> {{ $agreement->created_at->format('M d, Y') }}<br>
               <strong>Ref No:</strong> A2P-{{ str_pad($agreement->id, 5, '0', STR_PAD_LEFT) }}</p>

            <p><strong>To,</strong><br>
            {{ $agreement->user->name }}<br>
            {{ $agreement->user->email }}<br>
            {{ $agreement->user->phone ?? '' }}</p>

            <p><strong>Subject: Official Allotment and Engagement Agreement</strong></p>

            <p>Dear <strong>{{ $agreement->user->name }}</strong>,</p>

            <p>We are pleased to confirm your engagement with <strong>{{ config('app.name') }}</strong> in the official capacity of a 
                @php $types = ['salary'=>'Salary Based Employee','commission'=>'Commission Based Employee','channel_partner'=>'Channel Partner']; @endphp
                <strong>{{ $types[$agreement->agreement_type] ?? $agreement->agreement_type }}</strong>.
            </p>

            <p>This letter sets out the broad terms of your engagement. By digitally signing this letter, you accept the responsibilities and obligations associated with your role, including adherence to company policies, non-disclosure of confidential information, and maintaining professional conduct at all times.</p>

            @if($agreement->agreement_type === 'salary')
            <p>As a salaried employee, you will be subject to the standard working hours, reporting hierarchy, and the compensation structure mutually agreed upon. Statutory deductions as per government norms will apply.</p>
            @elseif($agreement->agreement_type === 'commission')
            <p>As a commission-based employee, your earnings will strictly depend on the successful execution and closure of assignments as per the commission structure outlined independently. You understand that this is a performance-based role.</p>
            @else
            <p>As a Channel Partner, you agree to act in the best interests of the company, referring genuine clients and honoring the terms of the referral commission agreements. You shall not misrepresent the company under any circumstances.</p>
            @endif

            <p>This agreement has been verified securely and mutually agreed upon through an OTP-based Digital Signature. It carries the same legal validity as a physically signed contract.</p>

            <div class="signature-section">
                <div class="sign-box">
                    <div class="digital-stamp">
                        <strong>✅ E-SIGNED SECURELY</strong>
                        By: {{ $agreement->user->name }}<br>
                        Email: {{ $agreement->user->email }}<br>
                        Time: {{ $agreement->signed_at->format('M d, Y h:i A') }}<br>
                        IP: {{ $agreement->ip_address }}<br>
                        Verified via OTP
                    </div>
                </div>

                <div class="sign-box" style="display:flex; align-items:flex-end; padding-top:10px;">
                    <div class="author-sign" style="width:100%; border-top:none; text-align:center;">
                        @if($agreement->signature)
                            <img src="{{ $agreement->signature }}" alt="User Signature" style="max-height: 80px; margin-bottom: 10px;">
                        @endif
                        <div style="border-top:1px solid #000; padding-top:10px; margin-top:5px; font-weight:bold;">
                            Signature & Consent<br>
                            {{ $agreement->user->name }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
<script>
function downloadAsPDF() {
    var oldTitle = document.title;
    document.title = 'Agreement_{{ $agreement->user->name ?? "doc" }}_{{ $agreement->id }}';
    window.print();
    document.title = oldTitle;
}

// Auto trigger download if ?download=1
@if(request()->get('download') == 1)
window.addEventListener('load', function() {
    setTimeout(function() {
        downloadAsPDF();
    }, 800);
});
@endif
</script>
</html>
