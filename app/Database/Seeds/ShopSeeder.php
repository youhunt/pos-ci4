<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ShopSeeder extends Seeder
{
    public function run()
    {
        $slug = url_title('Toko Utama', '-', true);

        $data = [
            [
                'name' => 'Toko Utama',
                'slug'       => $slug,
                'address' => 'Jl. Raya No. 1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $this->db->table('shops')->insertBatch($data);
    }
}
