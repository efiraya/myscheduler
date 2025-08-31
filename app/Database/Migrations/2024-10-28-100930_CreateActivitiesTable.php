<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivitiesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'activity_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'deadline' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'duration' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => false,
            ],
            'activity_type' => [
                'type'       => 'ENUM',
                'constraint' => ['college', 'work', 'organization', 'personal'],
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'weight' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
            ],
            'constraint_points' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
            ],
            'final_weight' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('activities');
    }

    public function down()
    {
        $this->forge->dropTable('activities');
    }
}
