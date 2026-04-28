<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface ICriteriaRepository
{
    public function getByGroup(int $groupId);
    public function createCriteria(array $data);
    public function createSubCriteria(array $data);
    public function findCriteriaById(int $id);
    public function checkCriteriaExists(string $name, int $groupId): bool;
    public function checkSubCriteriaExists(string $name, int $criteriaId): bool;
}