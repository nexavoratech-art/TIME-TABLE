@extends('layouts.app')
@section('title', 'Departments')
@section('page-title', 'Departments')

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4">
            <h5 class="card-title mb-3">Add Department</h5>
            <form action="{{ route('departments.store') }}" method="POST">@csrf
                <div class="mb-3"><label class="form-label fw-bold">Department Code</label>
                    <input class="form-control @error('dept_code') is-invalid @enderror" name="dept_code" value="{{ old('dept_code') }}" maxlength="20" required>
                    @error('dept_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3"><label class="form-label fw-bold">Department Name</label>
                    <input class="form-control @error('dept_name') is-invalid @enderror" name="dept_name" value="{{ old('dept_name') }}" maxlength="150" required>
                    @error('dept_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button class="btn btn-primary w-100">Save Department</button>
            </form>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-3">
            <div class="table-responsive"><table class="table table-hover align-middle">
                <thead><tr><th>Code</th><th>Department</th><th>Programmes</th><th>Courses</th><th>Instructors</th><th>Status</th><th></th></tr></thead>
                <tbody>@forelse($departments as $department)<tr>
                    <td><strong>{{ $department->dept_code }}</strong></td><td>{{ $department->dept_name }}</td>
                    <td>{{ $department->programs_count }}</td><td>{{ $department->courses_count }}</td><td>{{ $department->instructors_count }}</td>
                    <td><span class="badge {{ $department->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $department->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td><a class="btn btn-sm btn-outline-primary" href="{{ route('departments.show', $department) }}">View</a></td>
                </tr>@empty<tr><td colspan="7" class="text-center text-muted">No departments found.</td></tr>@endforelse</tbody>
            </table></div>
        </div>
    </div>
</div>
@endsection
