<?php

namespace App\Utils\Responses;

class BaseResponse
{
    public $success;
    public $message;
    public $data;
    public $error;

    public function __construct($success, $message, $data = null, $error = null)
    {
        $this->success = $success;
        $this->message = $message;
        $this->data = $data;
        $this->error = $error;
    }

    // Optional: Method untuk mengonversi ke array
    public function toArray()
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
            'error' => $this->error
        ];
    }
}