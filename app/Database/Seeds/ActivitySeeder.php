<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        // Clear existing activities first
        //$this->db->table('activities')->truncate();
        // Get the test user's ID from the database
        $testUser = $this->db->table('users')
            ->where('username', 'admin')
            ->get()
            ->getRow();
            
        if (!$testUser) {
            throw new \RuntimeException('Test user not found. Please run UserSeeder first.');
        }
        
        $userId = $testUser->id;

        // Sample activity data for seeding
        $data = [
            [
                'activity_name' => 'Discrete Mathematics Assignment',
                'deadline' => date('Y-m-d', strtotime('+1 days')),
                'duration' => 2, // Duration in hours
                'activity_type' => 'college',
                'description' => 'Complete Chapter 5 problems',
                'user_id' => $userId,
            ],
            [
                'activity_name' => 'Internship Meeting',
                'deadline' => date('Y-m-d', strtotime('+2 days')),
                'duration' => 1, // Duration in hours
                'activity_type' => 'work',
                'description' => 'Discuss project progress',
                'user_id' => $userId,
            ],
            [
                'activity_name' => 'Bijak Kelola Bumi',
                'deadline' => date('Y-m-d', strtotime('+3 days')),
                'duration' => 3, // Duration in hours
                'activity_type' => 'organization',
                'description' => 'Helping with community clean-up',
                'user_id' => $userId,
            ],
            [
                'activity_name' => 'Comic Last Panel',
                'deadline' => date('Y-m-d', strtotime('+5 days')),
                'duration' => 1, // Duration in hours
                'activity_type' => 'personal',
                'description' => 'Finish drawing the last panel for the comic',
                'user_id' => $userId,
            ],
            [
                'activity_name' => 'Data Analysis Report',
                'deadline' => date('Y-m-d', strtotime('+1 week')),
                'duration' => 4, // Duration in hours
                'activity_type' => 'college',
                'description' => 'Analyze data and write conclusions',
                'user_id' => $userId,
            ],
        ];

        // Insert sample data into the activities table
        $this->db->table('activities')->insertBatch($data);
    }
}