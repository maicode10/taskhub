<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;

// if somebody opens the website, send him to the login page
Route::get('/', function () {
    return redirect()->route('login');
});


// ===== MEMBER 1 - LOGIN AND PASSWORD =====

// guest = only people who are NOT logged in can open these
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// auth = only people who ARE logged in can open these
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/change-password',  [AuthController::class, 'showChangePassword'])->name('password.form');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');

    // redirect users to their role-specific dashboard
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');

    // MEMBER 2 - ADMIN DASHBOARD AND AUDIT LOGS
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
            ->name('admin.dashboard');

        Route::get('/admin/audit-logs', [AuditLogController::class, 'index'])
            ->name('admin.audit-logs.index');
    });

    // MEMBER 2 - STAFF DASHBOARD
    Route::middleware('role:staff')->group(function () {
        Route::get('/staff/dashboard', [DashboardController::class, 'staff'])
            ->name('staff.dashboard');
    });
});

// MEMBER 3 - TASK ROUTES
require __DIR__.'/tasks.php';