<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToSchedules extends Migration
{
    public function up()
    {   
        $db = \Config\Database::connect();
        $fields = $db->getFieldData('schedules');
        $columnNames = array_map(function($field) {
            return $field->name;
        }, $fields);

        // Tambahkan kolom jika belum ada
        if (!in_array('completion_stat', $columnNames)) {
            $this->forge->addColumn('schedules', [
                'completion_stat' => [
                    'type' => 'ENUM',
                    'constraint' => ['pending', 'overdue', 'completed'],
                    'default' => 'pending',
                    'null' => false,
                ],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('schedules', 'completion_stat');
    }
}
