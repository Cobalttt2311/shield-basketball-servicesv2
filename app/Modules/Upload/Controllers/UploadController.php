<?php

namespace App\Modules\Upload\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Utils\Responses\BaseResponse;

use App\Modules\Upload\Services\Interfaces\IUploadService;

class UploadController extends Controller
{
    protected IUploadService $service;

    public function __construct(
        IUploadService $service
    ) {
        $this->service = $service;
    }

    public function upload(
        Request $request
    ) {
        try {

            $request->validate([
                'file' => 'required|file|max:51200'
            ]);

            $result = $this->service
                ->uploadFile(
                    $request->file('file')
                );

            return response()->json(
                (new BaseResponse(
                    true,
                    'File uploaded successfully',
                    $result
                ))->toArray(),
                201
            );

        } catch (Throwable $e) {

            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function getAll()
    {
        try {

            return response()->json(
                (new BaseResponse(
                    true,
                    'Files retrieved successfully',
                    $this->service->getAllFiles()
                ))->toArray()
            );

        } catch (Throwable $e) {

            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function getById(
        $id
    ) {
        try {

            return response()->json(
                (new BaseResponse(
                    true,
                    'File retrieved successfully',
                    $this->service->getFileById($id)
                ))->toArray()
            );

        } catch (Throwable $e) {

            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function delete(
        $id
    ) {
        try {

            $this->service
                ->deleteFile($id);

            return response()->json(
                (new BaseResponse(
                    true,
                    'File deleted successfully'
                ))->toArray()
            );

        } catch (Throwable $e) {

            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }
}