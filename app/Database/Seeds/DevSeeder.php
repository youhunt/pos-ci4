<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DevSeeder extends Seeder
{
    public function run()
    {
        $this->call('UserSeeder');
        $this->call('ShopSeeder');
        $this->call('CategorySeeder');
        $this->call('ProductSeeder');
        $this->call('ProductVariantSeeder');
        $this->call('StockSeeder'); // optional
    }
}
