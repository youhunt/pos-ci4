<?php 

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategories extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true,'auto_increment'=>true],
            'shop_id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true],
            'name' => ['type'=>'VARCHAR','constraint'=>191],
            'slug' => ['type'=>'VARCHAR','constraint'=>191,'null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('categories');
        $this->db->query('ALTER TABLE categories ADD CONSTRAINT fk_categories_shop FOREIGN KEY (shop_id) REFERENCES shops(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE categories DROP FOREIGN KEY fk_categories_shop');
        $this->forge->dropTable('categories');
    }
}
