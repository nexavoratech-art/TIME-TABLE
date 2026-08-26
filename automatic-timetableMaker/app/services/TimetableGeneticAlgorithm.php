<?php

namespace App\Services;

use App\Models\Course;
use App\Models\InstructorAvailability;
use App\Models\StudentGroup;
use App\Models\TimeSlot;
use App\Models\Venue;

class TimetableGeneticAlgorithm
{
    private $courses;
    private $venues;
    private $timeSlots;
    private $availability;
    private $groupSizes;

    public function __construct(private int $populationSize = 50, private int $generations = 100, private float $mutationRate = 0.05)
    {
        $this->courses = Course::query()->with('instructor')->get();
        $this->venues = Venue::query()->get();
        $this->timeSlots = TimeSlot::query()->orderBy('day_of_week')->orderBy('start_time')->get();
        $this->availability = InstructorAvailability::query()->where('is_available', true)->get()->groupBy('instr_id');
        $this->groupSizes = StudentGroup::query()->get()->groupBy(fn ($group) => $group->program_id.'-'.$group->year_of_study)->map(fn ($groups) => $groups->max('student_count'));
    }

    public function generate(): array
    {
        if ($this->courses->isEmpty() || $this->venues->isEmpty() || $this->timeSlots->isEmpty()) {
            return ['error' => 'Courses, venues, and database time slots are required before generation.'];
        }
        if ($this->courses->contains(fn ($course) => ! $course->instr_id || ! $course->year_of_study)) {
            return ['error' => 'Every course must have an assigned instructor and year of study.'];
        }

        $population = [];
        for ($i = 0; $i < $this->populationSize; $i++) {
            $population[] = $this->createRandomChromosome();
        }

        for ($generation = 1; $generation <= $this->generations; $generation++) {
            $fitnesses = array_map([$this, 'calculateFitness'], $population);
            $bestIndex = array_search(max($fitnesses), $fitnesses);
            if ($fitnesses[$bestIndex] >= 1.0) {
                return ['schedule' => $population[$bestIndex], 'fitness' => 1.0, 'generation' => $generation];
            }
            $next = [$population[$bestIndex]];
            while (count($next) < $this->populationSize) {
                $next[] = $this->mutate($this->crossover(
                    $this->tournamentSelection($population, $fitnesses),
                    $this->tournamentSelection($population, $fitnesses)
                ));
            }
            $population = $next;
        }

        $fitnesses = array_map([$this, 'calculateFitness'], $population);
        $bestIndex = array_search(max($fitnesses), $fitnesses);

        return ['schedule' => $population[$bestIndex], 'fitness' => $fitnesses[$bestIndex], 'generation' => $this->generations];
    }

    private function createRandomChromosome(): array
    {
        $chromosome = [];
        foreach ($this->courses as $course) {
            $sessions = max(1, (int) ceil($course->hours_per_week / 2));
            for ($session = 1; $session <= $sessions; $session++) {
                $slot = $this->timeSlots->random();
                $venue = $this->venues->random();
                $chromosome[] = [
                    'course_code' => $course->course_code,
                    'program_id' => $course->program_id,
                    'year_of_study' => (int) $course->year_of_study,
                    'instr_id' => $course->instr_id,
                    'instructor_name' => $course->instructor?->instr_name,
                    'room_id' => $venue->room_id,
                    'slot_id' => $slot->slot_id,
                    'day' => $slot->day_of_week,
                    'time_slot' => substr($slot->start_time, 0, 5).'-'.substr($slot->end_time, 0, 5),
                    'session' => $session,
                ];
            }
        }

        return $chromosome;
    }

    private function calculateFitness(array $chromosome): float
    {
        $conflicts = 0;
        foreach ($chromosome as $index => $geneA) {
            foreach (array_slice($chromosome, $index + 1) as $geneB) {
                if ($geneA['slot_id'] !== $geneB['slot_id']) {
                    continue;
                }
                $conflicts += (int) ($geneA['room_id'] === $geneB['room_id']);
                $conflicts += (int) ($geneA['instr_id'] === $geneB['instr_id']);
                $conflicts += (int) ($geneA['program_id'] === $geneB['program_id'] && $geneA['year_of_study'] === $geneB['year_of_study']);
            }

            $allowedSlots = $this->availability->get($geneA['instr_id']);
            if ($allowedSlots && ! $allowedSlots->contains('slot_id', $geneA['slot_id'])) {
                $conflicts += 2;
            }
            $venue = $this->venues->firstWhere('room_id', $geneA['room_id']);
            $requiredCapacity = $this->groupSizes->get($geneA['program_id'].'-'.$geneA['year_of_study'], 0);
            if ($venue && $requiredCapacity > $venue->capacity) {
                $conflicts += 2;
            }
        }

        return 1 / (1 + $conflicts);
    }

    private function tournamentSelection(array $population, array $fitnesses, int $size = 3): array
    {
        $best = null;
        $bestFitness = -1;
        for ($i = 0; $i < $size; $i++) {
            $index = array_rand($population);
            if ($fitnesses[$index] > $bestFitness) {
                $best = $population[$index];
                $bestFitness = $fitnesses[$index];
            }
        }
        return $best;
    }

    private function crossover(array $first, array $second): array
    {
        $child = [];
        foreach ($first as $index => $gene) {
            $child[] = random_int(0, 1) ? $gene : $second[$index];
        }
        return $child;
    }

    private function mutate(array $chromosome): array
    {
        foreach ($chromosome as &$gene) {
            if ((mt_rand() / mt_getrandmax()) < $this->mutationRate) {
                $slot = $this->timeSlots->random();
                $venue = $this->venues->random();
                $gene['slot_id'] = $slot->slot_id;
                $gene['day'] = $slot->day_of_week;
                $gene['time_slot'] = substr($slot->start_time, 0, 5).'-'.substr($slot->end_time, 0, 5);
                $gene['room_id'] = $venue->room_id;
            }
        }
        return $chromosome;
    }
}
