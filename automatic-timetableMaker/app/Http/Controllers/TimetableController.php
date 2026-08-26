<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\TimeSlot;
use App\Models\Venue;
use App\Services\TimetableGeneticAlgorithm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
            return redirect()->route('timetable')->with('error', 'Complete all academic assignments before generating the timetable.');
        }

        $startedAt = microtime(true);
        $result = (new TimetableGeneticAlgorithm(populationSize: 80, generations: 250, mutationRate: 0.06))->generate();
        if (isset($result['error'])) {
            return redirect()->route('timetable')->with('error', $result['error']);
        }

        $venues = Venue::query()->get()->keyBy('room_id');
        $instructors = Instructor::query()->get()->keyBy('instr_id');
        $dayOrder = array_flip(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);

        $schedule = collect($result['schedule'])->map(function (array $item) use ($venues, $instructors): array {
            $fullName = $instructors->get($item['instr_id'])?->instr_name ?? $item['instructor_name'] ?? 'Unassigned';
            $item['instructor_surname'] = Str::afterLast(trim($fullName), ' ');
            $item['venue_name'] = $venues->get($item['room_id'])?->room_name ?? 'Unassigned';
            return $item;
        })->sortBy(fn (array $item): string => sprintf('%02d-%s-%02d-%s', $dayOrder[$item['day']] ?? 99, $item['time_slot'], $item['year_of_study'], $item['course_code']))->values();

        $fitness = (float) $result['fitness'];
        return view('timetable', array_merge($readiness, [
            'schedule' => $schedule,
            'years' => $schedule->pluck('year_of_study')->unique()->sort()->values(),
            'fitness' => round($fitness * 100, 2),
            'generation' => $result['generation'],
            'conflicts' => max(0, (int) round((1 / max($fitness, PHP_FLOAT_EPSILON)) - 1)),
            'duration' => round(microtime(true) - $startedAt, 2),
        ]));
    }

    private function readiness(): array
    {
        $counts = ['courses' => Course::query()->count(), 'instructors' => Instructor::query()->count(), 'venues' => Venue::query()->count(), 'timeSlots' => TimeSlot::query()->count()];
        $unassignedCourses = Course::query()->where(fn ($query) => $query->whereNull('instr_id')->orWhereNull('year_of_study'))->count();
        return ['counts' => $counts, 'unassignedCourses' => $unassignedCourses, 'isReady' => collect($counts)->every(fn (int $count): bool => $count > 0) && $unassignedCourses === 0];
    }
}
