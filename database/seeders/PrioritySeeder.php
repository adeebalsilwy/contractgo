<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the priorities table exists
        if (!Schema::hasTable('priorities')) {
            $this->command->warn('priorities table does not exist, skipping seeding.');
            return;
        }
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing priority data to avoid duplicates
        Priority::truncate();
        
        // Sample priority data with comprehensive fields
        $priorityData = [
            [
                'title' => 'Low',
                'color' => 'success',
                'slug' => 'low'
            ],
            [
                'title' => 'Medium',
                'color' => 'warning',
                'slug' => 'medium'
            ],
            [
                'title' => 'High',
                'color' => 'danger',
                'slug' => 'high'
            ],
            [
                'title' => 'Critical',
                'color' => 'dark',
                'slug' => 'critical'
            ],
            [
                'title' => 'Urgent',
                'color' => 'primary',
                'slug' => 'urgent'
            ]
        ];
        
        // Insert the priority records
        foreach ($priorityData as $data) {
            Priority::create($data);
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Priority data seeded successfully!');
    }
}