@extends('layouts.app')
@section('title', 'User Management')

@section('content')
<div class="row g-4">
    <!-- Add User Form -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <h5 class="card-title mb-3"><i class="bi bi-person-plus-fill text-primary me-2"></i>Register New User</h5>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Default Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Assign Role</label>
                    <select name="role" class="form-select">
                        <option value="user" selected>Standard User</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg me-1"></i>Create User</button>
            </form>
        </div>
    </div>

    <!-- Registered Users List -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-3">
            <h5 class="card-title mb-3"><i class="bi bi-people-fill me-2"></i>System Users</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td class="fw-bold">{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                <span class="badge {{ $u->role === 'admin' ? 'bg-danger' : 'bg-secondary' }}">
                                    {{ strtoupper($u->role) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection