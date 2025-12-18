<?php

namespace App\Services;

use App\Models\ProductModel;

class ProductSyncService
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function pull(int $shopId, ?string $since = null, int $limit = 500): array
    {
        $query = $this->productModel
            ->where('shop_id', $shopId);

        if ($since) {
            $query->where('updated_at >', $since);
        }

        $rows = $query
            ->orderBy('updated_at', 'ASC')
            ->findAll($limit);

        $lastSync = null;
        foreach ($rows as $row) {
            if (!$lastSync || $row['updated_at'] > $lastSync) {
                $lastSync = $row['updated_at'];
            }
        }

        return [
            'count'     => count($rows),
            'last_sync' => $lastSync,
            'items'     => $rows
        ];
    }
}
