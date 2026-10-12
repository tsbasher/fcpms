<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 420px;
            margin: 40px auto;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 32px;
        }
        .logo {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }
        p {
            color: #374151;
            font-size: 15px;
            line-height: 1.6;
        }
        .button {
            display: block;
            width: 100%;
            text-align: center;
            margin: 24px 0;
        }
        .button a {
            display: inline-block;
            background-color: #343a40;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: 600;
        }
        .note {
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 16px;
            margin-top: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">FCPMS 2.0</div>
        <p>Hello,</p>
        <p>We received a request to reset your password. Click the button below to choose a new password.</p>
        <div class="button">
            <a href="{{ $url }}">Reset Password</a>
        </div>
        <p>This password reset link will expire in 60 minutes. If you did not request a password reset, no further action is required.</p>
        <div class="note">
            If you are having trouble clicking the button, copy and paste the URL below into your web browser:<br>
            <a href="{{ $url }}">{{ $url }}</a>
        </div>
    </div>
</body>
</html>