@extends('layouts.app')
@section('title','Services - ServiceHub')
@section('heading','Explore Services')

@section('content')

<div class="page-head reveal">
    <div>
        <small class="eyebrow">SERVICE MARKETPLACE</small>
        <h2>Find a service you need</h2>
        <p>Choose from verified professionals near you.</p>
    </div>
</div>

<form class="filters reveal" method="GET">
    <input name="q" value="{{ request('q') }}" placeholder="Search cleaning, plumbing, AC repair...">
    <select name="category">
        <option value="">All categories</option>
        @foreach($categories as $c)
            <option value="{{ $c->slug }}" @selected(request('category')===$c->slug)>{{ $c->icon }} {{ $c->name }}</option>
        @endforeach
    </select>
    <button class="primary" type="submit">Search</button>
</form>

<div class="service-grid">
    @forelse($q as $service)
        <article class="service-card reveal">
            <div class="service-cover">
                {{ $service->category->icon ?? '🛠️' }}
                <span>★ {{ number_format($service->rating,1) }}</span>
            </div>
            <div class="service-body">
                <small>{{ $service->category->name }}</small>
                <h3>{{ $service->name }}</h3>
                <p>{{ $service->provider->name }}</p>
                <div class="service-bottom">
                    <b>₹{{ number_format($service->price,2) }}</b>
                    <small>{{ $service->duration }}</small>
                </div>
                <a class="primary full" href="{{ route('services.show',$service) }}">View & Book</a>
            </div>
        </article>
    @empty
        <div class="empty" style="grid-column:1/-1">
            <div class="empty-icon">🔍</div>
            No services found. Try a different search or category.
        </div>
    @endforelse
</div>

{{ $q->links() }}

@endsection
