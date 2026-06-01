<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface IPairwiseCriteriaRepository
{
    public function getCriteriaByGroup(
        int $groupId
    );

    public function deleteByPosition(
        int $positionId
    );

    public function insertMany(
        array $data
    );

    public function getByPosition(
        int $positionId
    );

    public function getValue(
        int $positionId,
        int $firstId,
        int $secondId
    );

    public function updateValue(
        int $positionId,
        int $firstId,
        int $secondId,
        float $value
    );
}