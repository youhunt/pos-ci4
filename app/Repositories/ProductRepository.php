<?php

namespace App\Repositories;

use App\Models\ProductModel;

class ProductRepository extends SyncRepository
{
    protected $product;

    public function __construct()
    {
        $this->product = new ProductModel();
    }

    public function findById($id)
    {
        return $this->product->find($id);
    }

    public function findByBarcode($barcode)
    {
        return $this->product
            ->where('barcode', $barcode)
            ->where('is_active', 1)
            ->first();
    }

    public function search($keyword)
    {
        return $this->product
            ->where('is_active', 1)
            ->groupStart()
                ->like('name', $keyword)
                ->orLike('barcode', $keyword)
            ->groupEnd()
            ->limit(20)
            ->find();
    }

    public function getPromoEligibleProducts($shopId)
    {
        return $this->product
            ->where('shop_id', $shopId)
            ->where('is_active', 1)
            ->where('is_promo_eligible', 1)
            ->findAll();
    }

    // 🔑 INCREMENTAL SYNC (HANYA PRODUK AKTIF)
    public function getForSync(
        int $shopId,
        ?string $since = null,
        int $limit = 500
    ): array {
        $builder = $this->product->builder();

        $builder
            ->select([
                'id',
                'shop_id',
                'sku',
                'name',
                'barcode',
                'price',
                'stock',
                'is_active',
                'updated_at'
            ])
            ->where('shop_id', $shopId)
            ->where('is_active', 1)
            ->orderBy('updated_at', 'asc');

        $this->applySince($builder, $since);
        $this->applyLimit($builder, $limit);

        return $builder->get()->getResultArray();
    }
}

