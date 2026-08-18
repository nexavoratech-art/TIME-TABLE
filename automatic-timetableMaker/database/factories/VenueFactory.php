<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Venue>
 */
class VenueFactory extends Factory
{
    protected $model = Venue::class;

    public function definition(): array
    {
        static $index = 0;
        $rooms = [
            ['name' => 'Room A101', 'capacity' => 120, 'type' => 'Lecture Hall'],
            ['name' => 'Room A102', 'capacity' => 90, 'type' => 'Lecture Hall'],
            ['name' => 'Room B201', 'capacity' => 70, 'type' => 'Classroom'],
            ['name' => 'Room B202', 'capacity' => 60, 'type' => 'Classroom'],
            ['name' => 'Lab 1', 'capacity' => 40, 'type' => 'Lab'],
            ['name' => 'Lab 2', 'capacity' => 35, 'type' => 'Lab'],
        ];

        $room = $rooms[$index % count($rooms)];
        $index++;

        return [
            'room_name' => $room['name'],
            'capacity' => $room['capacity'],
            'room_type' => $room['type'],
        ];
    }
}
