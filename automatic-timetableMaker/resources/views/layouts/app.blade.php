<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UniTime Scheduler')</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body>
    @include('partials.alerts')
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-info" href="{{ route('dashboard') }}">
                <i class="bi bi-calendar3 me-2"></i>UniTime GA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('programs') ? 'active' : '' }}" href="{{ route('programs') }}">
                            <i class="bi bi-diagram-3 me-1"></i>Programs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('students') ? 'active' : '' }}" href="{{ route('students') }}">
                            <i class="bi bi-people me-1"></i>Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courses') ? 'active' : '' }}" href="{{ route('courses') }}">
                            <i class="bi bi-book me-1"></i>Courses
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('instructors') ? 'active' : '' }}" href="{{ route('instructors') }}">
                            <i class="bi bi-person-badge me-1"></i>Instructors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('availability') ? 'active' : '' }}" href="{{ route('availability') }}">
                            <i class="bi bi-clock-history me-1"></i>Availability
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('venues') ? 'active' : '' }}" href="{{ route('venues') }}">
                            <i class="bi bi-building me-1"></i>Venues
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary text-white px-3 shadow-sm {{ request()->routeIs('timetable') ? 'active border-light' : '' }}" href="{{ route('timetable') }}">
                            <i class="bi bi-cpu-fill me-1"></i>Generator
                        </a>
                    </li>

                    @if(Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold" href="{{ route('users.index') }}">
                            <i class="bi bi-people-fill me-1"></i>Manage Users
                        </a>
                    </li>
                    @endif

                    <!-- User Profile Dropdown / Logout -->
                    <li class="nav-item ms-lg-3">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Logout ({{ Auth::user()->name }})</button>
                        </form>
                    </li>
                </ul>
                @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        @yield('content')
    </main>
</body>

</html>