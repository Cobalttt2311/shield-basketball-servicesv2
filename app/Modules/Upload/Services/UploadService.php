<?php

namespace App\Modules\Upload\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Modules\Upload\Services\Interfaces\IUploadService;
use App\Modules\Upload\Repositories\Interfaces\IUploadRepository;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

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

        $blobClient = BlobRestProxy::createBlobService(
            sprintf(
                "DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s",
                env('AZURE_STORAGE_NAME'),
                env('AZURE_STORAGE_KEY')
            )
        );

        $fileName = time() . '_' . $file->getClientOriginalName();

        $content = fopen(
            $file->getRealPath(),
            'r'
        );

        $blobClient->createBlockBlob(
            env('AZURE_STORAGE_CONTAINER'),
            $fileName,
            $content
        );

        $url = sprintf(
            'https://%s.blob.core.windows.net/%s/%s',
            env('AZURE_STORAGE_NAME'),
            env('AZURE_STORAGE_CONTAINER'),
            $fileName
        );

        return $this->repo->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $fileName,
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

        $blobClient = BlobRestProxy::createBlobService(
            sprintf(
                "DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s",
                env('AZURE_STORAGE_NAME'),
                env('AZURE_STORAGE_KEY')
            )
        );

        if (
            !empty($file->file_path) &&
            Storage::disk('azure')->exists(
                $file->file_path
            )
        ) {
            $blobClient->deleteBlob(
                env('AZURE_STORAGE_CONTAINER'),
                $file->file_path
            );
        }

        return $this->repo
            ->delete($id);
    }
}