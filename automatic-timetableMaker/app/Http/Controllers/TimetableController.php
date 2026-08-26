<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\TimeSlot;
use App\Models\Venue;
use App\Services\TimetableGeneticAlgorithm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimetableController extends Controller
{
    public function index(): View
    {
        return view('timetable', $this->readiness());
    }

    public function generate(Request $request): View|RedirectResponse
    {
        $readiness = $this->readiness();

        if (! $readiness['isReady']) {
            return redirect()->route('timetable')->with(
                'error',
                'Add at least one course, instructor, venue, and time slot before generating a timetable.'
            );
        }

        $startedAt = microtime(true);
        $result = (new TimetableGeneticAlgorithm(
            populationSize: 60,
            generations: 150,
            mutationRate: 0.05
        ))->generate();

        if (isset($result['error'])) {
            return redirect()->route('timetable')->with('error', $result['error']);
        }

        $venues = Venue::query()->get()->keyBy('id');
        $instructors = Instructor::query()->get()->keyBy('id');
        $dayOrder = array_flip(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);

        $schedule = collect($result['schedule'])
            ->map(function (array $item) use ($venues, $instructors): array {
                $item['venue_name'] = $venues->get($item['venue_id'])?->venue_name ?? 'Room '.$item['venue_id'];
                $item['instructor_name'] = $instructors->get($item['instructor_id'])?->name ?? 'Instructor '.$item['instructor_id'];

                return $item;
            })
            ->sortBy(fn (array $item): string => sprintf(
                '%02d-%s-%s',
                $dayOrder[$item['day']] ?? 99,
                $item['time_slot'],
                $item['course_name']
            ))
            ->values();

        $fitness = (float) $result['fitness'];

        return view('timetable', array_merge($readiness, [
            'schedule' => $schedule,
            'fitness' => round($fitness * 100, 2),
            'generation' => $result['generation'],
            'conflicts' => max(0, (int) round((1 / max($fitness, PHP_FLOAT_EPSILON)) - 1)),
            'duration' => round(microtime(true) - $startedAt, 2),
        ]));
    }

    private function readiness(): array
    {
        $counts = [
            'courses' => Course::query()->count(),
            'instructors' => Instructor::query()->count(),
            'venues' => Venue::query()->count(),
            'timeSlots' => TimeSlot::query()->count(),
        ];

        return [
            'counts' => $counts,
            'isReady' => collect($counts)->every(fn (int $count): bool => $count > 0),
        ];
    }
}
