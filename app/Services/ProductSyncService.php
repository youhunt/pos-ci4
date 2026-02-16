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

        // hitung last_sync dari data sebenarnya
        $lastSync = $since;

        if (!empty($items)) {
            $lastSync = max(array_column($items, 'updated_at'));
        }

        return [
            'count'     => count($items),
            'last_sync' => $lastSync,
            'items'     => $items
        ];
    }
}
