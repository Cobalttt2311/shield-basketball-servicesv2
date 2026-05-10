<?php

namespace App\Modules\Admin\Services;

use App\Modules\Admin\Services\Interfaces\IGroupService;
use App\Modules\Admin\Repositories\Interfaces\IGroupRepository;

class GroupService implements IGroupService
{
    protected IGroupRepository $repo;

    public function __construct(IGroupRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function createGroup(array $data)
    {
        return $this->repo->createGroup($data);
    }

    public function updateGroup(int $id, array $data)
    {
        return $this->repo->updateGroup($id, $data);
    }

    public function deleteGroup(int $id)
    {
        return $this->repo->deleteGroup($id);
    }
}