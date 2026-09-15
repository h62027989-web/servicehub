<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title','ServiceHub')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/servicehub.css').'?v=20260914' }}">
</head>
<body>
<div class="scroll-progress" id="scrollProgress"></div>
<div class="shell">
    <div class="sidebar-overlay"></div>

    <aside class="sidebar">
        <a class="brand" href="{{ route('dashboard') }}"><span>S</span>ServiceHub</a>
        <div class="role-pill">{{ strtoupper(auth()->user()->role) }} ACCOUNT</div>

        <nav>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="ic">⌂</span>Dashboard</a>
            <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}"><span class="ic">▦</span>Services</a>

            @if(auth()->user()->role==='customer')
                <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.*') ? 'active' : '' }}"><span class="ic">◷</span>My Bookings</a>
            @endif

            @if(auth()->user()->role==='provider')
                <a href="{{ route('provider.services.index') }}" class="{{ request()->routeIs('provider.services.*') ? 'active' : '' }}"><span class="ic">⚒</span>My Services</a>
                <a href="{{ route('provider.bookings.index') }}" class="{{ request()->routeIs('provider.bookings.*') ? 'active' : '' }}"><span class="ic">◷</span>Requests</a>
            @endif

            @if(auth()->user()->role==='admin')
                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><span class="ic">▣</span>Categories</a>
                <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}"><span class="ic">▦</span>Manage Services</a>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><span class="ic">◯</span>Users</a>
                <a href="{{ route('admin.bookings.index') }}" class="{{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}"><span class="ic">◷</span>Bookings</a>
            @endif
        </nav>

        <div class="sidebar-foot">
            <form method="POST" action="{{ route('logout') }}" data-no-loading>
                @csrf
                <button class="logout-btn" type="submit">↪ Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-btn" type="button" aria-label="Open menu">☰</button>
                <div>
                    <small class="eyebrow">SERVICE BOOKING PLATFORM</small>
                    <h1>@yield('heading','Welcome back, '.auth()->user()->name.' 👋')</h1>
                </div>
            </div>
            <div class="topbar-right">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
            </div>
        </header>

        <section class="content">
            @if(session('success'))<div data-flash="{{ session('success') }}" data-flash-type="success" style="display:none"></div>@endif
            @if(session('error'))<div data-flash="{{ session('error') }}" data-flash-type="error" style="display:none"></div>@endif
            @if($errors->any())
                <div class="alert error">
                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            @yield('content')
        </section>
    </main>
</div>

<div class="toast-stack" id="toastStack"></div>
<script src="{{ asset('js/servicehub.js') }}"></script>
</body>
</html>
