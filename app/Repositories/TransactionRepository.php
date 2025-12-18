<?php

namespace App\Repositories;

use App\Models\TransactionModel;
use App\Models\TransactionItemModel;

class TransactionRepository
{
    protected $trx;
    protected $item;

    public function __construct()
    {
        $this->trx  = new TransactionModel();
        $this->item = new TransactionItemModel();
    }

    public function createTransaction(array $data)
    {
        $this->trx->insert($data);
        return $this->trx->getInsertID();
    }

    public function createTransactionItem($trxId, array $data)
    {
        $data['transaction_id'] = $trxId;
        $this->item->insert($data);
    }

    public function getTransactionWithItems($trxId)
    {
        $transaction = $this->trx->find($trxId);
        $items       = $this->item
            ->where('transaction_id', $trxId)
            ->findAll();

        return [
            'transaction' => $transaction,
            'items'       => $items,
        ];
    }

    public function addItem(int $trxId, array $data): void
    {
        $data['transaction_id'] = $trxId;
        $this->item->insert($data);
    }

    // ✅ INI YANG DIPAKAI RECEIPT
    public function getTransaction(int $trxId): ?array
    {
        return $this->trx->find($trxId);
    }

    public function getItems(int $trxId): array
    {
        return $this->item->getItemsWithProduct($trxId);
    }

}


