<?php

namespace App\Modules\Upload\Repositories;

use App\Modules\Upload\Models\Upload;
use App\Modules\Upload\Repositories\Interfaces\IUploadRepository;

class UploadRepository implements IUploadRepository
{
    public function create(array $data)
    {
        return Upload::create($data);
    }

    public function findById(int $id)
    {
        return Upload::find($id);
    }

    public function getAll()
    {
        return Upload::latest()->get();
    }

    public function delete(int $id)
    {
        return Upload::destroy($id);
    }
}