<?php

namespace App\Modules\Upload\Repositories\Interfaces;

interface IUploadRepository
{
    public function create(array $data);

    public function findById(int $id);

    public function getAll();

    public function delete(int $id);
}