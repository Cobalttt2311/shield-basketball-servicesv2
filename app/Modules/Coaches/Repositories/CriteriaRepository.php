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

    public function getCriteriaByGroupId(int $groupId)
    {
        return Criteria::with('subCriteria')
            ->where('group_id', $groupId)
            ->get();
    }

    public function createCriteria(array $data)
    {
        return Criteria::create($data);
    }

    public function updateCriteria(int $id, array $data)
    {
        /** @var Criteria|null $criteria */
        $criteria = Criteria::find($id);

        if (! $criteria) {
            return null;
        }

        $criteria->update($data);

        return $criteria;
    }

    public function deleteCriteria(int $id)
    {
        return Criteria::destroy($id);
    }

    public function findCriteriaById(int $id)
    {
        return Criteria::with('subCriteria')->find($id);
    }

    public function createSubCriteria(array $data)
    {
        return SubCriteria::create($data);
    }

    public function getAllSubCriteria()
    {
        return SubCriteria::with('criteria')->get();
    }

    public function getSubCriteriaByCriteria(int $criteriaId)
    {
        return SubCriteria::where('criteria_id', $criteriaId)
            ->with('criteria')
            ->get();
    }

    public function findSubCriteriaById(int $id)
    {
        return SubCriteria::with('criteria')->find($id);
    }

    public function updateSubCriteria(int $id, array $data)
    {
        /** @var SubCriteria|null $sub */
        $sub = SubCriteria::find($id);

        if (! $sub) {
            return null;
        }

        $sub->update($data);

        return $sub;
    }

    public function deleteSubCriteria(int $id)
    {
        return SubCriteria::destroy($id);
    }

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
