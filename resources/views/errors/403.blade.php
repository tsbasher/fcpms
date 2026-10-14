@php
    $dashboardUrl = null;
    if (Auth::guard('admin')->check()) {
        $dashboardUrl = route('admin.home');
    } elseif (Auth::guard('web')->check()) {
        $dashboardUrl = route('user.home');
    }
    $message = isset($exception) && $exception->getMessage() ? $exception->getMessage() : null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Denied</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            background: #f4f6f9;
            color: #343a40;
            padding: 24px;
        }
        .card {
            max-width: 520px;
            width: 100%;
            background: #fff;
            border-radius: 16px;
            padding: 40px 32px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }
        .card img {
            max-width: 280px;
            width: 100%;
            height: auto;
            margin-bottom: 24px;
        }
        .card h1 {
            font-size: 26px;
            margin: 0 0 10px;
            color: #dc3545;
        }
        .card p {
            font-size: 15px;
            line-height: 1.6;
            color: #6c757d;
            margin: 0 0 28px;
        }
        .btn {
            display: inline-block;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            padding: 12px 22px;
            border-radius: 8px;
            margin: 6px;
            transition: .2s;
            border: 1px solid transparent;
            cursor: pointer;
        }
        .btn-primary { background: #007bff; color: #fff; }
        .btn-primary:hover { background: #0069d9; }
        .btn-light { background: #e9ecef; color: #343a40; }
        .btn-light:hover { background: #dfe3e8; }
    </style>
</head>
<body>
    <div class="card">
        <img src="{{ asset('backend/dist/img/access_denied.png') }}" alt="Access Denied">
        <h1>Access Denied</h1>
        <p>{{ $message ?: "You don't have permission to access this page. Please contact your administrator if you believe this is a mistake." }}</p>
        <div>
            @if ($dashboardUrl)
                <a href="{{ $dashboardUrl }}" class="btn btn-primary">Back to Dashboard</a>
            @endif
            <a href="javascript:history.back()" class="btn btn-light">Go Back</a>
        </div>
    </div>
</body>
</html>
