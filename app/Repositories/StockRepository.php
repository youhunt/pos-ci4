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

    /**
     * Ambil stok REAL dari ledger
     */
    public function getAvailableStock(int $shopId, int $productId): int
    {
        $in = $this->stock
            ->selectSum('qty')
            ->where([
                'shop_id' => $shopId,
                'product_id' => $productId,
                'type' => 'in'
            ])
            ->first()['qty'] ?? 0;

        $out = $this->stock
            ->selectSum('qty')
            ->where([
                'shop_id' => $shopId,
                'product_id' => $productId,
                'type' => 'out'
            ])
            ->first()['qty'] ?? 0;

        return (int)$in - (int)$out;
    }

    /**
     * Catat mutasi stok (OUT)
     */
    public function recordOut(
        int $shopId,
        int $productId,
        int $qty,
        string $note
    ): void {
        $this->stock->insert([
            'shop_id'    => $shopId,
            'product_id' => $productId,
            'type'       => 'out',
            'qty'        => $qty,
            'note'       => $note,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * (Opsional) Update snapshot di products.stock
     */
    public function syncProductStock(int $shopId, int $productId): void
    {
        $stock = $this->getAvailableStock($shopId, $productId);

        $this->product
            ->where('shop_id', $shopId)
            ->where('id', $productId)
            ->set('stock', $stock)
            ->update();
    }
}
