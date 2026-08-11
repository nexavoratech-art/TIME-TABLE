<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'room_type' => 'required|string|max:50',
        ]);

        Venue::create($validated);

        return redirect()->back()->with('success', 'Venue successfully created!');
    }
}
