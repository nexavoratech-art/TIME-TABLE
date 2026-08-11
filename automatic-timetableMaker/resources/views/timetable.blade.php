@extends('layouts.app')
@section('title', 'Timetable Engine')

@section('content')
<div class="card border-0 shadow-sm p-4 mb-4 text-center bg-white">
    <h3 class="fw-bold mb-2"><i class="bi bi-cpu text-success me-2"></i>Genetic Algorithm Generator</h3>
    <p class="text-muted">Generate clash-free schedules respecting hard constraints and instructor preferences.</p>
    <div class="d-flex justify-content-center gap-3">
        <button class="btn btn-success btn-lg px-4"><i class="bi bi-play-fill me-1"></i>Generate Schedule</button>
        <button class="btn btn-outline-secondary btn-lg px-4"><i class="bi bi-download me-1"></i>Export PDF</button>
    </div>
</div>

<div class="card border-0 shadow-sm p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="card-title mb-0"><i class="bi bi-table me-2"></i>Generated Schedule</h5>
        <select class="form-select w-auto">
            <option>Filter by Student Group...</option>
            <option>Filter by Instructor...</option>
            <option>Filter by Venue...</option>
        </select>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr><th>Time Slot</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fw-bold">08:00 - 09:00</td>
                    <td class="bg-primary-subtle">
                        <div class="fw-bold text-primary">CS201 - Data Structures</div>
                        <div class="small"><i class="bi bi-person me-1"></i>Dr. Alan Turing | <i class="bi bi-geo-alt me-1"></i>Lab 3B</div>
                        <span class="badge bg-primary mt-1"><i class="bi bi-people me-1"></i>CS Year 2 A</span>
                    </td>
                    <td></td><td></td><td></td><td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection