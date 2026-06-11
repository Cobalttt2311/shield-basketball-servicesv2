<?php

namespace App\Modules\Upload\Services\Interfaces;

use Illuminate\Http\UploadedFile;

interface IUploadService
{
    public function uploadFile(
        UploadedFile $file
    );

    public function getAllFiles();

    public function getFileById(
        int $id
    );

    public function deleteFile(
        int $id
    );
}