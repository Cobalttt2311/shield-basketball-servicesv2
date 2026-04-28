<?php

namespace App\Modules\Admin\Services\Interfaces;

interface IGroupService
{
    public function getAll();
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
}