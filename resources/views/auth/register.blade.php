<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Register - ServiceHub</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/servicehub.css') }}">
</head>
<body>
<div class="auth">
    <div class="auth-visual">
        <span class="eyebrow" style="color:#C9CBFF">SERVICEHUB</span>
        <h2 class="animate-fade-up">Create your account and start booking in minutes.</h2>
        <p class="animate-fade-up">Join ServiceHub to discover trusted professionals for cleaning, repairs, beauty and more.</p>
        <div class="auth-points animate-fade-up">
            <div><span>✓</span> Free to join, no hidden fees</div>
            <div><span>📅</span> Flexible scheduling</div>
            <div><span>⭐</span> Rated by real customers</div>
        </div>
    </div>

    <div class="auth-panel">
        <div class="auth-card animate-fade-up">
            <div class="brand center"><span>S</span>ServiceHub</div>
            <h1 class="center">Create account</h1>
            <p class="body-text center">Start booking trusted local services.</p>

            @if($errors->any())
                <div class="alert error">
                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}">
                @csrf
                <label>Name
                    <input name="name" value="{{ old('name') }}" autocomplete="name" required>
                </label>
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                </label>
                <label>Phone
                    <input name="phone" value="{{ old('phone') }}" autocomplete="tel">
                </label>
                <label>City
                    <input name="city" value="{{ old('city','Surat') }}" autocomplete="address-level2">
                </label>
                <label>Password
                    <div class="input-wrap">
                        <input type="password" name="password" id="regPassword" autocomplete="new-password" required>
                        <button type="button" class="input-toggle" data-toggle-password="regPassword" aria-label="Show password">👁</button>
                    </div>
                </label>
                <label>Confirm Password
                    <div class="input-wrap">
                        <input type="password" name="password_confirmation" id="regPasswordConfirm" autocomplete="new-password" required>
                        <button type="button" class="input-toggle" data-toggle-password="regPasswordConfirm" aria-label="Show password">👁</button>
                    </div>
                </label>
                <button class="primary full" type="submit">Create Account</button>
            </form>

            <p class="center body-text" style="margin-top:18px">Already registered? <a href="{{ route('login') }}" style="color:var(--primary);font-weight:700">Login</a></p>
        </div>
    </div>
</div>
<script src="{{ asset('js/servicehub.js') }}"></script>
</body>
</html>
