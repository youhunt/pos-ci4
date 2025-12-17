<?php 

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true,
            ],

            'shop_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],

            'invoice' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],

            'user_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,  // WAJIB untuk SET NULL
            ],

            'total_amount' => ['type'=>'DECIMAL','constraint'=>'14,2','default'=>'0.00'],
            'total_paid' => ['type'=>'DECIMAL','constraint'=>'14,2','default'=>'0.00'],
            'discount_amount' => ['type'=>'DECIMAL','constraint'=>'14,2','default'=>'0.00'],

            'payment_status' => [
                'type' => 'ENUM',
                'constraint' => ['paid','partial','pending'],
                'default' => 'pending'
            ],

            'local_id' => ['type'=>'VARCHAR','constraint'=>191,'null'=>true],

            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
            'synced_at' => ['type'=>'DATETIME','null'=>true],
        ]);

        $this->forge->addKey('id', true);

        // Foreign keys by forge
        $this->forge->addForeignKey('shop_id', 'shops', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('transactions');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
