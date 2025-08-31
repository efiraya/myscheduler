<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ResultsFromIntToFloat extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('activities', [
            'weight' => [
                'type'       => 'FLOAT',
                'constraint' => '5,2',
                'default'    => 0,
            ],
            'constraint_points' => [
                'type'       => 'FLOAT',
                'constraint' => '5,2',
                'default'    => 0,
            ],
            'final_weight' => [
                'type'       => 'FLOAT',
                'constraint' => '5,2',
                'default'    => 0,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('activities', [
            'weight' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
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
        ]);
    }
}
