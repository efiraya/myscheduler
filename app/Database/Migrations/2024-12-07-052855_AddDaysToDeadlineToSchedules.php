<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDaysToDeadlineToSchedules extends Migration
{
    public function up()
    {
        $fields = [
            'daysToDeadline' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => false,
                'after'      => 'description',
            ],
        ];

        $this->forge->addColumn('schedules', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('schedules', 'daysToDeadline');
    }
}
