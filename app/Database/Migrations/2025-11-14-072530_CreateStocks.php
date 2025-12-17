<?php 

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStocks extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true,'auto_increment'=>true],
            'shop_id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true],
            'product_id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true],
            'variant_id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true,'null'=>true],
            'type' => ['type'=>'ENUM','constraint'=>['in','out','adjustment']],
            'qty' => ['type'=>'INT','constraint'=>11,'default'=>0],
            'note' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('stocks');
        $this->db->query('ALTER TABLE stocks ADD CONSTRAINT fk_stocks_shop FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE stocks ADD CONSTRAINT fk_stocks_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE stocks DROP FOREIGN KEY fk_stocks_product');
        $this->db->query('ALTER TABLE stocks DROP FOREIGN KEY fk_stocks_shop');
        $this->forge->dropTable('stocks');
    }
}
