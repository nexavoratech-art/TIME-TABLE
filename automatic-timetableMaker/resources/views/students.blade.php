@extends('layouts.app')
@section('title', 'Student Groups')

@section('content')
<div class="card border-0 shadow-sm p-3 mb-4">
    <h5 class="card-title mb-3"><i class="bi bi-person-plus-fill text-primary me-2"></i>Add Student Group (Cohort)</h5>
    <form class="row g-3" action="{{ route('student-groups.store') }}" method="POST">
        @csrf
        <div class="col-md-4">
            <input type="text" class="form-control" name="group_name" placeholder="Group Name (e.g. CS Year 2 A)">
        </div>
        <div class="col-md-4">
            <input type="number" class="form-control" name="student_count" placeholder="Student Count">
        </div>
        <div class="col-md-4">
            <select class="form-select" name="program_id">
                <option value="">Select Program...</option>
                @foreach (\App\Models\Program::all() as $program)
                    <option value="{{ $program->program_id }}">{{ $program->program_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <button class="btn btn-primary"><i class="bi bi-save me-1"></i>Add Group</button>
        </div>
    </form>
</div>
@endsection