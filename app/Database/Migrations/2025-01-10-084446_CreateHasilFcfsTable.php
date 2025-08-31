<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHasilFcfsTable extends Migration
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
            'hard_constraint' => [
                'type'       => 'FLOAT',
                'constraint' => '5,2',
                'default'    => 0,
                'after'      => 'weight',
            ],
            'soft_constraint' => [
                'type'       => 'FLOAT',
                'constraint' => '5,2',
                'default'    => 0,
                'after'      => 'hard_constraint'
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
            'completion_stat' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'overdue', 'completed'],
                'default' => 'pending',
                'null' => false,
            ],
            'daysToDeadline' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => false,
                'after'      => 'fixed_deadline',
            ],
            'urgency' => [
                'type'       => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'critical'],
                'null'       => false,
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
        $this->forge->createTable('hasil_fcfs');
    }

    public function down()
    {
        $this->forge->dropTable('hasil_fcfs');
    }
}
