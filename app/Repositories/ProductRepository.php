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
}
