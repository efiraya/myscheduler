<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddActivityIdToFCFS extends Migration
{
    public function up()
    {
        $fields = [
            'activity_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ];

        $this->forge->addColumn('hasil_fcfs', $fields);
        $this->forge->addForeignKey('activity_id', 'activities', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropColumn('hasil_fcfs', 'activity_id');
    }
}
