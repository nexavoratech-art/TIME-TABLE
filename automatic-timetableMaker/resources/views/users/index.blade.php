@extends('layouts.app')
@section('title', 'User Management')
@section('content')
<div class="admin-heading mb-4"><div><span class="eyebrow text-primary">System administration</span><h1 class="h3 fw-bold mb-1">User account management</h1><p class="text-muted mb-0">Create accounts, assign access levels, reset passwords, and remove inactive users.</p></div><span class="user-total"><strong>{{ $users->count() }}</strong> accounts</span></div>
<div class="row g-4">
    <div class="col-lg-4"><div class="card border-0 shadow-sm p-4 sticky-lg-top" style="top:5rem"><h2 class="h5 fw-bold mb-3"><i class="bi bi-person-plus text-primary me-2"></i>Create account</h2>
        <form action="{{ route('users.store') }}" method="POST">@csrf
            <div class="mb-3"><label class="form-label">Full name</label><input name="name" value="{{ old('name') }}" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Role</label><select name="role" class="form-select"><option value="user">Scheduling user</option><option value="admin">Administrator</option></select></div>
            <div class="mb-3"><label class="form-label">Temporary password</label><input type="password" name="password" class="form-control" minlength="8" required></div>
            <div class="mb-4"><label class="form-label">Confirm password</label><input type="password" name="password_confirmation" class="form-control" minlength="8" required></div>
            <button class="btn btn-primary w-100"><i class="bi bi-person-check me-1"></i>Create account</button>
        </form>
    </div></div>
    <div class="col-lg-8"><div class="d-grid gap-3">
        @forelse($users as $managedUser)
        <article class="user-account-card">
            <div class="user-avatar">{{ Str::upper(Str::substr($managedUser->name,0,1)) }}</div>
            <div class="user-identity"><strong>{{ $managedUser->name }}</strong><span>{{ $managedUser->email }}</span><small>Added {{ $managedUser->created_at?->format('d M Y') }}</small></div>
            <span class="role-badge role-{{ $managedUser->role }}">{{ $managedUser->role === 'admin' ? 'Administrator' : 'Scheduling user' }}</span>
            <div class="account-actions"><button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUser{{ $managedUser->id }}"><i class="bi bi-pencil-square"></i> Manage</button>
                @if(!auth()->user()->is($managedUser))<form action="{{ route('users.destroy',$managedUser) }}" method="POST" onsubmit="return confirm('Delete this user account?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>@endif
            </div>
        </article>
        <div class="modal fade" id="editUser{{ $managedUser->id }}" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0"><form action="{{ route('users.update',$managedUser) }}" method="POST">@csrf @method('PUT')<div class="modal-header"><h2 class="modal-title fs-5">Manage {{ $managedUser->name }}</h2><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body">
            <div class="mb-3"><label class="form-label">Full name</label><input name="name" value="{{ $managedUser->name }}" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" value="{{ $managedUser->email }}" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Role</label><select name="role" class="form-select"><option value="user" @selected($managedUser->role==='user')>Scheduling user</option><option value="admin" @selected($managedUser->role==='admin')>Administrator</option></select></div>
            <hr><p class="small text-muted">Leave password fields blank to keep the existing password.</p><div class="mb-3"><label class="form-label">New password</label><input type="password" name="password" class="form-control" minlength="8"></div><div><label class="form-label">Confirm password</label><input type="password" name="password_confirmation" class="form-control" minlength="8"></div>
        </div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary">Save changes</button></div></form></div></div></div>
        @empty<div class="empty-schedule text-center"><p class="mb-0">No user accounts found.</p></div>@endforelse
    </div></div>
</div>
@endsection
