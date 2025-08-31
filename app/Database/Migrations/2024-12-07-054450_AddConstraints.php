<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddConstraints extends Migration
{
    public function up()
    {
        $this->forge->addColumn('activities', [
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
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('hard_constraint', 'soft_constraint');
    }
}
