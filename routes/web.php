<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::middleware('auth')->group(function () {
    Route::prefix('/dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('index');

        Route::prefix('/api')->name('api.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\DashboardController::class, 'index'])->name('index');
        });
    });

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::prefix('/tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');

        Route::prefix('/api')->name('api.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\TaskController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Api\TaskController::class, 'store'])->name('store');
            Route::patch('/{task}', [\App\Http\Controllers\Api\TaskController::class, 'update'])->name('update');
            Route::patch('/{task}/status', [\App\Http\Controllers\Api\TaskController::class, 'changeStatus'])->name('status');
            Route::delete('/{task}', [\App\Http\Controllers\Api\TaskController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('/users')->name('users.')->group(function () {

        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
        });
    });
});
require __DIR__.'/auth.php';
