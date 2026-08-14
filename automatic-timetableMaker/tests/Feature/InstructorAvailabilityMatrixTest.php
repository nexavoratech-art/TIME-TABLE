<?php

namespace Tests\Feature;

use App\Models\Instructor;
use App\Models\TimeSlot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstructorAvailabilityMatrixTest extends TestCase
{
    use RefreshDatabase;

    public function test_instructor_dropdown_lists_real_instructors_and_availability_can_be_saved(): void
    {
        $instructor = Instructor::create([
            'instr_name' => 'Dr. Alice Smith',
            'dept_id' => 1,
        ]);

        TimeSlot::create([
            'day_of_week' => 'Monday',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
        ]);

        TimeSlot::create([
            'day_of_week' => 'Monday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
        ]);

        $response = $this->get('/availability');

        $response->assertOk();
        $response->assertSee('Dr. Alice Smith');

        $response = $this->post('/availability/instructor', [
            'instr_id' => $instructor->instr_id,
            'slot_ids' => [1, 2],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('instructor_availabilities', [
            'instr_id' => $instructor->instr_id,
            'slot_id' => 1,
            'is_available' => true,
        ]);
        $this->assertDatabaseHas('instructor_availabilities', [
            'instr_id' => $instructor->instr_id,
            'slot_id' => 2,
            'is_available' => true,
        ]);
    }
}
