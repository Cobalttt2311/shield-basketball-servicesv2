<?php

namespace App\Modules\Admin\Services\Interfaces;

interface IGroupService
{
    public function getAll();

    public function createGroup(array $data);

    public function updateGroup(int $id, array $data);

    public function deleteGroup(int $id);
}
