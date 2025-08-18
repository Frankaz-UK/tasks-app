<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome/Index', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::middleware('auth')->group(function () {
    Route::prefix('/dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('index');
    });

    Route::prefix('/api')->name('api.')->group(function () {
        Route::prefix('/dashboard')->name('dashboard.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\DashboardController::class, 'index'])->name('index');
        });


        Route::prefix('/tasks')->name('tasks.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\TaskController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Api\TaskController::class, 'store'])->name('store');
            Route::patch('/{task}', [\App\Http\Controllers\Api\TaskController::class, 'update'])->name('update');
            Route::patch('/{task}/status', [\App\Http\Controllers\Api\TaskController::class, 'changeStatus'])->name('status');
            Route::patch('/{task}/user', [\App\Http\Controllers\Api\TaskController::class, 'changeUser'])->name('user');
            Route::delete('/{task}', [\App\Http\Controllers\Api\TaskController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('/users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
        });
    });

    /* This section will be updated with less options (no delete for a start) and most functions transferred to the users section instead */
    Route::prefix('/profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::group(['middleware' => ['permission:user-delete']], function () {
            Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('/tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
    });

    Route::prefix('/users')->name('users.')->group(function () {
        // these routes to be added soon, add a user, edit a user, show/list users + store/update & delete
    });
});

require __DIR__.'/auth.php';
