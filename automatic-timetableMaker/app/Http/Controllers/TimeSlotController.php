<?php

namespace App\Http\Controllers;

use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
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
}
