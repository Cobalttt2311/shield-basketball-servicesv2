<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\PairwiseSubCriteria;
use App\Modules\Coaches\Models\SubCriteria;
use App\Modules\Coaches\Repositories\Interfaces\IPairwiseSubCriteriaRepository;

class PairwiseSubCriteriaRepository implements IPairwiseSubCriteriaRepository
{
    public function getSubCriteriaByCriteria(
        int $criteriaId
    ) {
        return SubCriteria::where(
            'criteria_id',
            $criteriaId
        )
            ->orderBy('id')
            ->get();
    }

    public function deleteByPositionAndCriteria(
        int $positionId,
        int $criteriaId
    ) {
        return PairwiseSubCriteria::where(
            'position_id',
            $positionId
        )
            ->where(
                'criteria_id',
                $criteriaId
            )
            ->delete();
    }

    public function insertMany(
        array $data
    ) {
        return PairwiseSubCriteria::insert(
            $data
        );
    }

    public function getValue(
        int $positionId,
        int $criteriaId,
        int $firstId,
        int $secondId
    ) {
        return PairwiseSubCriteria::where(
            'position_id',
            $positionId
        )
            ->where(
                'criteria_id',
                $criteriaId
            )
            ->where(
                'sub_criteria_first_id',
                $firstId
            )
            ->where(
                'sub_criteria_second_id',
                $secondId
            )
            ->first();
    }

    public function saveValue(
        int $positionId,
        int $criteriaId,
        int $firstId,
        int $secondId,
        float $value
    ) {
        // $query = PairwiseSubCriteria::where(
        //     'position_id',
        //     $positionId
        // )
        // ->where(
        //     'criteria_id',
        //     $criteriaId
        // )
        // ->where(
        //     'sub_criteria_first_id',
        //     $firstId
        // )
        // ->where(
        //     'sub_criteria_second_id',
        //     $secondId
        // );

        // dd(
        //     $query->first()
        // );

        return PairwiseSubCriteria::where(
            'position_id',
            $positionId
        )
            ->where(
                'criteria_id',
                $criteriaId
            )
            ->where(
                'sub_criteria_first_id',
                $firstId
            )
            ->where(
                'sub_criteria_second_id',
                $secondId
            )
            ->update([
                'value' => $value,
            ]);
    }

    public function getByPositionAndCriteria(
        int $positionId,
        int $criteriaId
    ) {
        return PairwiseSubCriteria::where(
            'position_id',
            $positionId
        )
            ->where(
                'criteria_id',
                $criteriaId
            )
            ->with([
                'firstSubCriteria',
                'secondSubCriteria',
            ])
            ->get();
    }

    public function getPairwise(
        int $positionId,
        int $criteriaId
    ) {
        return PairwiseSubCriteria::with([
            'firstSubCriteria',
            'secondSubCriteria',
        ])
            ->where(
                'position_id',
                $positionId
            )
            ->where(
                'criteria_id',
                $criteriaId
            )
            ->orderBy(
                'sub_criteria_first_id'
            )
            ->orderBy(
                'sub_criteria_second_id'
            )
            ->get();
    }
}
