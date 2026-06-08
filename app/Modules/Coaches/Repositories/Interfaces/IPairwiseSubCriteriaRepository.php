<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface IPairwiseSubCriteriaRepository
{
    public function getSubCriteriaByCriteria(
        int $criteriaId
    );

    public function deleteByPositionAndCriteria(
        int $positionId,
        int $criteriaId
    );

    public function insertMany(
        array $data
    );

    public function getValue(
        int $positionId,
        int $criteriaId,
        int $firstId,
        int $secondId
    );

    public function saveValue(
        int $positionId,
        int $criteriaId,
        int $firstId,
        int $secondId,
        float $value
    );

    public function getByPositionAndCriteria(
        int $positionId,
        int $criteriaId
    );
}
