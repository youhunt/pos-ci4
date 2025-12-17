<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        helper('text');

        $products = [];
        $categories = [1,2,3,4,5,6];

        for ($i = 1; $i <= 50; $i++) {
            $products[] = [
                'shop_id' => 1,
                'sku' => 'SKU-' . strtoupper(random_string('alnum', 6)),
                'name' => 'Produk ' . $i,
                'description' => 'Deskripsi produk ' . $i,
                'category_id' => $categories[array_rand($categories)],
                'barcode' => random_string('numeric', 10),
                'price' => rand(5000, 100000),
                'wholesale_price' => rand(3000, 80000),
                'image' => null,
                'has_variants' => rand(0, 1), // random ada varian atau tidak
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('products')->insertBatch($products);
    }
}
