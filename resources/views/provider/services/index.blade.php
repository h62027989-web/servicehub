@extends('layouts.app')
@section('title','My Services - ServiceHub')
@section('heading','My Services')

@section('content')

<div class="card reveal">
    <h3 class="card-title" style="margin-bottom:16px">Add Service</h3>
    <form method="POST" enctype="multipart/form-data" action="{{ route('provider.services.store') }}" class="form-grid">
        @csrf
        <label>Category
            <select name="category_id">
                @foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->icon }} {{ $c->name }}</option>@endforeach
            </select>
        </label>
        <label>Name<input name="name" required></label>
        <label>Price<input name="price" type="number" step="0.01" required></label>
        <label>Duration<input name="duration" placeholder="1-2 hours" required></label>
        <label>Image<input type="file" name="image" accept="image/*"></label>
        <label class="wide">Description<textarea name="description"></textarea></label>
        <button class="primary wide" type="submit">Add Service</button>
    </form>
</div>

<div class="card reveal">
    @forelse($services as $s)
        <div class="row divider">
            <div class="service-icon">{{ $s->category->icon }}</div>
            <div class="grow">
                <b>{{ $s->name }}</b>
                <small>₹{{ $s->price }} • {{ $s->duration }}</small>
            </div>
            <form method="POST" action="{{ route('provider.services.destroy',$s) }}">
                @csrf @method('DELETE')
                <button class="danger" type="submit">Delete</button>
            </form>
        </div>
    @empty
        <div class="empty">
            <div class="empty-icon">⚒</div>
            You haven't added any services yet.
        </div>
    @endforelse
    {{ $services->links() }}
</div>

@endsection
