@extends('layouts.app')
@section('title', 'Initial Setup - Create Admin')

@section('content')
<div class="row justify-content-center my-5">
    <div class="col-md-6">
        <div class="card border-0 shadow-lg p-4 bg-white border-top border-primary border-5">
            <div class="text-center mb-4">
                <i class="bi bi-person-badge-fill text-primary display-4"></i>
                <h4 class="fw-bold mt-2">First-Time System Initialization</h4>
                <p class="text-muted small">No users found. Create the primary <strong>System Administrator</strong> account.</p>
            </div>

            <form action="{{ route('register.first.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="bi bi-person me-1"></i>Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Administrator Name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="bi bi-envelope me-1"></i>Admin Email</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@university.edu" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="bi bi-key me-1"></i>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="At least 8 characters" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="bi bi-check2-circle me-1"></i>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                    <i class="bi bi-shield-check me-1"></i>Initialize System & Register Admin
                </button>
            </form>
        </div>
    </div>
</div>
@endsection