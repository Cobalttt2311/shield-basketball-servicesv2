<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Admin\Repositories\Interfaces\IManagementDataRepository;
use App\Modules\Coaches\Repositories\Interfaces\ICriteriaRepository;
use App\Modules\Coaches\Services\Interfaces\ICriteriaService;
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

    public function createCriteria(array $data)
    {
        $user = Auth::user();

        $coach = $this->managementRepo->findCoachByUserId($user->id);

        if (! $coach) {
            throw new Exception('Coach not found');
        }

        if ($this->repo->checkCriteriaExists($data['name'], $coach->group_id)) {
            throw new Exception('Criteria already exists');
        }

        return $this->repo->createCriteria([
            'name' => $data['name'],
            'group_id' => $coach->group_id,
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

        if ($criteria->group_id != $coach->group_id) {
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

        if ($criteria->group_id != $coach->group_id) {
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

        if ($criteria->group_id != $coach->group_id) {
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
}
