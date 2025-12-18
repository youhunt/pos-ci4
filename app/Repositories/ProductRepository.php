<?php

namespace App\Repositories;

use App\Models\ProductModel;

class ProductRepository
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
            ->first();
    }

    public function search($keyword)
    {
        return $this->product
            ->like('name', $keyword)
            ->orLike('barcode', $keyword)
            ->limit(20)
            ->find();
    }

    public function getPromoEligibleProducts($shopId)
    {
        return $this->product
            ->where('shop_id', $shopId)
            ->where('is_promo_eligible', 1)
            ->findAll();
    }

    // 🆕 KHUSUS POS SYNC
    public function getForSync(int $shopId, ?string $since = null): array
    {
        $db = \Config\Database::connect();

        $builder = $db->table('products p')
            ->select("
                p.id,
                p.shop_id,
                p.sku,
                p.barcode,
                p.name,
                p.price,
                p.updated_at,
                IFNULL(s.stock, 0) AS stock
            ")
            ->join(
                'stocks s',
                's.product_id = p.id AND s.shop_id = p.shop_id',
                'left'
            )
            ->where('p.shop_id', $shopId);

        if ($since) {
            $builder->where('p.updated_at >', $since);
        }

        return $builder->get()->getResultArray();
    }
}

