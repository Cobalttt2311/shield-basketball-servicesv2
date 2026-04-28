<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Models\SubCriteria;
use App\Modules\Coaches\Repositories\Interfaces\ICriteriaRepository;

class CriteriaRepository implements ICriteriaRepository
{
    public function getByGroup(int $groupId)
    {
        return Criteria::with('subCriteria')
            ->where('group_id', $groupId)
            ->get();
    }

    public function createCriteria(array $data)
    {
        return Criteria::create($data);
    }

    public function createSubCriteria(array $data)
    {
        return SubCriteria::create($data);
    }

    public function findCriteriaById(int $id)
    {
        return Criteria::find($id);
    }

    // ✅ pindahan dari EvaluationRepository
    public function findSubCriteriaWithCriteria(int $id)
    {
        return SubCriteria::with('criteria')->find($id);
    }

    public function checkCriteriaExists(string $name, int $groupId): bool
    {
        return Criteria::where('name', $name)
            ->where('group_id', $groupId)
            ->exists();
    }

    public function checkSubCriteriaExists(string $name, int $criteriaId): bool
    {
        return SubCriteria::where('name', $name)
            ->where('criteria_id', $criteriaId)
            ->exists();
    }
}