<?php

namespace App\Modules\Coaches\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coaches\Services\PairwiseCriteriaService;
use Illuminate\Http\Request;

class PairwiseCriteriaController extends Controller
{
    protected $service;

    public function __construct(
        PairwiseCriteriaService $service
    ) {
        $this->service = $service;
    }

    public function generate(
        int $groupId,
        int $positionId
    ) {
        $this->service
            ->generatePairwise(
                $groupId,
                $positionId
            );

        return response()->json([
            'message' => 'Pairwise generated successfully',
        ]);
    }

    public function save(
        Request $request
    ) {

        $validated =
            $request->validate([

                '*.position_id' => 'required|exists:positions,id',

                '*.criteria_first_id' => 'required|exists:criteria,id',

                '*.criteria_second_id' => 'required|exists:criteria,id',

                '*.value' => 'required|numeric|min:0.111|max:9',
            ]);

        $this->service
            ->saveValue(
                $validated
            );

        return response()->json([
            'message' => 'Pairwise updated successfully',
        ]);
    }

    public function matrix(
        int $groupId,
        int $positionId
    ) {
        return response()->json(
            $this->service
                ->generateMatrix(
                    $groupId,
                    $positionId
                )
        );
    }

    public function weights(
        int $groupId,
        int $positionId
    ) {
        return response()->json(
            $this->service
                ->calculateWeights(
                    $groupId,
                    $positionId
                )
        );
    }

    public function saveWeights(
        int $groupId,
        int $positionId
    ) {
        $this->service
            ->saveWeights(
                $groupId,
                $positionId
            );

        return response()->json([
            'message' => 'Weights saved successfully',
        ]);
    }

    public function consistency(
        int $groupId,
        int $positionId
    ) {
        return response()->json(
            $this->service
                ->calculateConsistencyRatio(
                    $groupId,
                    $positionId
                )
        );
    }
}
