<?php

namespace App\Repositories;

use App\Models\PaymentModel;

class PaymentRepository
{
    protected $payment;

    public function __construct()
    {
        $this->payment = new PaymentModel();
    }

    public function addPayment(int $trxId, string $method, int $amount, ?string $note = null)
    {
        return $this->payment->insert([
            'transaction_id' => $trxId,
            'method'         => $method,
            'amount'         => $amount,
            'note'           => $note,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
    }

    public function getByTransaction(int $trxId): array
    {
        return $this->payment
            ->where('transaction_id', $trxId)
            ->findAll();
    }
}
