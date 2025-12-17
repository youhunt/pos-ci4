<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Makanan', 'shop_id' => 1],
            ['name' => 'Minuman', 'shop_id' => 1],
            ['name' => 'Sembako', 'shop_id' => 1],
            ['name' => 'Fashion Pria', 'shop_id' => 1],
            ['name' => 'Fashion Wanita', 'shop_id' => 1],
            ['name' => 'Aksesoris', 'shop_id' => 1],
        ];

        foreach ($categories as &$c) {
            $c['created_at'] = date('Y-m-d H:i:s');
            $c['updated_at'] = date('Y-m-d H:i:s');
        }

        $this->db->table('categories')->insertBatch($categories);
    }
}
