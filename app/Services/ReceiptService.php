<?php

namespace App\Services;
use App\Repositories\TransactionRepository;
use App\Repositories\PaymentRepository;

class ReceiptService
{
    
    protected $trxRepo;
    protected $paymentRepo;

    public function __construct()
    {
        $this->trxRepo = new TransactionRepository();
        $this->paymentRepo = new PaymentRepository();
    }

    public function getReceipt(int $trxId): array
    {
        $trx = $this->trxRepo->getTransaction($trxId);

        if (!$trx) {
            throw new \RuntimeException('Transaksi tidak ditemukan');
        }

        return [
            'transaction' => [
                'id'              => (int)$trx['id'],
                'invoice'         => $trx['invoice'],
                'created_at'      => $trx['created_at'],
                'total_amount'    => (float)$trx['total_amount'],
                'discount_amount' => (float)$trx['discount_amount'],
                'total_paid'      => (float)$trx['total_paid'],
                'payment_status'  => $trx['payment_status'],
            ],
            'items'    => $this->trxRepo->getItems($trxId),
            'payments' => $this->paymentRepo->getByTransaction($trxId),
        ];
    }
}
