<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TimetableGeneticAlgorithm;
use App\Models\Venue;
use App\Models\Instructor;

class TimetableController extends Controller
{
    public function index()
    {
        return view('timetable');
    }

    public function generate(Request $request)
    {
        // Instantiating Genetic Algorithm service
        $ga = new TimetableGeneticAlgorithm(
            populationSize: 60,
            generations: 150,
            mutationRate: 0.05
        );

        $result = $ga->generate();

        if (isset($result['error'])) {
            return redirect()->back()->with('error', $result['error']);
        }

        // Resolve relations for UI rendering
        $venues = Venue::all()->keyBy('id');
        $instructors = Instructor::all()->keyBy('id');

        $scheduledItems = collect($result['schedule'])->map(function ($item) use ($venues, $instructors) {
            $item['venue_name'] = $venues[$item['venue_id']]->venue_name ?? 'Room ' . $item['venue_id'];
            $item['instructor_name'] = $instructors[$item['instructor_id']]->name ?? 'Instructor ' . $item['instructor_id'];
            return $item;
        });

        return view('timetable', [
            'schedule' => $scheduledItems,
            'fitness' => round($result['fitness'] * 100, 2),
            'generation' => $result['generation']
        ]);
    }
}