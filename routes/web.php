<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Settings (All authenticated roles)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Admin Management (Admin role only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
        Route::get('/admins-create', [AdminController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
        Route::get('/admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');
        Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
        Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');
    });

    // Mahasiswa (Students) Module
    Route::middleware('role:admin,lecturer')->group(function () {
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    });
    Route::middleware('role:admin')->group(function () {
        Route::get('/students-create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    });

    // Dosen (Lecturers) Module
    Route::middleware('role:admin,lecturer')->group(function () {
        Route::get('/lecturers', [LecturerController::class, 'index'])->name('lecturers.index');
        Route::get('/lecturers/{lecturer}', [LecturerController::class, 'show'])->name('lecturers.show');
    });
    Route::middleware('role:admin')->group(function () {
        Route::get('/lecturers-create', [LecturerController::class, 'create'])->name('lecturers.create');
        Route::post('/lecturers', [LecturerController::class, 'store'])->name('lecturers.store');
        Route::get('/lecturers/{lecturer}/edit', [LecturerController::class, 'edit'])->name('lecturers.edit');
        Route::put('/lecturers/{lecturer}', [LecturerController::class, 'update'])->name('lecturers.update');
        Route::delete('/lecturers/{lecturer}', [LecturerController::class, 'destroy'])->name('lecturers.destroy');
    });

    // Jadwal (Schedules) Module - Viewable by all authenticated roles
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');
    Route::middleware('role:admin')->group(function () {
        Route::get('/schedules-create', [ScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
    });

    // Nilai (Grades) Module
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::middleware('role:admin,lecturer')->group(function () {
        Route::get('/grades-create', [GradeController::class, 'create'])->name('grades.create');
        Route::post('/grades', [GradeController::class, 'store'])->name('grades.store');
        Route::get('/grades/{grade}/edit', [GradeController::class, 'edit'])->name('grades.edit');
        Route::put('/grades/{grade}', [GradeController::class, 'update'])->name('grades.update');
        Route::delete('/grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy');
    });
});
