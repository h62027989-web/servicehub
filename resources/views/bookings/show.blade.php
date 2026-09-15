@extends('layouts.app')
@section('title','Booking #'.$booking->id.' - ServiceHub')
@section('heading','Booking #'.$booking->id)

@section('content')

<div class="card reveal">
    <div class="section">
        <div>
            <small class="eyebrow">SERVICE</small>
            <h2 style="font-size:22px;font-weight:800;letter-spacing:-.01em;margin:6px 0">{{ $booking->service->name }}</h2>
            <p class="body-text">Provider: {{ $booking->provider->name }}</p>
        </div>
        <span class="badge {{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
    </div>

    <hr>

    <div class="details">
        <p><b>Date:</b> {{ $booking->booking_date->format('d M Y') }}</p>
        <p><b>Time:</b> {{ $booking->booking_time }}</p>
        <p><b>Address:</b> {{ $booking->address }}</p>
        <p><b>Amount:</b> ₹{{ number_format($booking->amount,2) }}</p>
        <p><b>Payment:</b> <span class="badge {{ $booking->payment_status === 'paid' ? 'paid' : 'unpaid' }}">{{ ucfirst($booking->payment_status) }}</span></p>
    </div>

    <div class="row" style="margin-top:20px;gap:10px">
        @if(auth()->id()===$booking->customer_id && $booking->payment_status!=='paid' && in_array($booking->status,['pending','confirmed']))
            <a class="primary" href="{{ route('payments.show',$booking) }}">Pay Now →</a>
        @endif

        @if(auth()->id()===$booking->customer_id && in_array($booking->status,['pending','confirmed']))
            <form method="POST" action="{{ route('bookings.cancel',$booking) }}" class="inline">
                @csrf
                <button class="danger" type="submit">Cancel Booking</button>
            </form>
        @endif
    </div>
</div>

@if(auth()->id()===$booking->customer_id && $booking->status==='completed' && !$booking->review)
    <div class="card reveal">
        <h3 class="card-title" style="margin-bottom:16px">Leave a Review</h3>
        <form method="POST" action="{{ route('reviews.store',$booking) }}" class="form-grid">
            @csrf
            <label>Rating
                <select name="rating">
                    <option>5</option><option>4</option><option>3</option><option>2</option><option>1</option>
                </select>
            </label>
            <label class="wide">Comment
                <textarea name="comment"></textarea>
            </label>
            <button class="primary wide" type="submit">Submit Review</button>
        </form>
    </div>
@endif

@endsection
