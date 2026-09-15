@extends('layouts.app')
@section('title','Manage Services - ServiceHub')
@section('heading','Manage Services')

@section('content')

<div class="card reveal">
    <h3 class="card-title" style="margin-bottom:16px">Add Service</h3>
    <form method="POST" action="{{ route('admin.services.store') }}" class="form-grid">
        @csrf
        <label>Category
            <select name="category_id">
                @foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
            </select>
        </label>
        <label>Provider
            <select name="provider_id">
                @foreach($providers as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
            </select>
        </label>
        <label>Name<input name="name" required></label>
        <label>Price<input name="price" type="number" step="0.01" required></label>
        <label>Duration<input name="duration" required></label>
        <label class="wide">Description<textarea name="description"></textarea></label>
        <button class="primary wide" type="submit">Create Service</button>
    </form>
</div>

<div class="card reveal">
    <h3 class="card-title" style="margin-bottom:12px">Services</h3>
    @forelse($services as $s)
        <div class="row divider">
            <div class="service-icon">{{ $s->category->icon }}</div>
            <div class="grow">
                <b>{{ $s->name }}</b>
                <small>{{ $s->provider->name }} • {{ $s->category->name }} • ₹{{ $s->price }}</small>
            </div>
            <form method="POST" action="{{ route('admin.services.destroy',$s) }}">
                @csrf @method('DELETE')
                <button class="danger" type="submit">Delete</button>
            </form>
        </div>
    @empty
        <div class="empty">
            <div class="empty-icon">▦</div>
            No services yet.
        </div>
    @endforelse
    {{ $services->links() }}
</div>

@endsection
