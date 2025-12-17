<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run()
    {
        // Ambil produk yang has_variants = 0
        $products = $this->db->table('products')
            ->where('has_variants', 0)
            ->get()
            ->getResultArray();

        $stocks = [];

        foreach ($products as $p) {
            $qty = rand(10, 100);

            // update stock di tabel products
            $this->db->table('products')
                ->where('id', $p['id'])
                ->update([
                    'stock' => $qty,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            // Insert ke stock_mutations
            $stocks[] = [
                'shop_id' => 1,
                'product_id' => $p['id'],
                'variant_id' => null,
                'type' => 'in',
                'qty' => $qty,
                'note' => 'Initial Stock',
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        if (! empty($stocks)) {
            $this->db->table('stocks')->insertBatch($stocks);
        }
    }
}
