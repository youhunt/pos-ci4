<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromoProducts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'=>'BIGINT',
                'unsigned'=>true,
                'auto_increment'=>true
            ],

            'shop_id' => [
                'type'=>'BIGINT',
                'unsigned'=>true
            ],

            'promo_id' => [
                'type'=>'BIGINT',
                'unsigned'=>true
            ],

            'product_id' => [
                'type'=>'BIGINT',
                'unsigned'=>true
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('promo_id', 'promos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('shop_id', 'shops', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('promo_products');
    }

    public function down()
    {
        $this->forge->dropTable('promo_products');
    }
}
