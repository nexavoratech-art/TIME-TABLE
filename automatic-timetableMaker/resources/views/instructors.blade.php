@extends('layouts.app')
@section('title', 'Instructors')

@section('content')
<div class="card border-0 shadow-sm p-3 mb-4">
    <h5 class="card-title mb-3"><i class="bi bi-person-vcard text-primary me-2"></i>Register Instructor</h5>
    <form class="row g-3" action="{{ route('instructors.store') }}" method="POST">
        @csrf
        <div class="col-md-6">
            <input type="text" class="form-control" name="instr_name" placeholder="Full Name (e.g. Dr. Alan Turing)">
        </div>
        <div class="col-md-6">
            <select class="form-select" name="dept_id">
                <option value="">Select Department...</option>
                @foreach (\App\Models\Department::all() as $department)
                    <option value="{{ $department->dept_id }}">{{ $department->dept_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <button class="btn btn-primary"><i class="bi bi-person-check me-1"></i>Add Instructor</button>
        </div>
    </form>
</div>
@endsection