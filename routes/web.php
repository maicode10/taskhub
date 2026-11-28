<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

    // temporary page, Member 2 will replace this with the real dashboards
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});