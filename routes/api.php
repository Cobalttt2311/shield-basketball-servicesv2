<?php

use App\Modules\Admin\Controllers\GroupController;
use App\Modules\Admin\Controllers\ManagementDataController;
use App\Modules\Coaches\Controllers\CriteriaController;
use App\Modules\Coaches\Controllers\EvaluationController;
use App\Modules\Coaches\Controllers\PairwiseCriteriaController;
use App\Modules\Coaches\Controllers\PairwiseSubCriteriaController;
use App\Modules\Coaches\Controllers\PositionController;
use App\Modules\User\Controllers\UserController;
use App\Modules\Coaches\Controllers\PlayerScoreMappingController;
use App\Modules\Upload\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [UserController::class, 'login']);
});

Route::prefix('positions')->group(function () {
    Route::get('/', [PositionController::class, 'index']);
    Route::get('/group/{groupId}', [PositionController::class, 'getByGroup']);
    Route::get('/{id}', [PositionController::class, 'show']);
    Route::post('/', [PositionController::class, 'store']);
    Route::put('/{id}', [PositionController::class, 'update']);
    Route::delete('/{id}', [PositionController::class, 'destroy']);
});

Route::prefix('pairwise-criteria')->group(function () {
    Route::post('/generate/{groupId}/{positionId}', [PairwiseCriteriaController::class, 'generate']);
    Route::put('/save', [PairwiseCriteriaController::class, 'save']);
    Route::get('/matrix/{groupId}/{positionId}', [PairwiseCriteriaController::class, 'matrix']);
    Route::get('/weights/{groupId}/{positionId}', [PairwiseCriteriaController::class, 'weights']);
    Route::post('/save-weight/{groupId}/{positionId}', [PairwiseCriteriaController::class, 'saveWeights']);
    Route::get('/consistency/{groupId}/{positionId}', [PairwiseCriteriaController::class, 'consistency']);
});

Route::prefix('pairwise-subcriteria')->group(function () {
    Route::post('/generate/{positionId}/{criteriaId}', [PairwiseSubCriteriaController::class, 'generate']);
    Route::put('/save', [PairwiseSubCriteriaController::class, 'save']);
    Route::get('/matrix/{positionId}/{criteriaId}', [PairwiseSubCriteriaController::class, 'matrix']);
    Route::get('/weights/{positionId}/{criteriaId}', [PairwiseSubCriteriaController::class, 'weights']);
    Route::post('/save-weight/{positionId}/{criteriaId}', [PairwiseSubCriteriaController::class, 'saveWeights']);
    Route::get('/consistency/{positionId}/{criteriaId}', [PairwiseSubCriteriaController::class, 'consistency']);
});

Route::get('/alternative-weight/{evaluationId}/{subCriteriaId}',[PlayerScoreMappingController::class, 'calculate']);
Route::post('forgot-password', [UserController::class, 'forgotPassword']);
Route::post('reset-password', [UserController::class, 'resetPassword']);

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
            Route::get('/profile', [UserController::class, 'getProfile']);
            Route::put('/profile', [UserController::class, 'updateProfile']);   
            Route::post('/change-password', [UserController::class, 'updatePassword']);   
        });
    });

    Route::middleware(['role:coach,admin'])->group(function () {
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

Route::prefix('uploads')->group(function () {
    Route::post('/', [UploadController::class, 'upload']);
    Route::get('/', [UploadController::class, 'getAll']);
    Route::get('/{id}', [UploadController::class, 'getById']);
    Route::delete('/{id}', [UploadController::class, 'delete']);
});