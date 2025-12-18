<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\TransactionItemModel;

class DashboardController extends BaseController
{
    protected $trx;
    protected $item;

    public function __construct()
    {
        $this->trx  = new TransactionModel();
        $this->item = new TransactionItemModel();
    }

    public function summary()
    {
        $shopId = (int)$this->request->getGet('shop_id');
        $today  = date('Y-m-d');

        // Summary transaksi hari ini
        $summary = $this->trx
            ->select([
                'COUNT(id) as total_transactions',
                'SUM(total_amount) as total_sales'
            ])
            ->where('shop_id', $shopId)
            ->where('DATE(created_at)', $today)
            ->first();

        // Item terjual hari ini
        $items = $this->item
            ->select('SUM(transaction_items.qty) as total_items')
            ->join('transactions', 'transactions.id = transaction_items.transaction_id')
            ->where('transactions.shop_id', $shopId)
            ->where('DATE(transactions.created_at)', $today)
            ->first();

        $totalTransactions = (int)($summary['total_transactions'] ?? 0);
        $totalSales       = (float)($summary['total_sales'] ?? 0);
        $totalItems       = (int)($items['total_items'] ?? 0);

        return $this->response->setJSON([
            'status' => 'ok',
            'data' => [
                'total_sales'        => $totalSales,
                'total_transactions' => $totalTransactions,
                'total_items'        => $totalItems,
                'avg_transaction'    => $totalTransactions > 0
                    ? round($totalSales / $totalTransactions)
                    : 0
            ]
        ]);
    }
}
