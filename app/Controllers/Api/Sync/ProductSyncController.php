<?php

namespace App\Controllers\Api\Sync;

use App\Services\ProductSyncService;

class ProductSyncController extends BaseSyncController
{
    protected $service;

    public function __construct()
    {
        $this->service = new ProductSyncService();
    }

    /**
     * GET /api/sync/products
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
                $this->success('products', $payload)
            );

        } catch (\Throwable $e) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(
                    $this->fail('products', $e->getMessage())
                );
        }
    }
}
