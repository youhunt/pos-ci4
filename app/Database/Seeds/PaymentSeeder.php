<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        $paymentTable = $this->db->table('payments');
        $transactions = $this->db->table('transactions')->get()->getResultArray();

        $payments = [];

        foreach ($transactions as $tx) {
            $payments[] = [
                'transaction_id' => $tx['id'],
                'method' => 'cash',
                'amount' => $tx['total_paid'],
                'note' => 'Auto generated',
                'created_at' => $tx['created_at'],
            ];
        }

        $paymentTable->insertBatch($payments);
    }
}
