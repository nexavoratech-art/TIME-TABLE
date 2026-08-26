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

<div class="card border-0 shadow-sm p-4 mt-4">
    <h2 class="h5 fw-bold mb-3">Existing course assignments</h2>
    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Course</th><th>Program</th><th>Instructor and year</th><th></th></tr></thead><tbody>
    @forelse(\App\Models\Course::with('instructor')->orderBy('course_code')->get() as $course)
        <tr><td><strong>{{ $course->course_code }}</strong><small class="d-block text-muted">{{ $course->course_name }}</small></td><td>{{ \App\Models\Program::find($course->program_id)?->program_name }}</td><td colspan="2"><form class="row g-2" action="{{ route('courses.assignment.update', $course) }}" method="POST">@csrf @method('PUT')<div class="col-md-7"><select class="form-select form-select-sm" name="instr_id" required><option value="">Select instructor</option>@foreach(\App\Models\Instructor::orderBy('instr_name')->get() as $instructor)<option value="{{ $instructor->instr_id }}" @selected($course->instr_id==$instructor->instr_id)>{{ $instructor->instr_name }}</option>@endforeach</select></div><div class="col-md-3"><select class="form-select form-select-sm" name="year_of_study" required>@for($year=1;$year<=8;$year++)<option value="{{ $year }}" @selected($course->year_of_study==$year)>Year {{ $year }}</option>@endfor</select></div><div class="col-md-2"><button class="btn btn-sm btn-outline-primary w-100">Save</button></div></form></td></tr>
    @empty<tr><td colspan="4" class="text-center text-muted py-4">No courses registered.</td></tr>@endforelse
    </tbody></table></div>
</div>
@endsection
