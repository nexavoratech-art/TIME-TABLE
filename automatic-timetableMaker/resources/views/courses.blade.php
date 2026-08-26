@extends('layouts.app')
@section('title', 'Courses')
@section('content')
<div class="card border-0 shadow-sm p-4">
    <div class="mb-4"><h1 class="h4 fw-bold mb-1">Course teaching assignment</h1><p class="text-muted mb-0">Register each course with its instructor and intended year of study.</p></div>
    <form class="row g-3" action="{{ route('courses.store') }}" method="POST">@csrf
        <div class="col-md-3"><label class="form-label">Course code</label><input type="text" class="form-control" name="course_code" value="{{ old('course_code') }}" required></div>
        <div class="col-md-5"><label class="form-label">Course title</label><input type="text" class="form-control" name="course_name" value="{{ old('course_name') }}" required></div>
        <div class="col-md-2"><label class="form-label">Hours/week</label><input type="number" min="1" max="20" class="form-control" name="hours_per_week" value="{{ old('hours_per_week') }}" required></div>
        <div class="col-md-2"><label class="form-label">Year</label><select class="form-select" name="year_of_study" required><option value="">Select</option>@for($year=1;$year<=8;$year++)<option value="{{ $year }}" @selected(old('year_of_study')==$year)>Year {{ $year }}</option>@endfor</select></div>
        <div class="col-md-6"><label class="form-label">Program</label><select class="form-select" name="program_id" required><option value="">Select program</option>@foreach(\App\Models\Program::all() as $program)<option value="{{ $program->program_id }}" @selected(old('program_id')==$program->program_id)>{{ $program->program_name }}</option>@endforeach</select></div>
        <div class="col-md-6"><label class="form-label">Assigned instructor</label><select class="form-select" name="instr_id" required><option value="">Select instructor</option>@foreach(\App\Models\Instructor::orderBy('instr_name')->get() as $instructor)<option value="{{ $instructor->instr_id }}" @selected(old('instr_id')==$instructor->instr_id)>{{ $instructor->instr_name }}</option>@endforeach</select></div>
        <div class="col-12"><button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i>Save course assignment</button></div>
    </form>
</div>
@endsection
