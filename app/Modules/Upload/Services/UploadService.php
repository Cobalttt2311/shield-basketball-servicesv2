<?php

namespace App\Modules\Upload\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Modules\Upload\Services\Interfaces\IUploadService;
use App\Modules\Upload\Repositories\Interfaces\IUploadRepository;

class UploadService implements IUploadService
{
    protected IUploadRepository $repo;

    public function __construct(
        IUploadRepository $repo
    ) {
        $this->repo = $repo;
    }

    public function uploadFile(
        UploadedFile $file
    ) {

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        $imageExtensions = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];

        $videoExtensions = [
            'mp4',
            'mov',
            'avi',
            'mkv'
        ];

        if (
            in_array(
                $extension,
                $imageExtensions
            )
        ) {

            $type = 'image';

        } elseif (
            in_array(
                $extension,
                $videoExtensions
            )
        ) {

            $type = 'video';

        } else {

            throw new Exception(
                'Unsupported file type'
            );
        }

        $path = $file->store(
            'uploads',
            'public'
        );

        $url = asset(
            'storage/' . $path
        );

        return $this->repo->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_url'  => $url,
            'file_type' => $type
        ]);
    }

    public function getAllFiles()
    {
        return $this->repo->getAll();
    }

    public function getFileById(
        int $id
    ) {

        $file = $this->repo
            ->findById($id);

        if (!$file) {
            throw new Exception(
                'File not found'
            );
        }

        return $file;
    }

    public function deleteFile(
        int $id
    ) {

        $file = $this->repo
            ->findById($id);

        if (!$file) {
            throw new Exception(
                'File not found'
            );
        }

        if (
            !empty($file->file_path) &&
            Storage::disk('public')->exists(
                $file->file_path
            )
        ) {
            Storage::disk('public')
                ->delete(
                    $file->file_path
                );
        }

        return $this->repo
            ->delete($id);
    }
}