<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface IPositionService
{
    public function getAll();

    public function getByGroupId(
        int $groupId
    );

    public function getById(
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
