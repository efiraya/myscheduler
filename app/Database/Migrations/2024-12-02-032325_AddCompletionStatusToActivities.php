<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCompletionStatusToActivities extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldData('activities');
        $columnNames = array_map(function($field) {
            return $field->name;
        }, $fields);

        // Tambahkan kolom jika belum ada
        if (!in_array('completion_stat', $columnNames)) {
            $this->forge->addColumn('activities', [
                'completion_stat' => [
                    'type' => 'ENUM',
                    'constraint' => ['pending', 'overdue', 'completed'],
                    'default' => 'pending',
                    'null' => false,
                ],
            ]);
        }

        // Tambahkan kolom 'urgency' jika belum ada
        if (!in_array('urgency', $columnNames)) {
            $this->forge->addColumn('activities', [
                'urgency' => [
                    'type'       => 'ENUM',
                    'constraint' => ['low', 'medium', 'high', 'critical'],
                    'null'       => false,
                ],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('activities', 'completion_stat');
        $this->forge->dropColumn('activities', 'urgency');
    }
}
