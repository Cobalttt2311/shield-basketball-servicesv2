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

Route::middleware(['auth:api'])->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/logout', [UserController::class, 'logout']);
    });

    Route::middleware(['role:admin'])->group(function () {

        Route::prefix('groups')->group(function () {
            Route::get('/', [GroupController::class, 'index']);
            Route::post('/', [GroupController::class, 'createGroup']);
            Route::put('/{id}', [GroupController::class, 'updateGroup']);
            Route::delete('/{id}', [GroupController::class, 'deleteGroup']);
        });

        Route::prefix('coaches')->group(function () {
            Route::get('/', [ManagementDataController::class, 'getCoaches']);
            Route::get('/detail/{id}', [ManagementDataController::class, 'getCoachDetail']); 
            Route::post('/', [ManagementDataController::class, 'storeCoach']);
            Route::put('/{id}', [ManagementDataController::class, 'updateCoach']);
            Route::delete('/{id}', [ManagementDataController::class, 'deleteCoach']);
        });

    });

    Route::middleware(['role:coach,admin'])->group(function (){
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

            Route::get('/group/{groupId}', [CriteriaController::class, 'getCriteriaByGroupId']);

            Route::get('/{id}', [CriteriaController::class, 'getCriteriaById']);

            Route::post('/', [CriteriaController::class, 'createCriteria']);

            Route::put('/{id}', [CriteriaController::class, 'updateCriteria']);

            Route::delete('/{id}', [CriteriaController::class, 'deleteCriteria']);

            Route::get('/sub/all', [CriteriaController::class, 'getAllSubCriteria']);

            Route::get('/sub/criteria/{criteriaId}', [CriteriaController::class, 'getSubCriteriaByCriteria']);

            Route::post('/sub', [CriteriaController::class, 'createSubCriteria']);

            Route::put('/sub/{id}', [CriteriaController::class, 'updateSubCriteria']);

            Route::delete('/sub/{id}', [CriteriaController::class, 'deleteSubCriteria']);
        });

        Route::prefix('evaluations')->group(function () {
            Route::post('/', [EvaluationController::class, 'createEvaluation']);
            Route::get('/all', [EvaluationController::class, 'getAllEvaluations']);
            Route::get('/{id}', [EvaluationController::class, 'getEvaluationById']);
            Route::put('/{id}', [EvaluationController::class, 'updateEvaluation']);
            Route::delete('/{id}', [EvaluationController::class, 'deleteEvaluation']);
        });

        Route::prefix('evaluation-scores')->group(function () {
            Route::post('/', [EvaluationController::class, 'createEvaluationScores']);
            Route::patch('/{id}', [EvaluationController::class, 'updateEvaluationScore']);
            Route::delete('/{id}', [EvaluationController::class, 'deleteEvaluationScore']);
        });
    });
});