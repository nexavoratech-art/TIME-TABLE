<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#0f172a">

    <title>
        @yield('title', 'UniTime Scheduler')
    </title>

    @vite([
        'resources/sass/app.scss',
        'resources/js/app.js'
    ])

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >
</head>

@php
    $academicActive =
        request()->routeIs('departments.*') ||
        request()->routeIs('programs') ||
        request()->routeIs('programs.*') ||
        request()->routeIs('students') ||
        request()->routeIs('students.*') ||
        request()->routeIs('courses') ||
        request()->routeIs('courses.*') ||
        request()->routeIs('instructors') ||
        request()->routeIs('instructors.*');

    $schedulingActive =
        request()->routeIs('availability') ||
        request()->routeIs('availability.*') ||
        request()->routeIs('venues') ||
        request()->routeIs('venues.*');

    $timetableActive =
        request()->routeIs('timetable') ||
        request()->routeIs('timetable.*');
@endphp

<body class="app-body">

<div class="app-shell">

    {{-- ==============================
        SIDEBAR
    =============================== --}}
    <aside class="app-sidebar" id="appSidebar">

        {{-- Brand --}}
        <div class="sidebar-brand">

            <a href="{{ route('dashboard') }}" class="brand-link">

                <span class="brand-icon">
                    <i class="bi bi-calendar3"></i>
                </span>

                <span class="brand-text">
                    <span class="brand-name">UniTime</span>
                    <span class="brand-subtitle">
                        Timetable Scheduler
                    </span>
                </span>

            </a>

            <button
                type="button"
                class="sidebar-collapse-button d-none d-lg-flex"
                id="sidebarToggleDesktop"
                aria-label="Collapse sidebar"
                title="Collapse sidebar"
            >
                <i class="bi bi-chevron-left"></i>
            </button>

        </div>


        {{-- Navigation --}}
        <nav class="sidebar-navigation">

            {{-- Dashboard --}}
            <div class="sidebar-section">

                <span class="sidebar-section-title">
                    Overview
                </span>

                <a
                    href="{{ route('dashboard') }}"
                    class="sidebar-link
                    {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    title="Dashboard"
                >
                    <span class="sidebar-link-icon">
                        <i class="bi bi-grid-1x2-fill"></i>
                    </span>

                    <span class="sidebar-link-text">
                        Dashboard
                    </span>
                </a>

            </div>


            {{-- Academic --}}
            <div class="sidebar-section">

                <span class="sidebar-section-title">
                    Academic Management
                </span>

                <button
                    class="sidebar-group-toggle
                    {{ $academicActive ? 'is-active' : 'collapsed' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#academicMenu"
                    aria-expanded="{{ $academicActive ? 'true' : 'false' }}"
                    aria-controls="academicMenu"
                    title="Academic Management"
                >

                    <span class="sidebar-link-icon">
                        <i class="bi bi-mortarboard-fill"></i>
                    </span>

                    <span class="sidebar-link-text">
                        Academic Setup
                    </span>

                    <span class="sidebar-chevron">
                        <i class="bi bi-chevron-down"></i>
                    </span>

                </button>


                <div
                    class="collapse sidebar-submenu
                    {{ $academicActive ? 'show' : '' }}"
                    id="academicMenu"
                >

                    <a
                        href="{{ route('departments.index') }}"
                        class="sidebar-submenu-link
                        {{ request()->routeIs('departments.*') ? 'active' : '' }}"
                    >
                        <span class="submenu-dot"></span>
                        Departments
                    </a>

                    <a
                        href="{{ route('programs') }}"
                        class="sidebar-submenu-link
                        {{ request()->routeIs('programs', 'programs.*') ? 'active' : '' }}"
                    >
                        <span class="submenu-dot"></span>
                        Programmes
                    </a>

                    <a
                        href="{{ route('students') }}"
                        class="sidebar-submenu-link
                        {{ request()->routeIs('students', 'students.*') ? 'active' : '' }}"
                    >
                        <span class="submenu-dot"></span>
                        Students
                    </a>

                    <a
                        href="{{ route('courses') }}"
                        class="sidebar-submenu-link
                        {{ request()->routeIs('courses', 'courses.*') ? 'active' : '' }}"
                    >
                        <span class="submenu-dot"></span>
                        Courses
                    </a>

                    <a
                        href="{{ route('instructors') }}"
                        class="sidebar-submenu-link
                        {{ request()->routeIs('instructors', 'instructors.*') ? 'active' : '' }}"
                    >
                        <span class="submenu-dot"></span>
                        Instructors
                    </a>

                </div>

            </div>


            {{-- Scheduling --}}
            <div class="sidebar-section">

                <span class="sidebar-section-title">
                    Scheduling
                </span>

                <button
                    class="sidebar-group-toggle
                    {{ $schedulingActive ? 'is-active' : 'collapsed' }}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#schedulingMenu"
                    aria-expanded="{{ $schedulingActive ? 'true' : 'false' }}"
                    aria-controls="schedulingMenu"
                    title="Scheduling"
                >

                    <span class="sidebar-link-icon">
                        <i class="bi bi-calendar-week-fill"></i>
                    </span>

                    <span class="sidebar-link-text">
                        Scheduling
                    </span>

                    <span class="sidebar-chevron">
                        <i class="bi bi-chevron-down"></i>
                    </span>

                </button>


                <div
                    class="collapse sidebar-submenu
                    {{ $schedulingActive ? 'show' : '' }}"
                    id="schedulingMenu"
                >

                    <a
                        href="{{ route('availability') }}"
                        class="sidebar-submenu-link
                        {{ request()->routeIs('availability', 'availability.*') ? 'active' : '' }}"
                    >
                        <span class="submenu-dot"></span>
                        Availability
                    </a>

                    <a
                        href="{{ route('venues') }}"
                        class="sidebar-submenu-link
                        {{ request()->routeIs('venues', 'venues.*') ? 'active' : '' }}"
                    >
                        <span class="submenu-dot"></span>
                        Venues
                    </a>

                </div>

            </div>


            {{-- Timetable --}}
            <div class="sidebar-section">

                <span class="sidebar-section-title">
                    Timetable
                </span>

                <a
                    href="{{ route('timetable') }}"
                    class="sidebar-generator-link
                    {{ $timetableActive ? 'active' : '' }}"
                    title="Timetable Generator"
                >

                    <span class="sidebar-link-icon">
                        <i class="bi bi-stars"></i>
                    </span>

                    <span class="sidebar-link-text">
                        Generate Timetable
                    </span>

                </a>

            </div>

        </nav>


        {{-- Sidebar Footer --}}
        <div class="sidebar-footer">

            <div class="sidebar-footer-icon">
                <i class="bi bi-calendar-check"></i>
            </div>

            <div class="sidebar-footer-text">
                <strong>UniTime GA</strong>
                <small>Scheduling Workspace</small>
            </div>

        </div>

    </aside>


    {{-- Mobile backdrop --}}
    <div
        class="sidebar-backdrop"
        id="sidebarBackdrop">
    </div>


    {{-- ==============================
        MAIN APPLICATION
    =============================== --}}
    <section class="app-main">

        {{-- Topbar --}}
        <header class="app-topbar">

            <div class="topbar-left">

                {{-- Mobile Menu --}}
                <button
                    type="button"
                    class="topbar-menu-button d-lg-none"
                    id="sidebarToggleMobile"
                    aria-label="Open navigation"
                >
                    <i class="bi bi-list"></i>
                </button>


                <div class="topbar-page-heading">

                    <span class="topbar-eyebrow">
                        UniTime Scheduler
                    </span>

                    <h1 class="topbar-page-title">
                        @yield('page-title', 'Dashboard')
                    </h1>

                </div>

            </div>


            <div class="topbar-right">

                <a
                    href="{{ route('timetable') }}"
                    class="topbar-generator-button
                    {{ $timetableActive ? 'active' : '' }}"
                >
                    <i class="bi bi-stars"></i>

                    <span class="d-none d-sm-inline">
                        Generate
                    </span>
                </a>

            </div>

        </header>


        {{-- Content --}}
        <main class="page-content">

            <div class="page-container">

                @include('partials.alerts')

                @yield('content')

            </div>

        </main>

    </section>

</div>

</body>
</html>
