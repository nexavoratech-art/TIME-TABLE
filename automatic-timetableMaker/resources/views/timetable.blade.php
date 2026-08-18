@extends('layouts.app')
@section('title', 'Timetable Generator')

@section('content')
<div class="row g-4">
    <!-- Generator Trigger Card -->
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 bg-white d-flex flex-row align-items-center justify-content-between">
            <div>
                <h4 class="fw-bold mb-1"><i class="bi bi-cpu-fill text-primary me-2"></i>Automated Genetic Schedule Generator</h4>
                <p class="text-muted small mb-0">Runs multi-generational evolutionary scheduling to resolve venue and instructor conflicts.</p>
            </div>
            <form action="{{ route('timetable.generate') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                    <i class="bi bi-play-fill me-1"></i>Run Genetic Algorithm
                </button>
            </form>
        </div>
    </div>

    @if(isset($schedule))
    <!-- Stats Banner -->
    <div class="col-12">
        <div class="alert alert-success d-flex align-items-center justify-content-between border-0 shadow-sm" role="alert">
            <div>
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <strong>Optimal Schedule Generated!</strong> Resolved across <strong>{{ $generation }}</strong> generations.
            </div>
            <span class="badge bg-success fs-6">Fitness Score: {{ $fitness }}%</span>
        </div>
    </div>

    <!-- Scheduled Output Table -->
    <div class="col-12">
        <div class="card border-0 shadow-sm p-3">
            <h5 class="card-title mb-3"><i class="bi bi-calendar3 me-2"></i>Generated Master Timetable</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Day</th>
                            <th>Time Slot</th>
                            <th>Course</th>
                            <th>Instructor</th>
                            <th>Venue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedule as $item)
                        <tr>
                            <td class="fw-bold text-primary">{{ $item['day'] }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $item['time_slot'] }}</span></td>
                            <td class="fw-bold">{{ $item['course_name'] }}</td>
                            <td><i class="bi bi-person me-1"></i>{{ $item['instructor_name'] }}</td>
                            <td><i class="bi bi-geo-alt me-1"></i>{{ $item['venue_name'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection