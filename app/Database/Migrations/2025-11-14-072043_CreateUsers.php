<?php 

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsers extends Migration
{
     public function up()
    {
        // Add shop_id
        if (! $this->db->fieldExists('shop_id', 'users')) {
            $this->forge->addColumn('users', [
                'shop_id' => [
                    'type'=>'BIGINT','constraint'=>20,'unsigned'=>true,
                    'null'       => true,
                    'after'      => 'active'
                ]
            ]);
        }

        // Add role
        if (! $this->db->fieldExists('role', 'users')) {
            $this->forge->addColumn('users', [
                'role' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'default'    => 'kasir',
                    'after'      => 'shop_id'
                ]
            ]);
        }

        // Add full_name
        if (! $this->db->fieldExists('full_name', 'users')) {
            $this->forge->addColumn('users', [
                'full_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                    'after'      => 'username'
                ]
            ]);
        }

        // Foreign Key shop_id → shops.id
        $this->db->query("
            ALTER TABLE users
            ADD CONSTRAINT fk_users_shop
                FOREIGN KEY (shop_id)
                REFERENCES shops(id)
                ON DELETE SET NULL 
                ON UPDATE CASCADE
        ");
    }

    public function down()
    {
        // drop FK
        $this->db->query("ALTER TABLE users DROP FOREIGN KEY fk_users_shop");

        // drop added fields
        if ($this->db->fieldExists('full_name', 'users')) {
            $this->forge->dropColumn('users', 'full_name');
        }
        if ($this->db->fieldExists('role', 'users')) {
            $this->forge->dropColumn('users', 'role');
        }
        if ($this->db->fieldExists('shop_id', 'users')) {
            $this->forge->dropColumn('users', 'shop_id');
        }
    }
}
