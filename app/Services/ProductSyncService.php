<?php

namespace App\Services;

use App\Models\ProductModel;
use App\Repositories\ProductRepository;

class ProductSyncService
{
    protected $productModel;
    protected $repo;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->repo = new ProductRepository();
    }

    public function pull(int $shopId, ?string $since = null, int $limit = 500): array
    {
        $items = $this->repo->getForSync($shopId, $since, $limit);

        return [
            'count'     => count($items),
            'last_sync' => date('Y-m-d H:i:s'),
            'items'     => $items
        ];
    }
}
