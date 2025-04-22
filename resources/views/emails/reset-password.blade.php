<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .button {
            display: inline-block;
            background-color: #3B82F6;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            font-size: 12px;
            text-align: center;
            color: #666;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reset Your Password</h1>
        </div>
        
        <p>Hello {{ $name }},</p>
        <p>You are receiving this email because we received a password reset request for your account.</p>
        
        <div style="text-align: center;">
            <a href="{{ $resetLink }}" style="background-color: #3B82F6; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block;">Reset Password</a>
        </div>
        
        <p>If you did not request a password reset, no further action is required.</p>
        <p>This password reset link will expire in 60 minutes.</p>
        
        <p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
        <p style="word-break: break-all;">{{ $resetLink }}</p>
        
        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
