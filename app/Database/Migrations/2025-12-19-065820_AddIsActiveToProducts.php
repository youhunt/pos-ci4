<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsActiveToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'after'      => 'stock'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'is_active');
    }
}
