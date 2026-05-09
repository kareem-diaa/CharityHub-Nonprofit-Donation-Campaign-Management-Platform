<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Donation Certificate</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; text-align: center; color: #333; }
        .certificate-container { border: 10px solid #6c5ce7; padding: 50px; margin: 20px; position: relative; }
        .header { color: #6c5ce7; font-size: 40px; margin-bottom: 20px; }
        .sub-header { font-size: 20px; margin-bottom: 40px; }
        .donor-name { font-size: 30px; font-weight: bold; border-bottom: 2px solid #333; display: inline-block; padding: 0 20px; margin: 20px 0; }
        .details { font-size: 18px; margin-top: 30px; line-height: 1.6; }
        .footer { margin-top: 50px; font-size: 14px; color: #777; }
        .stamp { position: absolute; bottom: 20px; right: 40px; opacity: 0.5; transform: rotate(-15deg); color: #6c5ce7; font-weight: bold; border: 4px solid #6c5ce7; padding: 10px; }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="header">Certificate of Appreciation</div>
        <div class="sub-header">This certificate is proudly presented to</div>
        
        <div class="donor-name">{{ $donor_name }}</div>
        
        <div class="details">
            In recognition of your generous donation of <strong>${{ number_format($amount, 2) }}</strong><br>
            to the campaign: <strong>{{ $campaign }}</strong><br>
            on {{ $date }}.
        </div>
        
        <div class="details" style="font-size: 14px; margin-top: 50px;">
            Your contribution helps us make a real impact in our community.<br>
            Thank you for being part of CharityHub.
        </div>

        <div class="stamp">VERIFIED BY CHARITYHUB</div>

        <div class="footer">
            Transaction ID: {{ $transaction_id }}<br>
            &copy; {{ date('Y') }} CharityHub Nonprofit Platform.
        </div>
    </div>
</body>
</html>
