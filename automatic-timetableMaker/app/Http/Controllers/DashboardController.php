<?php
namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Program;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Venue;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'deptCount' => Department::count(),
            'programCount' => Program::count(),
            'courseCount' => Course::count(),
            'instructorCount' => Instructor::count(),
            'venueCount' => Venue::count(),
        ]);
    }
}