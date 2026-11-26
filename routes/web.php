<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/courses/{course}/availability', [CourseController::class, 'availability'])
    ->name('courses.availability');

Route::resource('enrollments', EnrollmentController::class)->only(['index', 'create', 'store']);

Route::get('/pendaftaran', fn () => redirect()->route('enrollments.create'))
    ->name('registration');

// Guest authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Authenticated user dashboard & profile routes
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// Admin-only routes (Course write operations + Admin Dashboard & User Management)
Route::middleware(['auth', 'active', 'role:admin'])->group(function () {
    Route::resource('courses', CourseController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminUserController::class, 'dashboard'])->name('dashboard');
        Route::resource('users', AdminUserController::class)->except(['destroy']);
        Route::patch('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])
            ->name('users.toggle-status');
    });
});

// Public Course read routes (declared after courses/create so {course} doesn't shadow 'create')
Route::resource('courses', CourseController::class)->only(['index', 'show']);
