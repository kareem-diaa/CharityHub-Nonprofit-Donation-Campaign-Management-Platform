<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thank you for your donation!</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; background-color: #f8f9fa; color: #333; padding: 20px; }
        .container { background-color: #fff; max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 8px; border-top: 5px solid #6c5ce7; }
        .header { font-size: 24px; font-weight: bold; color: #6c5ce7; margin-bottom: 20px; }
        .details { background-color: #f1f2f6; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .details strong { display: inline-block; width: 120px; }
        .btn { display: inline-block; background-color: #6c5ce7; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; margin-top: 10px; }
        .footer { margin-top: 30px; font-size: 12px; color: #999; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Thank you, {{ $donation->user->name ?? 'Generous Donor' }}!</div>
        
        <p>Your contribution means the world to us. Your donation has been successfully processed, and we have attached your official Certificate of Appreciation to this email.</p>
        
        <div class="details">
            <strong>Campaign:</strong> {{ $donation->campaign->title }}<br>
            <strong>Amount:</strong> ${{ number_format($donation->amount, 2) }}<br>
            <strong>Date:</strong> {{ $donation->created_at->format('F j, Y') }}<br>
            <strong>Transaction ID:</strong> {{ $donation->transaction_id }}
        </div>
        
        <p>You can also download your certificate anytime from your CharityHub account.</p>
        
        <a href="{{ route('donations_certificate', $donation->id) }}" class="btn">Download Certificate</a>
        
        <div class="footer">
            &copy; {{ date('Y') }} CharityHub. All rights reserved.
        </div>
    </div>
</body>
</html>
