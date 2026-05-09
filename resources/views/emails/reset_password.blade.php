<!DOCTYPE html>
<html>
<head>
    <style>
        .button {
            background-color: #6c5ce7;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
        }
    </style>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #6c5ce7;">CharityHub Password Reset</h2>
        <p>Hello,</p>
        <p>You are receiving this email because we received a password reset request for your account.</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $resetLink }}" class="button" style="color: white;">Reset Password</a>
        </div>
        <p>If you did not request a password reset, no further action is required.</p>
        <hr style="border: 0; border-top: 1px solid #eee;">
        <p style="font-size: 12px; color: #777;">If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
        <p style="font-size: 12px; color: #777;">{{ $resetLink }}</p>
    </div>
</body>
</html>
