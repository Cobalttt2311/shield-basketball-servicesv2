<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface IPairwiseSubCriteriaRepository
{
    public function getSubCriteriaByCriteria(
        int $criteriaId
    );

    public function deleteByPositionAndCriteria(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    );

    public function insertMany(
        array $data
    );

    public function getValue(
        int $positionId,
        int $criteriaId,
        int $firstId,
        int $secondId,
        ?int $pairwiseSetId = null
    );

    public function saveValue(
        int $positionId,
        int $criteriaId,
        int $firstId,
        int $secondId,
        float $value,
        ?int $pairwiseSetId = null
    );

    public function getByPositionAndCriteria(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    );

    public function getPairwise(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    );
}
