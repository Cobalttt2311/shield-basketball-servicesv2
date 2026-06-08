<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Coaches\Services\Interfaces\IPositionService;
use App\Modules\Coaches\Repositories\Interfaces\IPositionRepository;

class PositionService
    implements IPositionService
{
    protected $repository;

    public function __construct(
        IPositionRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository
            ->getAll();
    }

    public function getByGroupId(
        int $groupId
    )
    {
        return $this->repository
            ->getByGroupId(
                $groupId
            );
    }

    public function getById(
        int $id
    )
    {
        return $this->repository
            ->findById($id);
    }

    public function create(
        array $data
    )
    {
        return $this->repository
            ->create($data);
    }

    public function update(
        int $id,
        array $data
    )
    {
        return $this->repository
            ->update(
                $id,
                $data
            );
    }

    public function delete(
        int $id
    )
    {
        return $this->repository
            ->delete($id);
    }
}