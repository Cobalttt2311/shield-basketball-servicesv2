<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface IPairwiseSubCriteriaRepository
{
    public function getSubCriteria(
        int $criteriaId
    );
    public function truncate(
        int $criteriaId
    );
    public function insertMany(array $data);
    public function updateValue(
        int $criteriaId,
        int $firstId,
        int $secondId,
        float $value
    );
    public function getValue(
        int $criteriaId,
        int $firstId,
        int $secondId
    );

}