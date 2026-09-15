@extends('layouts.app')
@section('title','Categories - ServiceHub')
@section('heading','Manage Categories')

@section('content')

<div class="card reveal">
    <h3 class="card-title" style="margin-bottom:16px">Add Category</h3>
    <form method="POST" action="{{ route('admin.categories.store') }}" class="form-grid">
        @csrf
        <label>Name<input name="name" required></label>
        <label>Icon<input name="icon" placeholder="🧹"></label>
        <label class="wide">Description<textarea name="description"></textarea></label>
        <button class="primary wide" type="submit">Create</button>
    </form>
</div>

<div class="card reveal">
    <h3 class="card-title" style="margin-bottom:12px">All Categories</h3>
    <div class="table">
        <div class="tr th"><span>Name</span><span>Slug</span><span>Action</span></div>
        @forelse($categories as $c)
            <div class="tr">
                <span>{{ $c->icon }} {{ $c->name }}</span>
                <span>{{ $c->slug }}</span>
                <span>
                    <form method="POST" action="{{ route('admin.categories.destroy',$c) }}">
                        @csrf @method('DELETE')
                        <button class="danger" type="submit">Delete</button>
                    </form>
                </span>
            </div>
        @empty
            <div class="empty">
                <div class="empty-icon">▣</div>
                No categories yet.
            </div>
        @endforelse
    </div>
    {{ $categories->links() }}
</div>

@endsection
