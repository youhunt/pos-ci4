<?php 

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionItems extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true,'auto_increment'=>true],
            'transaction_id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true],
            'product_id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true],
            'variant_id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true,'null'=>true],
            'qty' => ['type'=>'INT','constraint'=>11,'default'=>1],
            'price' => ['type'=>'DECIMAL','constraint'=>'14,2','default'=>'0.00'],
            'subtotal' => ['type'=>'DECIMAL','constraint'=>'14,2','default'=>'0.00'],
            'created_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('transaction_items');
        $this->db->query('ALTER TABLE transaction_items ADD CONSTRAINT fk_items_transaction FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE transaction_items DROP FOREIGN KEY fk_items_transaction');
        $this->forge->dropTable('transaction_items');
    }
}
