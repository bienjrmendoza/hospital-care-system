<?php

use App\Http\Controllers\AdminInviteController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\AdminScheduleController;
use App\Http\Controllers\AdminSpecializationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorRequestController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\ScheduleBrowserController;
use App\Http\Controllers\UserRequestController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::view('/home', 'index')->name('index');
Route::get('/about', [ScheduleBrowserController::class, 'about'])->name('about');
Route::get('/', [ScheduleBrowserController::class, 'index'])->name('home');
Route::get('/schedules/feed', [ScheduleBrowserController::class, 'feed'])->name('schedules.feed');

Route::view('/contact', 'contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

Route::get('/test-mail', function() {
    Mail::raw('This is a test email', function ($message) {
        $message->to('johnhowellatienza@gmail.com')
                ->subject('Test Email');
    });
    return 'Test email sent!';
});

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/doctor/invites/{token}', [AdminInviteController::class, 'showAccept'])->name('doctor.invites.accept');
    Route::post('/doctor/invites/{token}', [AdminInviteController::class, 'accept'])->name('doctor.invites.complete');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::middleware('role:user')->group(function (): void {
        Route::get('/user/dashboard', [UserRequestController::class, 'dashboard'])->name('user.dashboard');
        Route::post('/schedule-requests', [UserRequestController::class, 'store'])->name('schedule-requests.store');
        Route::patch('/schedule-requests/{scheduleRequest}/cancel', [UserRequestController::class, 'cancel'])->name('schedule-requests.cancel');
    });

    Route::prefix('doctor')->middleware('role:doctor')->group(function (): void {
        Route::get('/dashboard', [DoctorScheduleController::class, 'dashboard'])->name('doctor.dashboard');
        Route::get('/schedules', [DoctorScheduleController::class, 'index'])->name('doctor.schedules.index');
        Route::post('/schedules', [DoctorScheduleController::class, 'store'])->name('doctor.schedules.store');
        Route::put('/schedules/{schedule}', [DoctorScheduleController::class, 'update'])->name('doctor.schedules.update');
        Route::delete('/schedules/{schedule}', [DoctorScheduleController::class, 'destroy'])->name('doctor.schedules.destroy');

        Route::get('/requests', [DoctorRequestController::class, 'index'])->name('doctor.requests.index');
        Route::patch('/requests/{scheduleRequest}/accept', [DoctorRequestController::class, 'accept'])->name('doctor.requests.accept');
        Route::patch('/requests/{scheduleRequest}/decline', [DoctorRequestController::class, 'decline'])->name('doctor.requests.decline');
    });

    Route::prefix('admin')->middleware('role:admin')->group(function (): void {
        Route::get('/dashboard', [AdminManagementController::class, 'dashboard'])->name('admin.dashboard');

        Route::get('/admins', [AdminManagementController::class, 'indexAdmins'])->name('admin.admins.index');
        Route::post('/admins', [AdminManagementController::class, 'storeAdmin'])->name('admin.admins.store');

        Route::get('/invites', [AdminInviteController::class, 'index'])->name('admin.invites.index');
        Route::post('/invites', [AdminInviteController::class, 'store'])->name('admin.invites.store');

        Route::get('/specializations', [AdminSpecializationController::class, 'index'])->name('admin.specializations.index');
        Route::post('/specializations', [AdminSpecializationController::class, 'store'])->name('admin.specializations.store');
        Route::put('/specializations/{specialization}', [AdminSpecializationController::class, 'update'])->name('admin.specializations.update');
        Route::delete('/specializations/{specialization}', [AdminSpecializationController::class, 'destroy'])->name('admin.specializations.destroy');

        Route::get('/schedules', [AdminScheduleController::class, 'index'])->name('admin.schedules.index');
        Route::put('/schedules/{schedule}', [AdminScheduleController::class, 'update'])->name('admin.schedules.update');
        Route::delete('/schedules/{schedule}', [AdminScheduleController::class, 'destroy'])->name('admin.schedules.destroy');
    });
});
