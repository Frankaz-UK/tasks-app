<?php

use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\TaskController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\IndexController;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
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
            Route::get('/', [\App\Http\Controllers\Api\Auth\DashboardController::class, 'index'])->name('index');
        });

        Route::prefix('/tasks')->name('tasks.')->group(function () {
            Route::group(['middleware' => ['permission:task-list']], function () {
                Route::get('/', [\App\Http\Controllers\Api\Auth\TaskController::class, 'index'])->name('index');
            });
            Route::group(['middleware' => ['permission:task-create']], function () {
                Route::post('/', [\App\Http\Controllers\Api\Auth\TaskController::class, 'store'])->name('store')->middleware([HandlePrecognitiveRequests::class]);
            });
            Route::group(['middleware' => ['permission:task-update']], function () {
                Route::patch('/{task}', [\App\Http\Controllers\Api\Auth\TaskController::class, 'update'])->name('update')->middleware([HandlePrecognitiveRequests::class]);
                Route::patch('/{task}/user', [\App\Http\Controllers\Api\Auth\TaskController::class, 'changeUser'])->name('user')->middleware([HandlePrecognitiveRequests::class]);
            });
            Route::group(['middleware' => ['permission:task-complete']], function () {
                Route::patch('/{task}/status', [\App\Http\Controllers\Api\Auth\TaskController::class, 'changeStatus'])->name('status');
            });
            Route::group(['middleware' => ['permission:task-delete']], function () {
                Route::delete('/{task}', [\App\Http\Controllers\Api\Auth\TaskController::class, 'destroy'])->name('destroy');
            });
        });

        Route::prefix('/titles')->name('titles.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Api\Auth\UserController::class, 'getTitlesList'])->name('list');
        });

        Route::prefix('/users')->name('users.')->group(function () {
            Route::group(['middleware' => ['permission:user-list']], function () {
                Route::get('/', [\App\Http\Controllers\Api\Auth\UserController::class, 'index'])->name('index');
            });
            Route::group(['middleware' => ['permission:user-show']], function () {
                Route::get('/list', [\App\Http\Controllers\Api\Auth\UserController::class, 'getUsersList'])->name('list');
            });
            Route::group(['middleware' => ['permission:user-create']], function () {
                Route::post('/', [\App\Http\Controllers\Api\Auth\UserController::class, 'store'])->name('store')->middleware([HandlePrecognitiveRequests::class]);
            });
            Route::group(['middleware' => ['permission:user-update']], function () {
                Route::patch('/{user}', [\App\Http\Controllers\Api\Auth\UserController::class, 'update'])->name('update')->middleware([HandlePrecognitiveRequests::class]);
            });
            Route::group(['middleware' => ['permission:user-delete']], function () {
                Route::delete('/{user}', [\App\Http\Controllers\Api\Auth\UserController::class, 'destroy'])->name('destroy');
            });
        });
    });

    /*
     * Might remove this section altogether? Not following the same standard. Maybe refactor or use users section instead?
     */
    Route::prefix('/profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::group(['middleware' => ['permission:user-delete']], function () {
            Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        });
    });

    Route::group(['middleware' => ['permission:task-list']], function () {
        Route::prefix('/tasks')->name('tasks.')->group(function () {
            Route::get('/', [TaskController::class, 'index'])->name('index');
        });
    });

    Route::group(['middleware' => ['permission:user-list']], function () {
        Route::prefix('/users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
        });
    });
});

require __DIR__.'/auth.php';
