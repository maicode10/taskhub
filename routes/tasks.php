<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// MEMBER 3 - TASK MODULE
Route::middleware('auth')->group(function () {

    // admin only
    Route::middleware('role:admin')->prefix('admin')->name('admin.tasks.')->group(function () {
        Route::get('/tasks',               [TaskController::class, 'index'])->name('index');
        Route::get('/tasks/create',        [TaskController::class, 'create'])->name('create');
        Route::post('/tasks',              [TaskController::class, 'store'])->name('store');
        Route::post('/tasks/{task}/verify', [TaskController::class, 'verify'])->name('verify');
        Route::post('/tasks/{task}/reject', [TaskController::class, 'reject'])->name('reject');
    });

    // staff only
    Route::middleware('role:staff')->prefix('staff')->name('staff.tasks.')->group(function () {
        Route::get('/tasks',                  [TaskController::class, 'myTasks'])->name('index');
        Route::patch('/tasks/{task}/progress', [TaskController::class, 'updateProgress'])->name('progress');
        Route::post('/tasks/{task}/submit',    [TaskController::class, 'submit'])->name('submit');
    });
});