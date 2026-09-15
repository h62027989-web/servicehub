@extends('layouts.app')
@section('title','Bookings - ServiceHub')
@section('heading','Manage Bookings')

@section('content')

<div class="card table reveal">
    <div class="tr th"><span>Booking</span><span>People</span><span>Status</span></div>
    @forelse($bookings as $b)
        <form method="POST" action="{{ route('admin.bookings.update',$b) }}" class="tr">
            @csrf @method('PUT')
            <span>#{{ $b->id }}<small>{{ $b->service->name }} • ₹{{ $b->amount }}</small></span>
            <span>{{ $b->customer->name }} → {{ $b->provider->name }}</span>
            <span style="display:flex;gap:8px;align-items:center">
                <select name="status">
                    <option @selected($b->status==='pending')>pending</option>
                    <option @selected($b->status==='confirmed')>confirmed</option>
                    <option @selected($b->status==='completed')>completed</option>
                    <option @selected($b->status==='cancelled')>cancelled</option>
                </select>
                <button class="small-btn" type="submit">Save</button>
            </span>
        </form>
    @empty
        <div class="empty"><div class="empty-icon">◷</div>No bookings found.</div>
    @endforelse
</div>
<div class="reveal">{{ $bookings->links() }}</div>

@endsection
