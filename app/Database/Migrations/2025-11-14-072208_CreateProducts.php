<?php 

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProducts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true,'auto_increment'=>true],
            'shop_id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true],
            'sku' => ['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'name' => ['type'=>'VARCHAR','constraint'=>191],
            'description' => ['type'=>'TEXT','null'=>true],
            'category_id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true,'null'=>true],
            'barcode' => ['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'price' => ['type'=>'DECIMAL','constraint'=>'14,2','default'=>'0.00'],
            'wholesale_price' => ['type'=>'DECIMAL','constraint'=>'14,2','null'=>true],
            'image' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'has_variants' => ['type'=>'TINYINT','constraint'=>1,'default'=>0],
            'stock' => ['type'=>'INT','default'=>0],   // <— for products without variants
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('products');
        $this->db->query('ALTER TABLE products ADD CONSTRAINT fk_products_shop FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE products ADD CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE products DROP FOREIGN KEY fk_products_category');
        $this->db->query('ALTER TABLE products DROP FOREIGN KEY fk_products_shop');
        $this->forge->dropTable('products');
    }
}
