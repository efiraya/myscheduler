<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStartTimeToFCFS extends Migration
{
    public function up()
    {
        $fields = [
            'start_time' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ];

        $this->forge->addColumn('hasil_fcfs', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('hasil_fcfs', 'start_time');
    }
}
