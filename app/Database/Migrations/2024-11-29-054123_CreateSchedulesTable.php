<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSchedulesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'activity_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'activity_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'deadline' => [
                'type' => 'DATETIME',
                'null'       => false,
            ],
            'duration' => [
                'type' => 'INT',
                'null'       => false,
            ],
            'activity_type' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'final_weight' => [
                'type'       => 'FLOAT',
                'constraint' => '5,2',
                'default'    => 0,
            ],
            'completion_stat' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'overdue', 'completed'],
                'default' => 'pending',
                'null' => false,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('activity_id', 'activities', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('schedules');
    }

    public function down()
    {
        $this->forge->dropTable('schedules');
    }
}
