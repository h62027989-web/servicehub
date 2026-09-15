@extends('layouts.app')
@section('title',$service->name.' - ServiceHub')
@section('heading','Service Details')

@section('content')

<div class="detail reveal">
    <div class="detail-cover"><span>{{ $service->category->icon ?? '🛠️' }}</span></div>
    <div>
        <small class="eyebrow">{{ $service->category->name }}</small>
        <h2>{{ $service->name }}</h2>
        <p>{{ $service->description }}</p>
        <p class="body-text"><b>Provider:</b> {{ $service->provider->name }} &nbsp;•&nbsp; ★ {{ number_format($service->rating,1) }} ({{ $service->review_count }} reviews)</p>
        <div class="price-line">₹{{ number_format($service->price,2) }}</div>
        <small class="small-text">Duration: {{ $service->duration }}</small>
    </div>
</div>

@if(auth()->user()->role==='customer')
    <div class="card reveal">
        <h3 class="card-title" style="margin-bottom:16px">Book this service</h3>
        <form method="POST" action="{{ route('bookings.store') }}" class="form-grid">
            @csrf
            <input type="hidden" name="service_id" value="{{ $service->id }}">
            <label>Date
                <input type="date" name="booking_date" min="{{ now()->format('Y-m-d') }}" required>
            </label>
            <label>Time
                <input type="time" name="booking_time" required>
            </label>
            <label class="wide">Address
                <textarea name="address" required>{{ auth()->user()->address }}</textarea>
            </label>
            <label class="wide">Note
                <textarea name="customer_note" placeholder="Optional instructions"></textarea>
            </label>
            <button class="primary wide" type="submit">Continue to Payment →</button>
        </form>
    </div>
@else
    <div class="empty">
        <div class="empty-icon">🔒</div>
        Login as a customer to book this service.
    </div>
@endif

<h3 class="section-title" style="margin-top:28px">Reviews ({{ $service->review_count }})</h3>
@forelse($service->reviews as $review)
    <div class="card reveal">
        <b>{{ $review->customer->name }} &nbsp;•&nbsp; ★ {{ $review->rating }}</b>
        <p class="body-text" style="margin-top:6px">{{ $review->comment }}</p>
    </div>
@empty
    <div class="empty">
        <div class="empty-icon">💬</div>
        No reviews yet.
    </div>
@endforelse

@endsection
