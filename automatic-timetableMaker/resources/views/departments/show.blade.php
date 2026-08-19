@extends('layouts.app')
@section('title', $department->dept_name)
@section('page-title', 'Department Details')
@section('content')
<div class="card border-0 shadow-sm p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between gap-3"><div><h3>{{ $department->dept_name }}</h3><span class="badge bg-dark">{{ $department->dept_code }}</span> <span class="badge {{ $department->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $department->is_active ? 'Active' : 'Inactive' }}</span></div>
    <div class="d-flex gap-2"><a class="btn btn-outline-primary" href="{{ route('departments.edit', $department) }}">Edit</a>
        <form method="POST" action="{{ route('departments.toggle', $department) }}">@csrf @method('PATCH')<button class="btn btn-outline-warning">{{ $department->is_active ? 'Deactivate' : 'Activate' }}</button></form>
        <form method="POST" action="{{ route('departments.destroy', $department) }}">@csrf @method('DELETE')<button class="btn btn-outline-danger">Delete</button></form>
    </div></div>
</div>
<div class="row g-4">
    <div class="col-lg-4"><div class="card border-0 shadow-sm p-3"><h5>Programmes</h5><ul class="list-group list-group-flush">@forelse($department->programs as $program)<li class="list-group-item">{{ $program->program_name }}</li>@empty<li class="list-group-item text-muted">None</li>@endforelse</ul></div></div>
    <div class="col-lg-4"><div class="card border-0 shadow-sm p-3"><h5>Courses</h5><ul class="list-group list-group-flush">@forelse($department->programs->flatMap->courses as $course)<li class="list-group-item"><strong>{{ $course->course_code }}</strong> {{ $course->course_name }}</li>@empty<li class="list-group-item text-muted">None</li>@endforelse</ul></div></div>
    <div class="col-lg-4"><div class="card border-0 shadow-sm p-3"><h5>Instructors</h5><ul class="list-group list-group-flush">@forelse($department->instructors as $instructor)<li class="list-group-item">{{ $instructor->instr_name }}</li>@empty<li class="list-group-item text-muted">None</li>@endforelse</ul></div></div>
</div>
<div class="mt-4"><a class="btn btn-success" href="{{ route('timetable', ['department' => $department->dept_id]) }}">View Department Timetable</a></div>
@endsection
