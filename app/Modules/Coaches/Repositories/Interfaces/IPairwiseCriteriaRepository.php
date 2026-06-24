<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface IPairwiseCriteriaRepository
{
    public function getCriteriaByGroup(
        int $groupId
    );

    public function deleteByPosition(
        int $positionId,
        ?int $pairwiseSetId = null
    );

    public function insertMany(
        array $data
    );

    public function getByPosition(
        int $positionId,
        ?int $pairwiseSetId = null
    );

    public function getValue(
        int $positionId,
        int $firstId,
        int $secondId,
        ?int $pairwiseSetId = null
    );

    public function saveValue(
        int $positionId,
        int $firstId,
        int $secondId,
        float $value,
        ?int $pairwiseSetId = null
    );

    public function getPairwise(
        int $groupId,
        int $positionId,
        ?int $pairwiseSetId = null
    );
}
