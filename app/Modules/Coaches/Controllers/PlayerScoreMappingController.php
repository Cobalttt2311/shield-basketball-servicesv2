<?php

namespace App\Modules\Coaches\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coaches\Services\Interfaces\IPlayerScoreMappingService;

class PlayerScoreMappingController extends Controller
{
    protected $service;

    public function __construct(
        IPlayerScoreMappingService $service
    ) {
        $this->service = $service;
    }

    public function calculate(
        int $evaluationId,
        int $subCriteriaId
    ) {

        return response()->json(

            $this->service
                ->calculateAlternativeWeights(
                    $evaluationId,
                    $subCriteriaId
                )

        );
    }
}