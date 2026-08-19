@extends('layouts.app')
@section('title', 'Timetable Demonstration')

@section('content')
<div class="alert alert-warning text-center fw-bold">DEMONSTRATION DATA — NOT AN OFFICIAL RUCU TIMETABLE</div>
<div class="card border-0 shadow-sm p-4 mb-4 text-center bg-white">
    <h3 class="fw-bold mb-2">University Timetable Demonstration</h3>
    <p class="text-muted">{{ \App\Services\DemoTimetableGenerator::ACADEMIC_TERM }}</p>
    <form action="{{ route('timetable.generate') }}" method="POST">@csrf
        <button class="btn btn-success btn-lg px-4"><i class="bi bi-play-fill me-1"></i>Generate Schedule</button>
    </form>
</div>
<div class="card border-0 shadow-sm p-3 mb-4">
    <form class="row g-3 align-items-end" method="GET" action="{{ route('timetable') }}">
        <div class="col-md-3"><label class="form-label">Department</label><select class="form-select" name="department"><option value="">All Departments</option>
            @foreach($departments as $department)<option value="{{ $department->dept_id }}" @selected(($filters['department'] ?? null) == $department->dept_id)>{{ $department->dept_code }} — {{ $department->dept_name }}</option>@endforeach
        </select></div>
        <div class="col-md-3"><label class="form-label">Programme</label><select class="form-select" name="programme"><option value="">All Programmes</option>
            @foreach($programs as $program)<option value="{{ $program->program_id }}" @selected(($filters['programme'] ?? null) == $program->program_id)>{{ $program->program_name }}</option>@endforeach
        </select></div>
        <div class="col-md-2"><label class="form-label">Year</label><select class="form-select" name="year"><option value="">All Years</option>@foreach(range(1, 6) as $year)<option value="{{ $year }}" @selected(($filters['year'] ?? null) == $year)>Year {{ $year }}</option>@endforeach</select></div>
        <div class="col-md-2"><label class="form-label">Semester</label><select class="form-select" name="semester"><option value="">All Semesters</option>@foreach(range(1, 3) as $semester)<option value="{{ $semester }}" @selected(($filters['semester'] ?? null) == $semester)>Semester {{ $semester }}</option>@endforeach</select></div>
        <div class="col-md-2"><button class="btn btn-primary w-100">Apply Filters</button></div>
        <div class="col-12 text-end"><a class="btn btn-outline-danger" href="{{ route('timetable.pdf', $filters) }}">Export Filtered PDF</a></div>
    </form>
</div>
@if (session('generation'))
    @php($generation = session('generation'))
    <div class="alert alert-info">Requested: {{ $generation['result']['requested'] }}; scheduled: {{ $generation['result']['scheduled'] }}; unscheduled: {{ count($generation['result']['unscheduled']) }}; generation time: {{ $generation['result']['milliseconds'] }} ms. Validation violations: {{ array_sum($generation['validation']) }}.</div>
@endif

<div class="card border-0 shadow-sm p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="card-title mb-0"><i class="bi bi-table me-2"></i>Generated Schedule ({{ $entries->count() }} sessions)</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr><th>Day</th><th>Period</th><th>Course</th><th>Programme</th><th>Department</th><th>Instructor</th><th>Venue</th><th>Cohort</th></tr>
            </thead>
            <tbody>
                @forelse ($entries as $entry)
                    <tr><td>{{ $entry->day_of_week }}</td><td>{{ substr($entry->start_time, 0, 5) }}–{{ substr($entry->end_time, 0, 5) }}</td>
                        <td><strong>{{ $entry->course_code }}</strong> — {{ $entry->course_name }}</td><td>{{ $entry->program_name }}</td><td>{{ $entry->dept_name }}</td><td>{{ $entry->instr_name }}</td>
                        <td>{{ $entry->room_name }}</td><td>{{ $entry->group_name }}</td></tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">No timetable entries match these filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
