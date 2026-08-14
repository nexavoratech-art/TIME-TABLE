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
                        <option value="">Select Day</option>
                        <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
                        <option>Thursday</option><option>Friday</option>
                        <option>Saturday</option><option>Sunday</option>
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

            <form action="{{ route('availability') }}" method="GET">
                <div class="mb-3">
                    <select class="form-select" name="instr_id" id="instr_id" onchange="this.form.submit()">
                        <option value="">Select Instructor to set matrix...</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->instr_id }}" {{ old('instr_id', $selectedInstructorId) == $instructor->instr_id ? 'selected' : '' }}>
                                {{ $instructor->instr_id }} - {{ $instructor->instr_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            @if($selectedInstructorId)
                <form action="{{ route('availability.save') }}" method="POST">
                    @csrf
                    <input type="hidden" name="instr_id" value="{{ $selectedInstructorId }}">

                    <div class="table-responsive">
                        <table class="table table-bordered text-center small align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Day</th>
                                    @foreach($timeSlots as $slot)
                                        <th>{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                    <tr>
                                        <td><strong>{{ $day }}</strong></td>
                                        @foreach($timeSlots as $slot)
                                            @if($slot->day_of_week === $day)
                                                @php
                                                    $slotId = $slot->slot_id;
                                                    $isAvailable = isset($selectedAvailability[$slotId]) ? (bool) $selectedAvailability[$slotId] : false;
                                                @endphp
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            name="slot_ids[]"
                                                            value="{{ $slotId }}"
                                                            {{ $isAvailable ? 'checked' : '' }}
                                                            id="slot_{{ $slotId }}">
                                                    </div>
                                                </td>
                                            @else
                                                <td class="bg-light text-muted">-</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save me-1"></i>Save Instructor Availability
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection