<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    protected ProductRepository $repo;

    public function __construct()
    {
        $this->repo = new ProductRepository();
    }

    public function sync(int $shopId, ?string $since = null): array
    {
        $items = $this->repo->getForSync($shopId, $since);

        $lastSync = null;

        if (!empty($items)) {
            $lastSync = max(array_column($items, 'updated_at'));
        }

        return [
            'last_sync' => $lastSync,
            'products'  => $items,
        ];
    }
}
