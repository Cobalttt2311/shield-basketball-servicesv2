<?php

use App\Modules\Admin\Controllers\GroupController;
use App\Modules\Admin\Controllers\ManagementDataController;
use App\Modules\Coaches\Controllers\CriteriaController;
use App\Modules\Coaches\Controllers\EvaluationController;
use App\Modules\Coaches\Controllers\EvaluationReportController;
use App\Modules\Coaches\Controllers\PairwiseCriteriaController;
use App\Modules\Coaches\Controllers\PairwiseSetController;
use App\Modules\Coaches\Controllers\PairwiseSubCriteriaController;
use App\Modules\Coaches\Controllers\PositionController;
use App\Modules\User\Controllers\UserController;
use App\Modules\Coaches\Controllers\PlayerScoreMappingController;
use App\Modules\Upload\Controllers\UploadController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\MasterCoachMiddleware;
use App\Http\Middleware\HeadCoachMiddleware;


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
Route::middleware(['auth:api', 'role:coach', 'master.coach'])->group(function () {
    Route::prefix('pairwise-criteria')->group(function () {
        Route::post('/generate', [PairwiseCriteriaController::class, 'generate']);
        Route::put('/save', [PairwiseCriteriaController::class, 'save']);
        Route::get('/', [PairwiseCriteriaController::class, 'getPairwise']);
        Route::post('/calculate-weights', [PairwiseCriteriaController::class, 'calculateWeights']);
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
        Route::post('/generate', [PairwiseSubCriteriaController::class, 'generate']);
        Route::put('/save', [PairwiseSubCriteriaController::class, 'save']);
        Route::get('/', [PairwiseSubCriteriaController::class, 'getPairwise']);
        Route::post('/calculate-weights', [PairwiseSubCriteriaController::class, 'calculateWeights']);
    });
});
Route::get('/alternative-weight/{evaluationId}/{subCriteriaId}', [PlayerScoreMappingController::class, 'calculate']);
Route::get('/alternative-score/{evaluationId}/{positionId}', [PlayerScoreMappingController::class, 'calculateAlternativeScores']);
Route::get('/alternative-score/{positionId}', [PlayerScoreMappingController::class, 'calculateAlternativeScoresByPosition']);
Route::get('/recommendations/{evaluationId}', [PlayerScoreMappingController::class, 'getPositionRecommendations']);

Route::get('/alternative-weight/{evaluationId}/{subCriteriaId}',[PlayerScoreMappingController::class, 'calculate']);
Route::post('forgot-password', [UserController::class, 'forgotPassword']);
Route::post('reset-password', [UserController::class, 'resetPassword']);

Route::middleware(['auth:api'])->group(function () {

    Route::get('groups', [GroupController::class, 'index']);

    Route::prefix('auth')->group(function () {
        Route::post('/logout', [UserController::class, 'logout']);
    });

    Route::middleware('role:player')->group(function (){
        Route::prefix('player')->group(function (){
            Route::get('/profile', [UserController::class, 'getProfile']);
            Route::put('/profile', [UserController::class, 'updateProfile']);   
            Route::post('/change-password', [UserController::class, 'updatePassword']);   
        });
    });

    Route::middleware(['role:admin'])->group(function () {

        Route::prefix('groups')->group(function () {
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

    Route::middleware(['role:coach,admin'])->group(function () {
        Route::prefix('players')->group(function () {
            Route::get('/', [ManagementDataController::class, 'getPlayers']);
            Route::get('/detail/{id}', [ManagementDataController::class, 'getPlayerDetail']);
            Route::post('/', [ManagementDataController::class, 'storePlayer']);
            Route::put('/{id}', [ManagementDataController::class, 'updatePlayer']);
            Route::delete('/{id}', [ManagementDataController::class, 'deletePlayer']);
        });
    });
    
    Route::get('evaluation-reports/my-report/{evaluationId}', [EvaluationReportController::class, 'getPlayerReport']);
    Route::get('evaluation-reports/my-reports', [EvaluationReportController::class, 'getMyReportsList']);
    
    Route::middleware(['role:coach'])->group(function () {
        
        Route::prefix('coach')->group(function (){
            Route::get('/profile', [UserController::class, 'getProfile']);
            Route::put('/profile', [UserController::class, 'updateProfile']);   
            Route::post('/change-password', [UserController::class, 'updatePassword']);   
        });
        
        Route::prefix('criteria')->group(function () {
            Route::get('/me', [CriteriaController::class, 'getMyCriteria']);
            Route::get('/group/{groupId}', [CriteriaController::class, 'getCriteriaByGroupId']);
            Route::get('/{id}', [CriteriaController::class, 'getCriteriaById']);
            Route::get('/sub/all', [CriteriaController::class, 'getAllSubCriteria']);
            Route::get('/sub/criteria/{criteriaId}', [CriteriaController::class, 'getSubCriteriaByCriteria']);
            
            Route::middleware(['master.coach'])->group(function () {
                Route::post('/', [CriteriaController::class, 'createCriteria']);
                Route::put('/{id}', [CriteriaController::class, 'updateCriteria']);
                Route::delete('/{id}', [CriteriaController::class, 'deleteCriteria']);
                Route::post('/sub', [CriteriaController::class, 'createSubCriteria']);
                Route::put('/sub/{id}', [CriteriaController::class, 'updateSubCriteria']);
                Route::delete('/sub/{id}', [CriteriaController::class, 'deleteSubCriteria']);
            });
        });
        
        Route::prefix('evaluations')->group(function () {
            Route::post('/', [EvaluationController::class, 'createEvaluation']);
            Route::get('/all', [EvaluationController::class, 'getAllEvaluations']);
            Route::get('/{id}', [EvaluationController::class, 'getEvaluationById']);
            Route::put('/{id}', [EvaluationController::class, 'updateEvaluation']);
            Route::delete('/{id}', [EvaluationController::class, 'deleteEvaluation']);
            Route::post('/{id}/process-recommendation', [PlayerScoreMappingController::class, 'processRecommendation']);
        });
        
        Route::prefix('evaluation-scores')->group(function () {
            Route::post('/', [EvaluationController::class, 'createEvaluationScores']);
            Route::patch('/{id}', [EvaluationController::class, 'updateEvaluationScore']);
            Route::delete('/{id}', [EvaluationController::class, 'deleteEvaluationScore']);
        });
        
        Route::prefix('pairwise-sets')->group(function () {
            Route::get('/', [PairwiseSetController::class, 'getCompatibleSets']);
            Route::middleware(['master.coach'])->post('/', [PairwiseSetController::class, 'createSet']);
            Route::middleware(['master.coach'])->put('/{id}', [PairwiseSetController::class, 'updateSet']);
        });
        
        Route::post('evaluation-reports/finalize', [EvaluationReportController::class, 'finalizeReport']);
        Route::get('evaluation-reports/evaluation/{evaluationId}/player/{playerId}', [EvaluationReportController::class, 'getFinalizedReport']);
        Route::get('evaluation-reports/evaluation/{evaluationId}/players', [EvaluationReportController::class, 'getPlayersForFinalization']);
        // Route::prefix('pairwise-criteria')->group(function () {
        //     Route::post('/generate/{groupId}/{positionId}',[PairwiseCriteriaController::class, 'generate']);
        //     Route::put('/update',[PairwiseCriteriaController::class, 'update']);
        //     Route::get('/matrix/{groupId}/{positionId}',[PairwiseCriteriaController::class, 'matrix']);
        //     Route::get('/weights/{groupId}/{positionId}',[PairwiseCriteriaController::class, 'weights']);
        //     Route::post('/save-weight/{groupId}/{positionId}',[PairwiseCriteriaController::class, 'saveWeights']);
        //     Route::get('/consistency/{groupId}/{positionId}',[PairwiseCriteriaController::class, 'consistency']);
        // });

        // Route::prefix('pairwise-subcriteria')->group(function () {
        //     Route::post('/generate/{positionId}/{criteriaId}',[PairwiseSubCriteriaController::class, 'generate']);
        //     Route::put('/update',[PairwiseSubCriteriaController::class, 'update']);
        //     Route::get('/matrix/{positionId}/{criteriaId}',[PairwiseSubCriteriaController::class, 'matrix']);
        //     Route::get('/weights/{positionId}/{criteriaId}',[PairwiseSubCriteriaController::class, 'weights']);
        //     Route::post('/save-weight/{positionId}/{criteriaId}',[PairwiseSubCriteriaController::class, 'saveWeights']);
        //     Route::get('/consistency/{positionId}/{criteriaId}',[PairwiseSubCriteriaController::class, 'consistency']);
        // });
    });
});

Route::prefix('uploads')->group(function () {
    Route::post('/', [UploadController::class, 'upload']);
    Route::get('/', [UploadController::class, 'getAll']);
    Route::get('/{id}', [UploadController::class, 'getById']);
    Route::delete('/{id}', [UploadController::class, 'delete']);
});



