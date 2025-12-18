<?php

namespace App\Controllers\Api\Sync;

use App\Services\CategorySyncService;

class CategorySyncController extends BaseSyncController
{
    protected $service;

    public function __construct()
    {
        $this->service = new CategorySyncService();
    }

    /**
     * GET /api/sync/categories
     */
    public function pull()
    {
        try {
            $shopId = $this->requireInt(
                $this->request->getGet('shop_id'),
                'shop_id'
            );

            $since = $this->request->getGet('since');
            $limit = (int)($this->request->getGet('limit') ?? 500);

            $payload = $this->service->pull($shopId, $since, $limit);

            return $this->response->setJSON(
                $this->success('categories', $payload)
            );

        } catch (\Throwable $e) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(
                    $this->fail('categories', $e->getMessage())
                );
        }
    }
}
