<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFixedDeadlineToActivities extends Migration
{
    public function up()
    {
        // Adding the fixed_deadline column
        $this->forge->addColumn('activities', [
            'fixed_deadline' => [
                'type' => 'BOOLEAN',
                'default' => false, // Set default value
                'null' => false, // Set to false to make it a required field
            ],
        ]);
    }

    public function down()
    {
        // Removing the fixed_deadline column
        $this->forge->dropColumn('activities', 'fixed_deadline');
    }
}
