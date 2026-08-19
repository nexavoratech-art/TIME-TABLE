@extends('layouts.app')
@section('title', 'Programs')

@section('content')
<div class="row g-4">
    <div class="col-md-7 mx-auto">
        <div class="card border-0 shadow-sm p-4">

            <h5 class="card-title mb-3">
                <i class="bi bi-diagram-3-fill text-success me-2"></i>
                Add Program
            </h5>

            <!-- Program Form -->
            <form action="{{ route('programs.store') }}" method="POST">
                @csrf

                <!-- Department -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Department</label>

                    <select
                        class="form-select"
                        name="dept_id"
                        required
                    >
                        <option value="">Select Department...</option>

                        @foreach (\App\Models\Department::where('is_active', true)->orderBy('dept_name')->get() as $department)
                            <option value="{{ $department->dept_id }}">
                                {{ $department->dept_code }} — {{ $department->dept_name }}
                            </option>
                        @endforeach
                    </select>

                    @error('dept_id')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Program Name -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Program Name</label>

                    <input
                        type="text"
                        class="form-control"
                        name="program_name"
                        placeholder="e.g. BSc Computer Science"
                        value="{{ old('program_name') }}"
                        required
                    >

                    @error('program_name')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-check-circle me-1"></i>
                    Save Program
                </button>

            </form>

        </div>
    </div>
</div>
@endsection
