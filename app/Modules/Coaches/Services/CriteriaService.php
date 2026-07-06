<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Admin\Models\Group;
use App\Modules\Admin\Repositories\Interfaces\IManagementDataRepository;
use App\Modules\Coaches\Repositories\Interfaces\ICriteriaRepository;
use App\Modules\Coaches\Services\Interfaces\ICriteriaService;
use App\Utils\Messages\ErrorMessages\ErrorMessages;
use Exception;
use Illuminate\Support\Facades\Auth;

class CriteriaService implements ICriteriaService
{
    protected ICriteriaRepository $repo;

    protected IManagementDataRepository $managementRepo;

    public function __construct(
        ICriteriaRepository $repo,
        IManagementDataRepository $managementRepo
    ) {
        $this->repo = $repo;
        $this->managementRepo = $managementRepo;
    }

    public function getMyCriteria()
    {
        $user = Auth::user();

        $coach = $this->managementRepo->findCoachByUserId($user->id);

        if (! $coach) {
            throw new Exception('Coach not found');
        }

        return $this->repo->getByGroup($coach->group_id);
    }

    public function getCriteriaById(int $id)
    {
        $criteria = $this->repo->findCriteriaById($id);

        if (! $criteria) {
            throw new Exception('Criteria not found');
        }

        return $criteria;
    }

    public function getCriteriaByGroupId(int $groupId)
    {
        return $this->repo->getCriteriaByGroupId($groupId);
    }

    public function getCriteriaBySetId(int $setId)
    {
        return $this->repo->getBySet($setId);
    }

    public function createCriteria(array $data)
    {
        $user = Auth::user();

        $coach = $this->managementRepo->findCoachByUserId($user->id);

        if (! $coach) {
            throw new Exception('Coach not found');
        }

        $setId = $data['criteria_set_id'] ?? null;
        if (! $setId) {
            throw new Exception('Criteria set ID is required');
        }

        if ($this->repo->checkCriteriaExists($data['name'], $setId)) {
            throw new Exception('Criteria already exists');
        }

        return $this->repo->createCriteria([
            'name' => $data['name'],
            'criteria_set_id' => $setId,
        ]);
    }

    public function updateCriteria(int $id, array $data)
    {
        $criteria = $this->repo->findCriteriaById($id);

        if (! $criteria) {
            throw new Exception('Criteria not found');
        }

        $user = Auth::user();

        $coach = $this->managementRepo->findCoachByUserId($user->id);

        $isMaster = in_array('master_coach', $user->roles ?? []);

        if (! $isMaster && $criteria->criteriaSet?->group_id != $coach->group_id) {
            throw new Exception('Forbidden: different group');
        }

        return $this->repo->updateCriteria($id, [
            'name' => $data['name'],
        ]);
    }

    public function deleteCriteria(int $id)
    {
        $criteria = $this->repo->findCriteriaById($id);

        if (! $criteria) {
            throw new Exception('Criteria not found');
        }

        $user = Auth::user();

        $coach = $this->managementRepo->findCoachByUserId($user->id);

        $isMaster = in_array('master_coach', $user->roles ?? []);

        if (! $isMaster && $criteria->criteriaSet?->group_id != $coach->group_id) {
            throw new Exception('Forbidden: different group');
        }

        return $this->repo->deleteCriteria($id);
    }

    public function createSubCriteria(array $data)
    {
        $user = Auth::user();

        $coach = $this->managementRepo->findCoachByUserId($user->id);

        if (! $coach) {
            throw new Exception('Coach not found');
        }

        $criteria = $this->repo->findCriteriaById($data['criteria_id']);

        if (! $criteria) {
            throw new Exception('Criteria not found');
        }

        $isMaster = in_array('master_coach', $user->roles ?? []);

        if (! $isMaster && $criteria->criteriaSet?->group_id != $coach->group_id) {
            throw new Exception('Forbidden: different group');
        }

        if ($this->repo->checkSubCriteriaExists(
            $data['name'],
            $data['criteria_id']
        )) {
            throw new Exception('Sub criteria already exists');
        }

        return $this->repo->createSubCriteria([
            'criteria_id' => $data['criteria_id'],
            'name' => $data['name'],
        ]);
    }

    public function getAllSubCriteria()
    {
        return $this->repo->getAllSubCriteria();
    }

    public function getSubCriteriaByCriteria(int $criteriaId)
    {
        return $this->repo->getSubCriteriaByCriteria($criteriaId);
    }

    public function updateSubCriteria(int $id, array $data)
    {
        $sub = $this->repo->findSubCriteriaById($id);

        if (! $sub) {
            throw new Exception('Sub criteria not found');
        }

        return $this->repo->updateSubCriteria($id, [
            'name' => $data['name'],
        ]);
    }

    public function deleteSubCriteria(int $id)
    {
        $sub = $this->repo->findSubCriteriaById($id);

        if (! $sub) {
            throw new Exception('Sub criteria not found');
        }

        return $this->repo->deleteSubCriteria($id);
    }

    public function getAllSets(): array
    {
        $sets = $this->repo->getAllSets();
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

        $set = $this->repo->createSet([
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
        $set = $this->repo->findSetById($id);
        if (! $set) {
            throw new \InvalidArgumentException('Criteria set not found');
        }

        $updateData = [];
        if (isset($data['group_id'])) {
            $groupExists = Group::where('id', $data['group_id'])->exists();
            if (! $groupExists) {
                throw new \InvalidArgumentException(ErrorMessages::GROUP_NOT_FOUND);
            }
            $updateData['group_id'] = $data['group_id'];
        }

        if (isset($data['name'])) {
            $updateData['name'] = $data['name'];
        }

        $set = $this->repo->updateSet($set, $updateData);

        return [
            'id' => $set->id,
            'name' => $set->name,
            'group_id' => $set->group_id,
        ];
    }

    public function deleteSet(int $id): bool
    {
        $set = $this->repo->findSetById($id);
        if (! $set) {
            throw new \InvalidArgumentException('Criteria set not found');
        }

        return $this->repo->deleteSet($set);
    }
}
