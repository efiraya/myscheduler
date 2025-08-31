<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCreateAndUpdateAtForActivities extends Migration
{
    public function up()
    {
        $this->forge->addColumn('activities', [
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('created_at', 'updated_at');
    }
}
