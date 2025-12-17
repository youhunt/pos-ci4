<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransactionItemSeeder extends Seeder
{
    public function run()
    {
        $txTable = $this->db->table('transactions');
        $itemTable = $this->db->table('transaction_items');
        $products = $this->db->table('products')->get()->getResultArray();

        $transactions = $txTable->get()->getResultArray();

        foreach ($transactions as $tx) {
            $numItems = rand(1,5);
            $total = 0;
            $items = [];

            for ($i=0; $i<$numItems; $i++) {
                $p = $products[array_rand($products)];
                $price = $p['price'];
                $qty = rand(1,3);

                $items[] = [
                    'transaction_id' => $tx['id'],
                    'product_id' => $p['id'],
                    'variant_id' => null,
                    'qty' => $qty,
                    'price' => $price,
                    'subtotal' => $price * $qty,
                    'created_at' => $tx['created_at'],
                ];

                $total += $price * $qty;
            }

            $itemTable->insertBatch($items);

            // update total_amount
            $txTable->where('id', $tx['id'])
                ->update(['total_amount'=>$total, 'total_paid'=>$total, 'payment_status'=>'paid']);
        }
    }
}
