<?php

namespace App\Modules\Coaches\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coaches\Services\PairwiseSubCriteriaService;
use Illuminate\Http\Request;

class PairwiseSubCriteriaController extends Controller
{
    protected $service;

    public function __construct(
        PairwiseSubCriteriaService $service
    ) {
        $this->service = $service;
    }

    public function generate(
        int $positionId,
        int $criteriaId
    ) {
        $this->service
            ->generatePairwise(
                $positionId,
                $criteriaId
            );

        return response()->json([
            'message' => 'Pairwise sub criteria generated',
        ]);
    }

    public function save(
        Request $request
    ) {
        // dd($request->all());

        $validated =
            $request->validate([

                '*.position_id' => 'required|exists:positions,id',

                '*.criteria_id' => 'required|exists:criteria,id',

                '*.sub_criteria_first_id' => 'required|exists:sub_criteria,id',

                '*.sub_criteria_second_id' => 'required|exists:sub_criteria,id',

                '*.value' => 'required|numeric|min:0.111|max:9',
            ]);

        $this->service
            ->saveValue(
                $validated
            );

        return response()->json([
            'message' => 'Pairwise sub criteria updated',
        ]);
    }

    public function matrix(
        int $positionId,
        int $criteriaId
    ) {
        return response()->json(
            $this->service
                ->generateMatrix(
                    $positionId,
                    $criteriaId
                )
        );
    }

    public function weights(
        int $positionId,
        int $criteriaId
    ) {
        return response()->json(
            $this->service
                ->calculateWeights(
                    $positionId,
                    $criteriaId
                )
        );
    }

    public function saveWeights(
        int $positionId,
        int $criteriaId
    ) {
        $this->service
            ->saveWeights(
                $positionId,
                $criteriaId
            );

        return response()->json([
            'message' => 'Sub criteria weights saved',
        ]);
    }

    public function consistency(
        int $positionId,
        int $criteriaId
    ) {
        return response()->json(
            $this->service
                ->calculateConsistencyRatio(
                    $positionId,
                    $criteriaId
                )
        );
    }
}
