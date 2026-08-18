<?php

namespace Database\Factories;

use App\Models\TimeSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimeSlot>
 */
class TimeSlotFactory extends Factory
{
    protected $model = TimeSlot::class;

    public function definition(): array
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $times = [
            ['08:00:00', '10:00:00'],
            ['10:00:00', '12:00:00'],
            ['13:00:00', '15:00:00'],
            ['15:00:00', '17:00:00'],
        ];

        static $index = 0;
        $day = $days[floor($index / count($times)) % count($days)];
        $time = $times[$index % count($times)];
        $index++;

        return [
            'day_of_week' => $day,
            'start_time' => $time[0],
            'end_time' => $time[1],
        ];
    }
}
