<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Forgot Password - FYPMS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
html,body{height:100%}
body{font-family:'Segoe UI',sans-serif;background:#f0f4f8;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:16px}
.card{width:100%;max-width:440px;background:#fff;border-radius:18px;box-shadow:0 20px 60px rgba(12,68,124,.18);padding:40px 36px}
.card h2{color:#0C447C;font-size:22px;font-weight:700;margin-bottom:6px}
.card p{color:#8096b0;font-size:13px;margin-bottom:24px;line-height:1.5}
label{display:block;color:#0C447C;font-size:13px;font-weight:600;margin-bottom:6px}
input[type=email]{width:100%;padding:12px 14px;border:1.5px solid #e0e9f2;border-radius:10px;font-size:14px;margin-bottom:20px;outline:none}
input[type=email]:focus{border-color:#0C447C}
.btn{width:100%;padding:13px;background:#0C447C;color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer}
.btn:hover{background:#185FA5}
.alert{padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:20px}
.alert-success{background:#e8f5e9;color:#2e7d32}
.alert-error{background:#fce4e4;color:#c62828}
.back-link{display:block;text-align:center;margin-top:20px;color:#0C447C;font-size:13px;text-decoration:none;font-weight:600}
</style>
</head>
<body>
<div class="card">
    <h2>Forgot Password?</h2>
    <p>Enter your registered email address. Your Focal Person will be notified and will issue you a new password.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.request.submit') }}">
        @csrf
        <label>Email Address</label>
        <input type="email" name="email" placeholder="your.email@theluna.online" required value="{{ old('email') }}">
        <button type="submit" class="btn">Send Reset Request</button>
    </form>

    <a href="{{ route('login') }}" class="back-link">&larr; Back to Login</a>
</div>
</body>
</html>
