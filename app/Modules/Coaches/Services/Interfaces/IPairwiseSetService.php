<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface IPairwiseSetService
{
    public function getCompatibleSets(?int $evaluationId = null): array;

    public function createSet(array $data): array;

    public function updateSet(int $id, array $data): array;

    public function getWeights(int $id): array;

    public function calculateCRH(int $id): array;
}
