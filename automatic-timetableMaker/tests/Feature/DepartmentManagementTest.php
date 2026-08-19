<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Services\DemoTimetableGenerator;
use Database\Seeders\DemoTimetableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DepartmentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_department_crud_and_activation_work(): void
    {
        $this->post(route('departments.store'), ['dept_code' => 'TEST', 'dept_name' => 'Test Department'])->assertRedirect(route('departments.index'));
        $department = Department::query()->where('dept_code', 'TEST')->firstOrFail();
        $this->get(route('departments.show', $department))->assertOk()->assertSee('Test Department');
        $this->put(route('departments.update', $department), ['dept_code' => 'TST', 'dept_name' => 'Updated Department'])->assertRedirect(route('departments.show', $department));
        $this->patch(route('departments.toggle', $department))->assertRedirect();
        $this->assertFalse($department->fresh()->is_active);
        $this->delete(route('departments.destroy', $department))->assertRedirect(route('departments.index'));
        $this->assertDatabaseMissing('department', ['dept_id' => $department->dept_id]);
    }

    public function test_duplicate_department_codes_and_names_are_rejected(): void
    {
        Department::query()->create(['dept_id' => 1, 'dept_code' => 'CS', 'dept_name' => 'Computer Science', 'is_active' => true]);
        $this->from(route('departments.index'))->post(route('departments.store'), ['dept_code' => 'CS', 'dept_name' => 'Different'])
            ->assertRedirect(route('departments.index'))->assertSessionHasErrors('dept_code');
        $this->from(route('departments.index'))->post(route('departments.store'), ['dept_code' => 'NEW', 'dept_name' => 'Computer Science'])
            ->assertRedirect(route('departments.index'))->assertSessionHasErrors('dept_name');
    }

    public function test_department_with_dependants_cannot_be_deleted(): void
    {
        $this->seed(DemoTimetableSeeder::class);
        $department = Department::query()->where('dept_code', 'CS')->firstOrFail();
        $this->from(route('departments.show', $department))->delete(route('departments.destroy', $department))
            ->assertRedirect(route('departments.show', $department))->assertSessionHasErrors('department');
        $this->assertDatabaseHas('department', ['dept_id' => $department->dept_id]);
    }

    public function test_seeded_programmes_courses_and_instructors_resolve_to_departments(): void
    {
        $this->seed(DemoTimetableSeeder::class);
        $department = Department::query()->where('dept_code', 'CS')->firstOrFail();
        $this->assertSame(2, $department->programs()->count());
        $this->assertSame(8, $department->courses()->count());
        $this->assertSame(4, $department->instructors()->count());
    }

    public function test_department_filter_does_not_change_global_assignments(): void
    {
        $this->seed(DemoTimetableSeeder::class);
        app(DemoTimetableGenerator::class)->generate();
        $before = DB::table('timetable_entries')->orderBy('entry_id')->get()->map(fn ($row) => (array) $row)->all();
        $department = Department::query()->where('dept_code', 'CS')->firstOrFail();

        $response = $this->get(route('timetable', ['department' => $department->dept_id, 'year' => 1, 'semester' => 1]));
        $response->assertOk()->assertSee('Bachelor of Computer Science')->assertSee('Computer Science');
        $this->assertSame($before, DB::table('timetable_entries')->orderBy('entry_id')->get()->map(fn ($row) => (array) $row)->all());
    }

    public function test_filtered_department_pdf_export_succeeds(): void
    {
        $this->seed(DemoTimetableSeeder::class);
        app(DemoTimetableGenerator::class)->generate();
        $department = Department::query()->where('dept_code', 'CS')->firstOrFail();

        $response = $this->get(route('timetable.pdf', ['department' => $department->dept_id]));
        $response->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', (string) $response->getContent());
    }
}
