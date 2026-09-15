@extends('layouts.app')
@section('title','Dashboard - ServiceHub')

@section('content')

@if(auth()->user()->role==='customer')

    <div class="hero reveal">
        <div>
            <span class="eyebrow">FAST • TRUSTED • LOCAL</span>
            <h2>Book trusted professionals for your everyday needs.</h2>
            <p>Find verified providers, compare services and schedule a booking in a few taps.</p>
            <a class="primary" href="{{ route('services.index') }}">Explore Services →</a>
        </div>
        <div class="hero-icon">🛠️</div>
    </div>

    <div class="stats">
        <div class="reveal"><small>Upcoming</small><b data-counter="{{ $upcoming->count() }}">0</b></div>
        <div class="reveal"><small>Completed</small><b data-counter="{{ $completed }}">0</b></div>
        <div class="reveal"><small>Total Spent</small><b data-counter="{{ $spent }}" data-prefix="₹" data-decimals="2">₹0</b></div>
    </div>

    <h2 class="section-title">Upcoming bookings</h2>
    @forelse($upcoming as $b)
        <div class="card row reveal">
            <div class="service-icon">{{ $b->service->category->icon ?? '🛠️' }}</div>
            <div class="grow">
                <b>{{ $b->service->name }}</b>
                <small>{{ $b->provider->name }} • {{ $b->booking_date->format('d M Y') }} {{ $b->booking_time }}</small>
            </div>
            <span class="badge {{ $b->status }}">{{ ucfirst($b->status) }}</span>
            <a class="small-btn" href="{{ route('bookings.show',$b) }}">View</a>
        </div>
    @empty
        <div class="empty">
            <div class="empty-icon">📅</div>
            No upcoming bookings. <a href="{{ route('services.index') }}">Book a service</a>.
        </div>
    @endforelse

    <h2 class="section-title" style="margin-top:36px">How it works</h2>
    <div class="strip-grid cols-3">
        <div class="strip-card reveal">
            <div class="strip-num">1</div>
            <h4>Choose a service</h4>
            <p>Browse categories and pick the service that fits your need.</p>
        </div>
        <div class="strip-card reveal">
            <div class="strip-num">2</div>
            <h4>Select date & time</h4>
            <p>Pick a slot that works for you and share your address.</p>
        </div>
        <div class="strip-card reveal">
            <div class="strip-num">3</div>
            <h4>Pay & get it done</h4>
            <p>Complete secure checkout and your provider takes it from there.</p>
        </div>
    </div>

    <h2 class="section-title" style="margin-top:36px">Why choose ServiceHub</h2>
    <div class="strip-grid">
        <div class="strip-card reveal">
            <div class="strip-icon">✅</div>
            <h4>Trusted professionals</h4>
            <p>Every provider is verified before joining the platform.</p>
        </div>
        <div class="strip-card reveal">
            <div class="strip-icon">💰</div>
            <h4>Transparent pricing</h4>
            <p>See the full price upfront — no surprise charges.</p>
        </div>
        <div class="strip-card reveal">
            <div class="strip-icon">🔒</div>
            <h4>Secure payments</h4>
            <p>Checkout is encrypted and protected end-to-end.</p>
        </div>
        <div class="strip-card reveal">
            <div class="strip-icon">📱</div>
            <h4>Easy booking</h4>
            <p>Schedule, track and manage every booking from one dashboard.</p>
        </div>
    </div>

@elseif(auth()->user()->role==='provider')

    <div class="hero reveal">
        <div>
            <span class="eyebrow">PROVIDER DASHBOARD</span>
            <h2>Grow your service business.</h2>
            <p>Manage your services, booking requests and earnings from one place.</p>
            <a class="primary" href="{{ route('provider.services.index') }}">Manage Services</a>
        </div>
        <div class="hero-icon">⚒️</div>
    </div>

    <div class="stats">
        <div class="reveal"><small>My Services</small><b data-counter="{{ $services->count() }}">0</b></div>
        <div class="reveal"><small>Bookings</small><b data-counter="{{ $bookings->count() }}">0</b></div>
        <div class="reveal"><small>Earnings</small><b data-counter="{{ $earnings }}" data-prefix="₹" data-decimals="2">₹0</b></div>
    </div>

    <h2 class="section-title">Recent requests</h2>
    @forelse($bookings->take(8) as $b)
        <div class="card row reveal">
            <div class="service-icon">🛠️</div>
            <div class="grow">
                <b>{{ $b->service->name }}</b>
                <small>{{ $b->customer->name }} • {{ $b->booking_date->format('d M Y') }}</small>
            </div>
            <span class="badge {{ $b->status }}">{{ ucfirst($b->status) }}</span>
        </div>
    @empty
        <div class="empty">
            <div class="empty-icon">📋</div>
            No booking requests yet.
        </div>
    @endforelse

@else

    <div class="hero reveal">
        <div>
            <span class="eyebrow">ADMIN CONTROL CENTER</span>
            <h2>Platform overview.</h2>
            <p>Manage users, services, providers, categories and bookings.</p>
        </div>
        <div class="hero-icon">▣</div>
    </div>

    <div class="stats four">
        <div class="reveal"><small>Total Users</small><b data-counter="{{ $users }}">0</b></div>
        <div class="reveal"><small>Providers</small><b data-counter="{{ $providers }}">0</b></div>
        <div class="reveal"><small>Bookings</small><b data-counter="{{ $bookings }}">0</b></div>
        <div class="reveal"><small>Revenue</small><b data-counter="{{ $revenue }}" data-prefix="₹" data-decimals="2">₹0</b></div>
    </div>

    <h2 class="section-title">Quick actions</h2>
    <div class="quick-grid">
        <a href="{{ route('admin.categories.index') }}">Manage Categories <span>→</span></a>
        <a href="{{ route('admin.services.index') }}">Manage Services <span>→</span></a>
        <a href="{{ route('admin.users.index') }}">Manage Users <span>→</span></a>
        <a href="{{ route('admin.bookings.index') }}">Manage Bookings <span>→</span></a>
    </div>

@endif

@endsection
