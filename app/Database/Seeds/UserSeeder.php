<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'id'            => 2,
            'email'         => 'demo@local.test',
            'username'      => 'demo',
            'password_hash' => password_hash('demo123', PASSWORD_DEFAULT),
            'active'        => 1,
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $this->db->table('users')->insert($data);
    }
}
