<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTokenExpires extends Migration
{
    public function up()
    {
        // Add 'token_expires' column to 'users' table
        $this->forge->addColumn('users', [
            'token_expires' => [
                'type' => 'DATETIME',
                'null' => true
            ],
        ]);
    }

    public function down()
    {
        // Drop 'token_expires' column from 'users' table
        $this->forge->dropColumn('users', 'token_expires');
    }
}
