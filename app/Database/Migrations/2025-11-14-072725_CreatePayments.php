<?php 

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePayments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true,'auto_increment'=>true],
            'transaction_id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true],
            'method' => ['type'=>'VARCHAR','constraint'=>50,'null'=>true],
            'amount' => ['type'=>'DECIMAL','constraint'=>'14,2','default'=>'0.00'],
            'note' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('payments');
        $this->db->query('ALTER TABLE payments ADD CONSTRAINT fk_payments_transaction FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE payments DROP FOREIGN KEY fk_payments_transaction');
        $this->forge->dropTable('payments');
    }
}
