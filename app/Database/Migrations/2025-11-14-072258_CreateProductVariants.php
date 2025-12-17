<?php 

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductVariants extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true,'auto_increment'=>true],
            'product_id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true],
            'shop_id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true],
            'sku' => ['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'name' => ['type'=>'VARCHAR','constraint'=>191,'null'=>true],
            'price' => ['type'=>'DECIMAL','constraint'=>'14,2','null'=>true],
            'stock' => ['type'=>'INT','constraint'=>11,'default'=>0],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('product_variants');
        $this->db->query('ALTER TABLE product_variants ADD CONSTRAINT fk_variants_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE product_variants ADD CONSTRAINT fk_variants_shop FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE product_variants DROP FOREIGN KEY fk_variants_shop');
        $this->db->query('ALTER TABLE product_variants DROP FOREIGN KEY fk_variants_product');
        $this->forge->dropTable('product_variants');
    }
}
