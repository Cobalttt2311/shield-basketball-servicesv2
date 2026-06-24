<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface IPairwiseSetService
{
    public function getCompatibleSets(int $evaluationId): array;

    public function createSet(array $data): array;
}
