@extends('layouts.app')
@section('title','My Bookings - ServiceHub')
@section('heading','My Bookings')

@section('content')

<div class="page-head reveal">
    <div>
        <small class="eyebrow">CUSTOMER</small>
        <h2>Track your service appointments</h2>
    </div>
    <a class="primary" href="{{ route('services.index') }}">+ New Booking</a>
</div>

@forelse($bookings as $b)
    <div class="card row reveal">
        <div class="service-icon">{{ $b->service->category->icon ?? '🛠️' }}</div>
        <div class="grow">
            <h3 class="card-title">{{ $b->service->name }}</h3>
            <small>{{ $b->provider->name }} • {{ $b->booking_date->format('d M Y') }} • {{ $b->booking_time }}</small>
            <small>Amount: ₹{{ number_format($b->amount,2) }} • Payment: {{ ucfirst($b->payment_status) }}</small>
        </div>
        <div style="text-align:right">
            <span class="badge {{ $b->status }}">{{ ucfirst($b->status) }}</span><br>
            <a class="small-btn" style="margin-top:8px;display:inline-block" href="{{ route('bookings.show',$b) }}">Details</a>
        </div>
    </div>
@empty
    <div class="empty">
        <div class="empty-icon">📭</div>
        You have no bookings. <a href="{{ route('services.index') }}">Browse services</a>.
    </div>
@endforelse

{{ $bookings->links() }}

@endsection
