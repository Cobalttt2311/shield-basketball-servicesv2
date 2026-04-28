<?php

namespace App\Modules\Admin\Repositories\Interfaces;

use App\Modules\Admin\Models\Group;

interface IGroupRepository
{
    public function getAll();
    public function findById(int $id): ?Group;
    public function create(array $data): Group;
    public function update(int $id, array $data): ?Group;
    public function delete(int $id): bool;
}