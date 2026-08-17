@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="row justify-content-center my-5">
    <div class="col-md-5">
        <div class="card border-0 shadow-lg p-4 bg-white">
            <div class="text-center mb-4">
                <i class="bi bi-shield-lock-fill text-primary display-4"></i>
                <h4 class="fw-bold mt-2">System Login</h4>
                <p class="text-muted small">Enter your credentials to access UniTime Scheduler</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="bi bi-envelope me-1"></i>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@university.edu" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold"><i class="bi bi-key me-1"></i>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Sign In
                </button>
            </form>
        </div>
    </div>
</div>
@endsection