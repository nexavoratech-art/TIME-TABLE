<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\StudentGroupController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\TimeSlotController;

use App\Http\Middleware\AdminMiddleware;


// =====================================================
// GUEST ROUTES
// Only accessible when the user is NOT logged in
// =====================================================
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.post');


    // Register the first administrator
    Route::get('/register-admin', [AuthController::class, 'showFirstRegister'])
        ->name('register.first');

    Route::post('/register-admin', [AuthController::class, 'registerFirst'])
        ->name('register.first.post');
});


// =====================================================
// AUTHENTICATED ROUTES
// Only accessible after logging in
// =====================================================
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');


    // =========================
    // DASHBOARD
    // =========================
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');


    // =========================
    // PROGRAMS
    // =========================
    Route::get('/programs', function () {
        return view('programs');
    })->name('programs');

    Route::post('/programs', [ProgramController::class, 'store'])
        ->name('programs.store');


    // =========================
    // STUDENT GROUPS
    // =========================
    Route::get('/students', function () {
        return view('students');
    })->name('students');

    Route::post('/students', [StudentGroupController::class, 'store'])
        ->name('student-groups.store');


    // =========================
    // COURSES
    // =========================
    Route::get('/courses', function () {
        return view('courses');
    })->name('courses');

    Route::post('/courses', [CourseController::class, 'store'])
        ->name('courses.store');


    // =========================
    // INSTRUCTORS
    // =========================
    Route::get('/instructors', function () {
        return view('instructors');
    })->name('instructors');

    Route::post('/instructors', [InstructorController::class, 'store'])
        ->name('instructors.store');


    // =========================
    // AVAILABILITY / TIME SLOTS
    // =========================
    Route::get('/availability', [TimeSlotController::class, 'index'])
        ->name('availability');

    Route::post('/availability', [TimeSlotController::class, 'store'])
        ->name('time-slots.store');

    Route::post(
        '/availability/instructor',
        [TimeSlotController::class, 'saveInstructorAvailability']
    )->name('availability.save');


    // =========================
    // VENUES
    // =========================
    Route::get('/venues', function () {
        return view('venues');
    })->name('venues');

    Route::post('/venues', [VenueController::class, 'store'])
        ->name('venues.store');


    // =========================
    // TIMETABLE
    // =========================
    Route::get('/timetable', function () {
        return view('timetable');
    })->name('timetable');


    // =================================================
    // ADMIN-ONLY USER MANAGEMENT
    // =================================================
    Route::middleware(AdminMiddleware::class)->group(function () {

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');
    });
});