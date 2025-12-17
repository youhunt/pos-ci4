<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $txModel = $this->db->table('transactions');
        $products = $this->db->table('products')->get()->getResultArray();
        $users = $this->db->table('users')->get()->getResultArray();

        $transactions = [];

        for ($i = 1; $i <= 200; $i++) {
            $user = $users[array_rand($users)];
            $shopId = $user['shop_id'] ?? 1;

            $transactions[] = [
                'shop_id' => $shopId,
                'invoice' => 'INV-'.date('Ymd').'-'.str_pad($i, 4, '0', STR_PAD_LEFT),
                'user_id' => $user['id'],
                'total_amount' => 0,   // nanti dihitung di TransactionItemSeeder
                'total_paid' => 0,
                'discount_amount' => 0,
                'payment_status' => 'pending',
                'local_id' => 'local-tx-'.$i,
                'created_at' => date('Y-m-d H:i:s', strtotime("-".rand(0,30)." days")),
                'updated_at' => date('Y-m-d H:i:s', strtotime("-".rand(0,30)." days")),
            ];
        }

        $txModel->insertBatch($transactions);
    }
}
