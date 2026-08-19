@extends('layouts.app')
@section('title', 'Edit Department')
@section('page-title', 'Edit Department')
@section('content')
<div class="card border-0 shadow-sm p-4 mx-auto" style="max-width: 700px">
    <form action="{{ route('departments.update', $department) }}" method="POST">@csrf @method('PUT')
        <div class="mb-3"><label class="form-label fw-bold">Department Code</label><input class="form-control" name="dept_code" value="{{ old('dept_code', $department->dept_code) }}" required>@error('dept_code')<div class="text-danger small">{{ $message }}</div>@enderror</div>
        <div class="mb-3"><label class="form-label fw-bold">Department Name</label><input class="form-control" name="dept_name" value="{{ old('dept_name', $department->dept_name) }}" required>@error('dept_name')<div class="text-danger small">{{ $message }}</div>@enderror</div>
        <button class="btn btn-primary">Save Changes</button> <a class="btn btn-outline-secondary" href="{{ route('departments.show', $department) }}">Cancel</a>
    </form>
</div>
@endsection
