<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Admin\Models\Group;
use App\Modules\Coaches\Models\CriteriaSet;
use App\Utils\Messages\ErrorMessages\ErrorMessages;

class CriteriaSetService
{
    public function getAllSets(): array
    {
        $sets = CriteriaSet::with('group')->get();
        $result = [];

        foreach ($sets as $set) {
            $result[] = [
                'id' => $set->id,
                'name' => $set->name,
                'group_id' => $set->group_id,
                'group_name' => $set->group ? $set->group->age_group : null,
                'created_at' => $set->created_at ? $set->created_at->toDateString() : null,
            ];
        }

        return $result;
    }

    public function createSet(array $data): array
    {
        $groupId = $data['group_id'] ?? null;
        if ($groupId) {
            $groupExists = Group::where('id', $groupId)->exists();
            if (! $groupExists) {
                throw new \InvalidArgumentException(ErrorMessages::GROUP_NOT_FOUND);
            }
        }

        $set = CriteriaSet::create([
            'name' => $data['name'],
            'group_id' => $groupId,
        ]);

        return [
            'id' => $set->id,
            'name' => $set->name,
            'group_id' => $set->group_id,
        ];
    }

    public function updateSet(int $id, array $data): array
    {
        $set = CriteriaSet::find($id);
        if (! $set) {
            throw new \InvalidArgumentException('Criteria set not found');
        }

        if (isset($data['group_id'])) {
            $groupExists = Group::where('id', $data['group_id'])->exists();
            if (! $groupExists) {
                throw new \InvalidArgumentException(ErrorMessages::GROUP_NOT_FOUND);
            }
            $set->group_id = $data['group_id'];
        }

        if (isset($data['name'])) {
            $set->name = $data['name'];
        }

        $set->save();

        return [
            'id' => $set->id,
            'name' => $set->name,
            'group_id' => $set->group_id,
        ];
    }

    public function deleteSet(int $id): bool
    {
        $set = CriteriaSet::find($id);
        if (! $set) {
            throw new \InvalidArgumentException('Criteria set not found');
        }

        return $set->delete();
    }
}
