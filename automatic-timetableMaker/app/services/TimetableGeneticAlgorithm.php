<?php
namespace App\Services;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Venue;

class TimetableGeneticAlgorithm
{
    private $courses;
    private $instructors;
    private $venues;
    private $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    private $timeSlots = ['08:00-10:00', '10:00-12:00', '13:00-15:00', '15:00-17:00'];

    private $populationSize;
    private $generations;
    private $mutationRate;

    public function __construct($populationSize = 50, $generations = 100, $mutationRate = 0.05)
    {
        $this->populationSize = $populationSize;
        $this->generations = $generations;
        $this->mutationRate = $mutationRate;

        // Fetch active database records
        $this->courses = Course::all();
        $this->instructors = Instructor::all();
        $this->venues = Venue::all();
    }

    /**
     * Run the Genetic Algorithm execution loop
     */
    public function generate()
    {
        if ($this->courses->isEmpty() || $this->venues->isEmpty()) {
            return ['error' => 'Database lacks sufficient courses or venues to schedule.'];
        }

        // 1. Initialize Random Population
        $population = [];
        for ($i = 0; $i < $this->populationSize; $i++) {
            $population[] = $this->createRandomChromosome();
        }

        // 2. Evolutionary Loop
        for ($gen = 0; $gen < $this->generations; $gen++) {
            // Evaluate Fitness
            $fitnesses = [];
            foreach ($population as $index => $chromosome) {
                $fitnesses[$index] = $this->calculateFitness($chromosome);
            }

            // Check if optimal schedule (Fitness = 1.0) is found
            $maxFitnessIndex = array_search(max($fitnesses), $fitnesses);
            if ($fitnesses[$maxFitnessIndex] >= 1.0) {
                return [
                    'schedule' => $population[$maxFitnessIndex],
                    'fitness' => $fitnesses[$maxFitnessIndex],
                    'generation' => $gen + 1
                ];
            }

            // Build Next Generation
            $newPopulation = [];
            
            // Elitism: Preserve best chromosome
            $newPopulation[] = $population[$maxFitnessIndex];

            while (count($newPopulation) < $this->populationSize) {
                $parent1 = $this->tournamentSelection($population, $fitnesses);
                $parent2 = $this->tournamentSelection($population, $fitnesses);
                
                $offspring = $this->crossover($parent1, $parent2);
                $offspring = $this->mutate($offspring);

                $newPopulation[] = $offspring;
            }

            $population = $newPopulation;
        }

        // Return best schedule found within generation limit
        $finalFitnesses = array_map([$this, 'calculateFitness'], $population);
        $bestIndex = array_search(max($finalFitnesses), $finalFitnesses);

        return [
            'schedule' => $population[$bestIndex],
            'fitness' => $finalFitnesses[$bestIndex],
            'generation' => $this->generations
        ];
    }

    /**
     * Generate a random schedule chromosome
     */
    private function createRandomChromosome()
    {
        $chromosome = [];
        foreach ($this->courses as $course) {
            $chromosome[] = [
                'course_id' => $course->id,
                'course_name' => $course->course_name,
                'instructor_id' => $course->instructor_id ?? $this->instructors->random()->id,
                'venue_id' => $this->venues->random()->id,
                'day' => $this->days[array_rand($this->days)],
                'time_slot' => $this->timeSlots[array_rand($this->timeSlots)],
            ];
        }
        return $chromosome;
    }

    /**
     * Calculate fitness score (1.0 = zero conflicts)
     */
    private function calculateFitness($chromosome)
    {
        $conflicts = 0;
        $count = count($chromosome);

        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $geneA = $chromosome[$i];
                $geneB = $chromosome[$j];

                // Check for identical day and timeslot
                if ($geneA['day'] === $geneB['day'] && $geneA['time_slot'] === $geneB['time_slot']) {
                    // Hard Conflict 1: Venue double-booking
                    if ($geneA['venue_id'] === $geneB['venue_id']) {
                        $conflicts++;
                    }
                    // Hard Conflict 2: Instructor double-booking
                    if ($geneA['instructor_id'] === $geneB['instructor_id']) {
                        $conflicts++;
                    }
                }
            }

            // Hard Conflict 3: Venue Capacity check
            $course = $this->courses->firstWhere('id', $geneA['course_id']);
            $venue = $this->venues->firstWhere('id', $geneA['venue_id']);
            if ($course && $venue && isset($course->students_count) && $venue->capacity < $course->students_count) {
                $conflicts++;
            }
        }

        // Fitness inversely proportional to total conflicts
        return 1 / (1 + $conflicts);
    }

    /**
     * Tournament selection strategy
     */
    private function tournamentSelection($population, $fitnesses, $k = 3)
    {
        $best = null;
        $bestFitness = -1;

        for ($i = 0; $i < $k; $i++) {
            $randomIndex = rand(0, count($population) - 1);
            if ($fitnesses[$randomIndex] > $bestFitness) {
                $bestFitness = $fitnesses[$randomIndex];
                $best = $population[$randomIndex];
            }
        }

        return $best;
    }

    /**
     * Uniform crossover between two parents
     */
    private function crossover($parent1, $parent2)
    {
        $child = [];
        for ($i = 0; $i < count($parent1); $i++) {
            $child[] = (rand(0, 1) === 1) ? $parent1[$i] : $parent2[$i];
        }
        return $child;
    }

    /**
     * Random mutation on chromosome genes
     */
    private function mutate($chromosome)
    {
        foreach ($chromosome as &$gene) {
            if ((mt_rand() / mt_getrandmax()) < $this->mutationRate) {
                $gene['venue_id'] = $this->venues->random()->id;
                $gene['day'] = $this->days[array_rand($this->days)];
                $gene['time_slot'] = $this->timeSlots[array_rand($this->timeSlots)];
            }
        }
        return $chromosome;
    }
}