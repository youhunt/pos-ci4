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
        return [
            'last_sync' => date('Y-m-d H:i:s'),
            'products'  => $this->repo->getForSync($shopId, $since),
        ];
    }
}
