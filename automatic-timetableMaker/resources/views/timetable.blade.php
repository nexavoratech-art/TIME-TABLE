@extends('layouts.app')
@section('title', 'Timetable Generator')

@section('content')
<div class="timetable-page">
    <section class="generator-hero mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="eyebrow"><i class="bi bi-stars"></i> Intelligent scheduling workspace</span>
                <h1 class="display-6 fw-bold mt-3 mb-2">Build a conflict-aware master timetable</h1>
                <p class="lead mb-0">Generate, review, filter, and print a schedule from your registered academic data.</p>
            </div>
            <div class="col-lg-4">
                <form action="{{ route('timetable.generate') }}" method="POST" id="generate-form">
                    @csrf
                    <button type="submit" class="btn btn-light btn-lg w-100 generator-button" @disabled(!$isReady)>
                        <span class="button-idle"><i class="bi bi-cpu-fill me-2"></i>Generate timetable</span>
                        <span class="button-loading d-none"><span class="spinner-border spinner-border-sm me-2"></span>Optimizing schedule…</span>
                    </button>
                </form>
                <small class="d-block mt-2 text-center text-white-50">{{ $isReady ? 'All required data is ready.' : 'Complete the data checklist first.' }}</small>
            </div>
        </div>
    </section>

    <section class="row g-3 mb-4" aria-label="Scheduling data readiness">
        @foreach([
            ['label' => 'Courses', 'count' => $counts['courses'], 'icon' => 'bi-book', 'route' => 'courses'],
            ['label' => 'Instructors', 'count' => $counts['instructors'], 'icon' => 'bi-person-badge', 'route' => 'instructors'],
            ['label' => 'Venues', 'count' => $counts['venues'], 'icon' => 'bi-building', 'route' => 'venues'],
            ['label' => 'Time slots', 'count' => $counts['timeSlots'], 'icon' => 'bi-clock-history', 'route' => 'availability'],
        ] as $item)
        <div class="col-6 col-xl-3">
            <a class="readiness-card {{ $item['count'] > 0 ? 'is-ready' : 'needs-data' }}" href="{{ route($item['route']) }}">
                <span class="readiness-icon"><i class="bi {{ $item['icon'] }}"></i></span>
                <span><strong>{{ $item['count'] }}</strong><small>{{ $item['label'] }}</small></span>
                <i class="bi {{ $item['count'] > 0 ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' }} status-icon"></i>
            </a>
        </div>
        @endforeach
    </section>

    @if(isset($schedule))
    <section class="result-summary mb-4" aria-live="polite">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <span class="result-kicker"><i class="bi bi-check-circle-fill me-1"></i>Generation complete</span>
                <h2 class="h4 fw-bold mb-1">Master timetable ready for review</h2>
                <p class="text-muted mb-0">{{ $schedule->count() }} scheduled courses produced in {{ $duration }} seconds.</p>
            </div>
            <div class="summary-metrics">
                <div><strong>{{ $fitness }}%</strong><span>Fitness</span></div>
                <div><strong>{{ $conflicts }}</strong><span>Conflicts</span></div>
                <div><strong>{{ $generation }}</strong><span>Generations</span></div>
            </div>
        </div>
    </section>

    <section class="schedule-panel">
        <div class="schedule-toolbar">
            <div>
                <h2 class="h5 fw-bold mb-1"><i class="bi bi-calendar3 me-2 text-primary"></i>Generated master timetable</h2>
                <p class="text-muted small mb-0" id="result-count">Showing all {{ $schedule->count() }} sessions</p>
            </div>
            <div class="toolbar-actions">
                <div class="search-box"><i class="bi bi-search"></i><input type="search" id="schedule-search" class="form-control" placeholder="Search timetable…" aria-label="Search timetable"></div>
                <select id="day-filter" class="form-select" aria-label="Filter by day">
                    <option value="">All days</option>
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)<option value="{{ $day }}">{{ $day }}</option>@endforeach
                </select>
                <button type="button" class="btn btn-outline-primary" onclick="window.print()"><i class="bi bi-printer me-1"></i>Print</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table schedule-table align-middle mb-0">
                <thead><tr><th>Day</th><th>Time</th><th>Course</th><th>Instructor</th><th>Venue</th></tr></thead>
                <tbody id="schedule-body">
                    @foreach($schedule as $item)
                    <tr data-day="{{ $item['day'] }}" data-search="{{ Str::lower($item['day'].' '.$item['time_slot'].' '.$item['course_name'].' '.$item['instructor_name'].' '.$item['venue_name']) }}">
                        <td><span class="day-pill day-{{ Str::lower($item['day']) }}">{{ $item['day'] }}</span></td>
                        <td><span class="time-value"><i class="bi bi-clock me-1"></i>{{ $item['time_slot'] }}</span></td>
                        <td><strong>{{ $item['course_name'] }}</strong></td>
                        <td><span class="cell-detail"><i class="bi bi-person"></i>{{ $item['instructor_name'] }}</span></td>
                        <td><span class="cell-detail"><i class="bi bi-geo-alt"></i>{{ $item['venue_name'] }}</span></td>
                    </tr>
                    @endforeach
                    <tr id="empty-filter-state" class="d-none"><td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-search fs-3 d-block mb-2"></i>No sessions match your filters.</td></tr>
                </tbody>
            </table>
        </div>
    </section>
    @else
    <section class="empty-schedule text-center">
        <span class="empty-icon"><i class="bi bi-calendar2-week"></i></span>
        <h2 class="h4 fw-bold mt-3">No timetable generated yet</h2>
        <p class="text-muted mx-auto">Review the readiness cards, then generate your first optimized schedule.</p>
    </section>
    @endif
</div>

@push('scripts')
<script>
document.getElementById('generate-form')?.addEventListener('submit', function () {
    const button = this.querySelector('button');
    button.disabled = true;
    button.querySelector('.button-idle').classList.add('d-none');
    button.querySelector('.button-loading').classList.remove('d-none');
});

const search = document.getElementById('schedule-search');
const dayFilter = document.getElementById('day-filter');
const rows = [...document.querySelectorAll('#schedule-body tr[data-day]')];
function filterSchedule() {
    const query = (search?.value || '').trim().toLowerCase();
    const day = dayFilter?.value || '';
    let visible = 0;
    rows.forEach(row => {
        const show = (!day || row.dataset.day === day) && (!query || row.dataset.search.includes(query));
        row.classList.toggle('d-none', !show);
        if (show) visible++;
    });
    document.getElementById('empty-filter-state')?.classList.toggle('d-none', visible !== 0);
    if (document.getElementById('result-count')) document.getElementById('result-count').textContent = `Showing ${visible} of ${rows.length} sessions`;
}
search?.addEventListener('input', filterSchedule);
dayFilter?.addEventListener('change', filterSchedule);
</script>
@endpush
@endsection
