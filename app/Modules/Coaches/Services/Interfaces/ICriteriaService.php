<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface ICriteriaService
{
    public function getMyCriteria();

    public function getCriteriaById(int $id);

    public function getCriteriaByGroupId(int $groupId);

    public function createCriteria(array $data);

    public function updateCriteria(int $id, array $data);

    public function deleteCriteria(int $id);

    public function createSubCriteria(array $data);

    public function getAllSubCriteria();

    public function getSubCriteriaByCriteria(int $criteriaId);

    public function updateSubCriteria(int $id, array $data);

    public function deleteSubCriteria(int $id);
}