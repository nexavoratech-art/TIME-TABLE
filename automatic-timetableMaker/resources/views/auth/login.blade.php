@extends('layouts.app')
@section('title', 'Secure Sign In')

@section('content')
<div class="auth-shell">
    <section class="auth-intro">
        <span class="eyebrow"><i class="bi bi-calendar3"></i> UniTime GA</span>
        <h1>Academic timetable management, organized intelligently.</h1>
        <p>Secure access for authorized administrators and scheduling staff.</p>
        <div class="auth-features">
            <span><i class="bi bi-shield-check"></i> Role-protected administration</span>
            <span><i class="bi bi-cpu"></i> Conflict-aware generation</span>
            <span><i class="bi bi-calendar-check"></i> Structured academic scheduling</span>
        </div>
    </section>
    <section class="auth-card">
        <div class="auth-card-heading">
            <span class="auth-lock"><i class="bi bi-shield-lock"></i></span>
            <div><h2>Welcome back</h2><p>Sign in to continue to the scheduling workspace.</p></div>
        </div>
        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email address</label>
                <div class="input-group"><span class="input-group-text"><i class="bi bi-envelope"></i></span><input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" required autofocus></div>
                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <div class="input-group"><span class="input-group-text"><i class="bi bi-key"></i></span><input id="password" type="password" name="password" class="form-control" autocomplete="current-password" required><button class="btn btn-outline-secondary" type="button" id="toggle-password" aria-label="Show password"><i class="bi bi-eye"></i></button></div>
            </div>
            <div class="form-check mb-4"><input type="checkbox" name="remember" value="1" class="form-check-input" id="remember"><label class="form-check-label" for="remember">Keep me signed in on this device</label></div>
            <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold"><i class="bi bi-box-arrow-in-right me-2"></i>Sign in securely</button>
        </form>
        <p class="auth-note"><i class="bi bi-info-circle"></i> New accounts are created only by a system administrator.</p>
    </section>
</div>
@push('scripts')
<script>document.getElementById('toggle-password')?.addEventListener('click',function(){const input=document.getElementById('password');input.type=input.type==='password'?'text':'password';this.querySelector('i').classList.toggle('bi-eye');this.querySelector('i').classList.toggle('bi-eye-slash');});</script>
@endpush
@endsection
