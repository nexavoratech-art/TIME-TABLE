<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\InstructorAvailability;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    public function index()
    {
        $instructors = Instructor::orderBy('instr_name')->get();
        $timeSlots = TimeSlot::orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $selectedInstructorId = request('instr_id');
        $selectedAvailability = [];

        if ($selectedInstructorId) {
            $selectedAvailability = InstructorAvailability::where('instr_id', $selectedInstructorId)
                ->pluck('is_available', 'slot_id')
                ->toArray();
        }

        return view('availability', compact('instructors', 'timeSlots', 'selectedInstructorId', 'selectedAvailability'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        TimeSlot::create($validated);

        return redirect()->back()->with('success', 'Time slot successfully created!');
    }

    public function saveInstructorAvailability(Request $request)
    {
        $validated = $request->validate([
            'instr_id' => 'required|exists:instructors,instr_id',
            'slot_ids' => 'nullable|array',
            'slot_ids.*' => 'exists:time_slots,slot_id',
        ]);

        $instructorId = (int) $validated['instr_id'];
        $selectedSlotIds = $validated['slot_ids'] ?? [];

        InstructorAvailability::where('instr_id', $instructorId)->delete();

        foreach (TimeSlot::all() as $timeSlot) {
            InstructorAvailability::create([
                'instr_id' => $instructorId,
                'slot_id' => $timeSlot->slot_id,
                'is_available' => in_array((string) $timeSlot->slot_id, array_map('strval', $selectedSlotIds), true),
            ]);
        }

        return redirect()->route('availability', ['instr_id' => $instructorId])
            ->with('success', 'Instructor availability successfully saved.');
    }
}
