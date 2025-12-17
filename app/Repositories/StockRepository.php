<?php

namespace App\Repositories;

use App\Models\ProductModel;
use App\Models\StockModel;

class StockRepository
{
    protected $product;
    protected StockModel $stock;

    public function __construct()
    {
        $this->product = new ProductModel();
        $this->stock   = new StockModel();
    }

    public function getStock($shopId, $productId)
    {
        $row = $this->product
            ->where('shop_id', $shopId)
            ->where('id', $productId)
            ->first();

        return $row['stock'] ?? 0;
    }

    public function reduceStock($shopId, $productId, $qty)
    {
        $this->product
            ->where('shop_id', $shopId)
            ->where('id', $productId)
            ->set('stock', "stock - {$qty}", false)
            ->update();
    }

    public function getProductWithStock(int $shopId, int $productId)
    {
        return $this->product
            ->select('products.*, stocks.qty as stock')
            ->join('stocks', 'stocks.product_id = products.id')
            ->where('products.id', $productId)
            ->where('stocks.shop_id', $shopId)
            ->first();
    }

    public function decreaseStock(int $shopId, int $productId, int $qty): void
    {
        $this->stock
            ->where('shop_id', $shopId)
            ->where('product_id', $productId)
            ->decrement('qty', $qty);
    }
}
