<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run()
    {
        $variants = [];
        $builder = $this->db->table('products')->where('has_variants', 1);
        $products = $builder->get()->getResultArray();

        $variantNames = ['Small', 'Medium', 'Large', 'XL', 'Red', 'Blue', 'Green', 'Bundle'];

        $count = 0;

        foreach ($products as $p) {
            $num = rand(1, 3); // tiap produk 1–3 varian

            for ($i = 0; $i < $num; $i++) {
                if ($count >= 80) break; // limit 80 varian total

                $variants[] = [
                    'product_id' => $p['id'],
                    'shop_id' => 1,
                    'name' => $variantNames[array_rand($variantNames)],
                    'price' => $p['price'] + rand(500, 5000),
                    'stock' => rand(5, 50),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $count++;
            }
        }

        if (! empty($variants)) {
            $this->db->table('product_variants')->insertBatch($variants);
        }
    }
}
