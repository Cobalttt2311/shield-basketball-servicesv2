<?php

namespace App\Modules\Admin\Repositories;

use App\Modules\Admin\Models\Group;
use App\Modules\Admin\Repositories\Interfaces\IGroupRepository;

class GroupRepository implements IGroupRepository
{
    public function getAll()
    {
        return Group::all();
    }

    public function findById(int $id): ?Group
    {
        return Group::find($id);
    }

    public function createGroup(array $data): Group
    {
        return Group::create($data);
    }

    public function updateGroup(int $id, array $data): ?Group
    {
        $group = $this->findById($id);
        if (! $group) {
            return null;
        }

        $group->update($data);

        return $group;
    }

    public function deleteGroup(int $id): bool
    {
        $group = $this->findById($id);
        if (! $group) {
            return false;
        }

        return $group->delete();
    }
}
