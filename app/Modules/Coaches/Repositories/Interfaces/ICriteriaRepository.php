<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface ICriteriaRepository
{
    public function getByGroup(int $groupId);

    public function getCriteriaByGroupId(int $groupId);

    public function createCriteria(array $data);

    public function updateCriteria(int $id, array $data);

    public function deleteCriteria(int $id);

    public function findCriteriaById(int $id);

    public function createSubCriteria(array $data);

    public function getAllSubCriteria();

    public function getSubCriteriaByCriteria(int $criteriaId);

    public function findSubCriteriaById(int $id);

    public function updateSubCriteria(int $id, array $data);

    public function deleteSubCriteria(int $id);

    public function findSubCriteriaWithCriteria(int $id);

    public function checkCriteriaExists(string $name, int $groupId): bool;

    public function checkSubCriteriaExists(string $name, int $criteriaId): bool;
}
