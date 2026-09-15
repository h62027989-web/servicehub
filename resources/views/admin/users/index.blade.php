@extends('layouts.app')
@section('title','Users - ServiceHub')
@section('heading','Manage Users')

@section('content')

<div class="card table reveal">
    <div class="tr th"><span>User</span><span>Role</span><span>Status / Save</span></div>
    @forelse($users as $u)
        <form method="POST" action="{{ route('admin.users.update',$u) }}" class="tr">
            @csrf @method('PUT')
            <span><b>{{ $u->name }}</b><small>{{ $u->email }}</small></span>
            <span>
                <select name="role">
                    <option @selected($u->role==='customer')>customer</option>
                    <option @selected($u->role==='provider')>provider</option>
                    <option @selected($u->role==='admin')>admin</option>
                </select>
            </span>
            <span style="display:flex;gap:8px;align-items:center">
                <select name="is_active">
                    <option value="1" @selected($u->is_active)>Active</option>
                    <option value="0" @selected(!$u->is_active)>Inactive</option>
                </select>
                <button class="small-btn" type="submit">Save</button>
            </span>
        </form>
    @empty
        <div class="empty"><div class="empty-icon">◯</div>No users found.</div>
    @endforelse
</div>
<div class="reveal">{{ $users->links() }}</div>

@endsection
