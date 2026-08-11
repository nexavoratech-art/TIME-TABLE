@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-speedometer2 text-primary me-2"></i>System Overview</h2>
    <a href="{{ route('timetable') }}" class="btn btn-success"><i class="bi bi-play-circle-fill me-2"></i>Run Scheduler Engine</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-white p-3 border-start border-primary border-4">
            <span class="text-muted small fw-bold"><i class="bi bi-diagram-3 me-1"></i>PROGRAMS</span>
            <h3 class="mt-2 mb-0">{{ $deptCount }} / {{ $programCount }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-white p-3 border-start border-success border-4">
            <span class="text-muted small fw-bold"><i class="bi bi-book me-1"></i>COURSES</span>
            <h3 class="mt-2 mb-0">{{ $courseCount }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-white p-3 border-start border-warning border-4">
            <span class="text-muted small fw-bold"><i class="bi bi-person-badge me-1"></i>INSTRUCTORS</span>
            <h3 class="mt-2 mb-0">{{ $instructorCount }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-white p-3 border-start border-info border-4">
            <span class="text-muted small fw-bold"><i class="bi bi-building me-1"></i>VENUES</span>
            <h3 class="mt-2 mb-0">{{ $venueCount }}</h3>
        </div>
    </div>
</div>
@endsection