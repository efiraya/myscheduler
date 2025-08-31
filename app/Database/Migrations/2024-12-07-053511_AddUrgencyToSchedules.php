<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUrgencyToSchedules extends Migration
{
    public function up()
    {
        $fields = [
            'urgency' => [
                'type'       => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'critical'],
                'null'       => false,
            ],
        ];

        $this->forge->addColumn('schedules', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('schedules', 'urgency');
    }
}
