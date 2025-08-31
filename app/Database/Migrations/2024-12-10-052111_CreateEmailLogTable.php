<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmailLogTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'email_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'last_sent' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('email_logs');
    }

    public function down()
    {
        $this->forge->dropTable('email_logs');
    }
}
