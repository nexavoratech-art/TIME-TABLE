<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\StudentGroupController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\TimeSlotController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/programs', function () { return view('programs'); })->name('programs');
Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
Route::get('/students', function () { return view('students'); })->name('students');
Route::post('/students', [StudentGroupController::class, 'store'])->name('student-groups.store');
Route::get('/courses', function () { return view('courses'); })->name('courses');
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
Route::get('/instructors', function () { return view('instructors'); })->name('instructors');
Route::post('/instructors', [InstructorController::class, 'store'])->name('instructors.store');
Route::get('/availability', function () { return view('availability'); })->name('availability');
Route::post('/availability', [TimeSlotController::class, 'store'])->name('time-slots.store');
Route::get('/venues', function () { return view('venues'); })->name('venues');
Route::post('/venues', [VenueController::class, 'store'])->name('venues.store');
Route::get('/timetable', function () { return view('timetable'); })->name('timetable');

// Route::get('/', function () {
//     return view('welcome');
// });
