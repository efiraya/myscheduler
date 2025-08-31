<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCompletionTimeToFCFS extends Migration
{
    public function up()
    {
        $this->forge->addColumn('hasil_fcfs', [
            'completion_time' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'turnaround_time' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'waiting_time' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('completion_time', 'turnaround_time', 'waiting_time');
    }
}
