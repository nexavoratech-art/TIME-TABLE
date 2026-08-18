<?php

namespace Database\Factories;

use App\Models\Instructor;
use App\Models\InstructorAvailability;
use App\Models\TimeSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InstructorAvailability>
 */
class InstructorAvailabilityFactory extends Factory
{
    protected $model = InstructorAvailability::class;

    public function definition(): array
    {
        return [
            'instr_id' => Instructor::query()->inRandomOrder()->value('instr_id') ?? 1,
            'slot_id' => TimeSlot::query()->inRandomOrder()->value('slot_id') ?? 1,
            'is_available' => $this->faker->boolean(85),
        ];
    }
}
