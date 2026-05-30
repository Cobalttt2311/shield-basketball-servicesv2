<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface IPairwiseCriteriaRepository
{

    public function getCriteriaByGroup(int $groupId);
    public function truncateByGroup(int $groupId);
    public function insertMany(array $data);
    public function updateValue(
        int $groupId,
        int $firstId,
        int $secondId,
        float $value
    );
    public function getValue(
        int $groupId,
        int $firstId,
        int $secondId
    );
    public function getAllByGroup(int $groupId);
    

}