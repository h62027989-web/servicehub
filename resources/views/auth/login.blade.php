<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login - ServiceHub</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/servicehub.css') }}">
</head>
<body>
<div class="auth">
    <div class="auth-visual">
        <span class="eyebrow" style="color:#C9CBFF">SERVICEHUB</span>
        <h2 class="animate-fade-up">Book trusted local professionals in a few taps.</h2>
        <p class="animate-fade-up">From home cleaning to repairs, ServiceHub connects you with verified providers and transparent pricing.</p>
        <div class="auth-points animate-fade-up">
            <div><span>✓</span> Verified, rated professionals</div>
            <div><span>🔒</span> Secure checkout on every booking</div>
            <div><span>⚡</span> Book, track and manage in one place</div>
        </div>
    </div>

    <div class="auth-panel">
        <div class="auth-card animate-fade-up">
            <div class="brand center"><span>S</span>ServiceHub</div>
            <h1 class="center">Welcome back</h1>
            <p class="body-text center">Login to manage your service bookings.</p>

            @if($errors->any())
                <div class="alert error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                </label>
                <label>Password
                    <div class="input-wrap">
                        <input type="password" name="password" id="loginPassword" autocomplete="current-password" required>
                        <button type="button" class="input-toggle" data-toggle-password="loginPassword" aria-label="Show password">👁</button>
                    </div>
                </label>
                <label class="check"><input type="checkbox" name="remember"> Remember me</label>
                <button class="primary full" type="submit">Login</button>
            </form>

            <p class="center body-text" style="margin-top:18px">Don't have an account? <a href="{{ route('register') }}" style="color:var(--primary);font-weight:700">Register</a></p>
        </div>
    </div>
</div>
<script src="{{ asset('js/servicehub.js') }}"></script>
</body>
</html>
