<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\StudentGroupController;
use App\Http\Controllers\TimeSlotController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
Route::get('/departments/{department}', [DepartmentController::class, 'show'])->name('departments.show');
Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
Route::patch('/departments/{department}/toggle', [DepartmentController::class, 'toggle'])->name('departments.toggle');
Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
Route::get('/programs', function () {
    return view('programs');
})->name('programs');
Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
Route::get('/students', function () {
    return view('students');
})->name('students');
Route::post('/students', [StudentGroupController::class, 'store'])->name('student-groups.store');
Route::get('/courses', function () {
    return view('courses');
})->name('courses');
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
Route::get('/instructors', function () {
    return view('instructors');
})->name('instructors');
Route::post('/instructors', [InstructorController::class, 'store'])->name('instructors.store');
Route::get('/availability', function () {
    return view('availability');
})->name('availability');
Route::post('/availability', [TimeSlotController::class, 'store'])->name('time-slots.store');
Route::get('/venues', function () {
    return view('venues');
})->name('venues');
Route::post('/venues', [VenueController::class, 'store'])->name('venues.store');
Route::get('/timetable', [TimetableController::class, 'index'])->name('timetable');
Route::get('/timetable/pdf', [TimetableController::class, 'pdf'])->name('timetable.pdf');
Route::post('/timetable/generate', [TimetableController::class, 'generate'])->name('timetable.generate');

// Route::get('/', function () {
//     return view('welcome');
// });
