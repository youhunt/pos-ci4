<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\StockRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\PaymentRepository;
use App\Libraries\Discount\DiscountEngine;

class POSService
{
    protected $productRepo;
    protected $stockRepo;
    protected $transactionRepo;
    protected $paymentRepo;
    protected $discountEngine;

    public function __construct()
    {
        $this->productRepo      = new ProductRepository();
        $this->stockRepo        = new StockRepository();
        $this->paymentRepo      = new PaymentRepository();
        $this->transactionRepo  = new TransactionRepository();
        $this->discountEngine   = new DiscountEngine();
    }

    /**
     * MAIN CHECKOUT PROCESS
     */
    public function checkout(array $payload): array
    {
        $db = db_connect();
        $db->transBegin();

        try {
            $shopId = (int)$payload['shop_id'];
            $items  = $payload['items'];

            if (!$items) {
                throw new \RuntimeException('Cart kosong.');
            }

            // Hitung total (backend authoritative)
            $subtotal = 0;
            foreach ($items as $i) {
                $product = $this->stockRepo->getProductWithStock($shopId, $i['product_id']);

                if (!$product) {
                    throw new \RuntimeException('Produk tidak ditemukan.');
                }

                if ($product['stock'] < $i['qty']) {
                    throw new \RuntimeException("Stok tidak cukup untuk {$product['name']}.");
                }

                $subtotal += $product['price'] * $i['qty'];
            }

            $discount = 0; // nanti promo
            $grand    = $subtotal - $discount;
            $invoice = 'TRX-' . date('YmdHis') . '-' . random_int(100, 999);
            $paid   = (int)($payload['paid'] ?? $grand);
            $change = max(0, $paid - $grand);

            // Simpan header transaksi
            
            $trxId = $this->transactionRepo->createTransaction([
                'shop_id'         => $shopId,
                'invoice'         => $invoice,
                'user_id'         => $payload['user_id'] ?? null,
                'total_amount'    => $subtotal,
                'discount_amount' => $discount,
                'total_paid'      => $paid,
                'change_amount'   => $change,
                'payment_status'  => 'paid',
                'created_at'      => date('Y-m-d H:i:s'),
            ]);

            // Simpan item + update stok
            foreach ($items as $i) {
                $product = $this->stockRepo->getProductWithStock($shopId, $i['product_id']);

                $this->transactionRepo->addItem($trxId, [
                    'product_id' => $i['product_id'],
                    'qty'        => $i['qty'],
                    'price'      => $product['price'],
                    'line_total' => $product['price'] * $i['qty'],
                ]);

                $this->stockRepo->decreaseStock(
                    $shopId,
                    $i['product_id'],
                    $i['qty']
                );
            }

            $method = $payload['payment_method'] ?? 'cash';

            $this->paymentRepo->addPayment(
                $trxId,
                $method,
                $paid,
                'Auto generated'
            );

            $db->transCommit();

            return [
                'transaction_id' => $trxId,
                'invoice'        => $invoice,
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'grand_total'    => $grand,
                'paid'           => $paid,
                'change'         => $change,
                'items'          => $items,
            ];
        } catch (\Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }


    /**
     * CALCULATE ONLY (NO DB WRITE)
     */
    public function calculate(array $payload)
    {
        $shopId = $payload['shop_id'] ?? null;
        $items  = $payload['items'] ?? [];

        if (!$shopId || empty($items)) {
            throw new \RuntimeException("Data tidak lengkap.", 400);
        }

        $cartItems = [];
        $subtotal  = 0;

        foreach ($items as $row) {

            $product = $this->productRepo->findById($row['product_id']);
            if (!$product) {
                throw new \RuntimeException("Produk tidak ditemukan.", 400);
            }

            $qty = (int)$row['qty'];
            $lineTotal = $qty * $product['price'];
            $subtotal += $lineTotal;

            $cartItems[] = [
                'product_id' => $row['product_id'],
                'qty'        => $qty,
                'price'      => $product['price'],
                'line_total' => $lineTotal,
                'product'    => $product,
                'discount'   => 0,
            ];
        }

        $discountResult = $this->discountEngine->apply($cartItems, $shopId);

        return [
            'subtotal'    => $subtotal,
            'discount'    => $discountResult['total_discount'],
            'grand_total' => $subtotal - $discountResult['total_discount'],
            'items'       => $discountResult['items_after_discount'],
        ];
    }

}
