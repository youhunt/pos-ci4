<?php

namespace App\Controllers\Api\Sync;

use App\Controllers\BaseController;

abstract class BaseSyncController extends BaseController
{
    protected function success(string $resource, array $payload = []): array
    {
        return [
            'status'      => 'ok',
            'resource'    => $resource,
            'server_time' => date('Y-m-d H:i:s'),
            'payload'     => $payload
        ];
    }

    protected function fail(string $resource, string $message, int $code = 400): array
    {
        return [
            'status'      => 'error',
            'resource'    => $resource,
            'server_time' => date('Y-m-d H:i:s'),
            'message'     => $message,
            'code'        => $code
        ];
    }

    protected function requireInt($value, string $field): int
    {
        $val = (int)$value;
        if ($val <= 0) {
            throw new \InvalidArgumentException("$field is required");
        }
        return $val;
    }
}
