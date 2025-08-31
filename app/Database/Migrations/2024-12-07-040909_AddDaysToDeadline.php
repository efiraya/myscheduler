<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDaysToDeadline extends Migration
{
    public function up()
    {
        $fields = [
            'daysToDeadline' => [
                'type'       => 'INT',
                'constraint' => 5,
                'null'       => false,
                'after'      => 'fixed_deadline',
            ],
        ];

        $this->forge->addColumn('activities', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('activities', 'daysToDeadline');
    }
}
