<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],

            'shop_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
            ],

            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
            ],

            'type' => [
                'type' => 'ENUM',
                'constraint' => ['product','category','global'],
                'default' => 'product'
            ],

            'discount_type' => [
                'type' => 'ENUM',
                'constraint' => ['percent','nominal','fixed_price'],
                'default' => 'percent'
            ],

            'discount_value' => [
                'type' => 'DECIMAL',
                'constraint' => '14,2',
                'default' => '0.00'
            ],

            'category_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],

            'product_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],

            'start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'end_date' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'start_time' => [
                'type' => 'TIME',
                'null' => true,
            ],

            'end_time' => [
                'type' => 'TIME',
                'null' => true,
            ],

            'weekdays' => [ // contoh: 1,2,3,4,5
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],

            'active' => [
                'type' => 'TINYINT',
                'default' => 1,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],

            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('shop_id', 'shops', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('promos');
    }

    public function down()
    {
        $this->forge->dropTable('promos');
    }
}
