<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Clear existing users first
        //$this->db->table('users')->truncate();
        $data = [
            [
                'full_name' => 'Admin',
                'username'  => 'admin',
                'email'     => 'admin@example.com',
                'password'  => password_hash('123123', PASSWORD_DEFAULT), // Using password_hash for secure hashing
                'token'     => null,
                'created_at' => date('Y-m-d H:i:s'), // Setting created_at timestamp
                'updated_at' => date('Y-m-d H:i:s'), // Setting updated_at timestamp
            ],
        ];

        // Use insertBatch to insert multiple entries at once, or insert() if you're inserting a single entry
        $this->db->table('users')->insertBatch($data);
    }
}