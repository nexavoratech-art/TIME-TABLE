@extends('layouts.app')
@section('title', 'Student Groups')
@section('content')
<div class="card border-0 shadow-sm p-4">
    <h1 class="h4 fw-bold">Add student cohort</h1><p class="text-muted">The program and year identify the cohort used for clash and venue-capacity checks.</p>
    <form class="row g-3" action="{{ route('student-groups.store') }}" method="POST">@csrf
        <div class="col-md-4"><label class="form-label">Cohort name</label><input type="text" class="form-control" name="group_name" value="{{ old('group_name') }}" required></div>
        <div class="col-md-3"><label class="form-label">Number of students</label><input type="number" min="1" class="form-control" name="student_count" value="{{ old('student_count') }}" required></div>
        <div class="col-md-3"><label class="form-label">Program</label><select class="form-select" name="program_id" required><option value="">Select program</option>@foreach(\App\Models\Program::all() as $program)<option value="{{ $program->program_id }}">{{ $program->program_name }}</option>@endforeach</select></div>
        <div class="col-md-2"><label class="form-label">Year</label><select class="form-select" name="year_of_study" required>@for($year=1;$year<=8;$year++)<option value="{{ $year }}">Year {{ $year }}</option>@endfor</select></div>
        <div class="col-12"><button class="btn btn-primary"><i class="bi bi-people me-1"></i>Add cohort</button></div>
    </form>
</div>
@endsection
