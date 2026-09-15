@extends('layouts.app')
@section('title','Booking Requests - ServiceHub')
@section('heading','Booking Requests')

@section('content')

@forelse($bookings as $b)
    <div class="card row reveal">
        <div class="service-icon">🛠️</div>
        <div class="grow">
            <b>{{ $b->service->name }}</b>
            <small>{{ $b->customer->name }} • {{ $b->booking_date->format('d M Y') }} • ₹{{ $b->amount }}</small>
            <small>Payment: {{ ucfirst($b->payment_status) }}</small>
        </div>
        <span class="badge {{ $b->status }}">{{ ucfirst($b->status) }}</span>
        @if($b->status==='pending')
            <form method="POST" action="{{ route('provider.bookings.update',$b) }}">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="confirmed">
                <button class="small-btn" type="submit">Accept</button>
            </form>
        @elseif($b->status==='confirmed' && $b->payment_status==='paid')
            <form method="POST" action="{{ route('provider.bookings.update',$b) }}">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="completed">
                <button class="small-btn" type="submit">Complete</button>
            </form>
        @endif
    </div>
@empty
    <div class="empty">
        <div class="empty-icon">📋</div>
        No booking requests yet.
    </div>
@endforelse

{{ $bookings->links() }}

@endsection
