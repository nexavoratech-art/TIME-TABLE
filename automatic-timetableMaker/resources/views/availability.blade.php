@extends('layouts.app')
@section('title', 'Availability & Time Slots')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <h5 class="card-title mb-3"><i class="bi bi-clock text-primary me-2"></i>Define Time Slot</h5>
            <form action="{{ route('time-slots.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <select class="form-select" name="day_of_week">
                        <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
                        <option>Thursday</option><option>Friday</option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="time" class="form-control" name="start_time">
                </div>
                <div class="mb-3">
                    <input type="time" class="form-control" name="end_time">
                </div>
                <button class="btn btn-primary w-100"><i class="bi bi-plus-circle me-1"></i>Add Slot</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-3">
            <h5 class="card-title mb-3"><i class="bi bi-calendar-week text-success me-2"></i>Instructor Matrix</h5>
            <select class="form-select mb-3">
                <option>Select Instructor to set matrix...</option>
            </select>
            <div class="table-responsive">
                <table class="table table-bordered text-center small">
                    <thead class="table-dark">
                        <tr><th>Day</th><th>08:00 - 09:00</th><th>09:00 - 10:00</th><th>10:00 - 11:00</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Monday</td><td><span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Available</span></td><td><span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Busy</span></td><td><span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Available</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection