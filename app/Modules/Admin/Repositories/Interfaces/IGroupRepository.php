<?php

namespace App\Modules\Admin\Repositories\Interfaces;

use App\Modules\Admin\Models\Group;

interface IGroupRepository
{
    public function getAll();
    public function findById(int $id): ?Group;
    public function createGroup(array $data): Group;
    public function updateGroup(int $id, array $data): ?Group;
    public function deleteGroup(int $id): bool;
}