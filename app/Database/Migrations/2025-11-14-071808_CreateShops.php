<?php 

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateShops extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'BIGINT','constraint'=>20,'unsigned'=>true,'auto_increment'=>true],
            'name' => ['type'=>'VARCHAR','constraint'=>191],
            'slug' => ['type'=>'VARCHAR','constraint'=>191],
            'logo' => ['type'=>'VARCHAR','constraint'=>255,'null'=>true],
            'address' => ['type'=>'TEXT','null'=>true],
            'tax_percent' => ['type'=>'DECIMAL','constraint'=>'5,2','default'=>'0.00'],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('shops');
    }

    public function down()
    {
        $this->forge->dropTable('shops');
    }
}
