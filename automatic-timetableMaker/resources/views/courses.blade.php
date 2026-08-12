@extends('layouts.app')
@section('title', 'Courses')

@section('content')
<div class="card border-0 shadow-sm p-3 mb-4">
    <h5 class="card-title mb-3"><i class="bi bi-journal-plus text-primary me-2"></i>Add Course</h5>
    <form class="row g-3" action="{{ route('courses.store') }}" method="POST">
        @csrf
        <div class="col-md-3">
            <input type="text" class="form-control" name="course_code" placeholder="Course Code (e.g. CS201)">
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="course_name" placeholder="Course Title">
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control" name="hours_per_week" placeholder="Hours/Wk">
        </div>
        <div class="col-md-3">
            <select class="form-select" name="program_id">
                <option value="">Select Program...</option>
                @foreach (\App\Models\Program::all() as $program)
                    <option value="{{ $program->program_id }}">{{ $program->program_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <button class="btn btn-primary"><i class="bi bi-plus-square me-1"></i>Save Course</button>
        </div>
    </form>
</div>
@endsection