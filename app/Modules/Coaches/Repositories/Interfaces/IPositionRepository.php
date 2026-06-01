<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface IPositionRepository
{
    public function getAll();

    public function getByGroupId(
        int $groupId
    );

    public function findById(
        int $id
    );

    public function create(
        array $data
    );

    public function update(
        int $id,
        array $data
    );

    public function delete(
        int $id
    );
}