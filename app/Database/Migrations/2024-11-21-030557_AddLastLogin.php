<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLastLogin extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'last_login' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
                'after' => 'remember_expires' // Adjust this if you want it in a different position
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'last_login');
    }
}
