<?php
namespace App\Services;

class BaseService
{
    protected function respondSuccess($data = [], $message = 'OK')
    {
        return ['success' => true, 'message' => $message, 'data' => $data];
    }

    protected function respondError($message = 'Error', $code = 400)
    {
        return ['success' => false, 'message' => $message, 'code' => $code];
    }
}
