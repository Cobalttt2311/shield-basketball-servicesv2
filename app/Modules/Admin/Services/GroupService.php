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

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repo->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repo->delete($id);
    }
}