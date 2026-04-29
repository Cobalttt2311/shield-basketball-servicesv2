<?php

use Illuminate\Support\Facades\Route;
use App\Modules\User\Controllers\UserController;
use App\Modules\Admin\Controllers\GroupController;
use App\Modules\Admin\Controllers\ManagementDataController;
use App\Modules\Coaches\Controllers\CriteriaController;
use App\Modules\Coaches\Controllers\EvaluationController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
});

Route::post('forgot-password', [UserController::class, 'forgotPassword']);
Route::post('reset-password', [UserController::class, 'resetPassword']);

Route::middleware(['auth:api'])->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/logout', [UserController::class, 'logout']);
    });

    Route::middleware(['role:admin'])->group(function () {

        Route::prefix('groups')->group(function () {
            Route::get('/', [GroupController::class, 'index']);
            Route::post('/', [GroupController::class, 'store']);
            Route::put('/{id}', [GroupController::class, 'update']);
            Route::delete('/{id}', [GroupController::class, 'destroy']);
        });

        Route::prefix('coaches')->group(function () {
            Route::get('/', [ManagementDataController::class, 'getCoaches']);
            Route::get('/detail/{id}', [ManagementDataController::class, 'getCoachDetail']); 
            Route::post('/', [ManagementDataController::class, 'storeCoach']);
            Route::put('/{id}', [ManagementDataController::class, 'updateCoach']);
            Route::delete('/{id}', [ManagementDataController::class, 'deleteCoach']);
        });

        Route::prefix('players')->group(function () {
            Route::get('/', [ManagementDataController::class, 'getPlayers']);
            Route::get('/detail/{id}', [ManagementDataController::class, 'getPlayerDetail']); 
            Route::post('/', [ManagementDataController::class, 'storePlayer']);
            Route::put('/{id}', [ManagementDataController::class, 'updatePlayer']);
            Route::delete('/{id}', [ManagementDataController::class, 'deletePlayer']);
        });

    });

    Route::middleware(['role:coach'])->group(function () {

        Route::prefix('criteria')->group(function () {
            Route::get('/me', [CriteriaController::class, 'getMyCriteria']);
            Route::post('/', [CriteriaController::class, 'storeCriteria']);
            Route::post('/sub', [CriteriaController::class, 'storeSubCriteria']);
        });

        Route::prefix('evaluations')->group(function () {
            Route::post('/', [EvaluationController::class, 'store']);
        });
    });

});