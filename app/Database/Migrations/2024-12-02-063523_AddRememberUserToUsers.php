<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRememberUserToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'remember_username' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'updated_at',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'remember_username');
    }
}
