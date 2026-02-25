<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the statuses table exists
        if (!Schema::hasTable('statuses')) {
            $this->command->warn('statuses table does not exist, skipping seeding.');
            return;
        }
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing status data to avoid duplicates
        Status::truncate();
        
        // Sample status data with comprehensive fields
        $statusData = [
            [
                'title' => 'Pending',
                'color' => 'warning',
                'slug' => 'pending'
            ],
            [
                'title' => 'In Progress',
                'color' => 'primary',
                'slug' => 'in-progress'
            ],
            [
                'title' => 'Completed',
                'color' => 'success',
                'slug' => 'completed'
            ],
            [
                'title' => 'Cancelled',
                'color' => 'danger',
                'slug' => 'cancelled'
            ],
            [
                'title' => 'On Hold',
                'color' => 'secondary',
                'slug' => 'on-hold'
            ],
            [
                'title' => 'Review',
                'color' => 'info',
                'slug' => 'review'
            ],
            [
                'title' => 'Draft',
                'color' => 'light',
                'slug' => 'draft'
            ],
            [
                'title' => 'Approved',
                'color' => 'success',
                'slug' => 'approved'
            ],
            [
                'title' => 'Rejected',
                'color' => 'danger',
                'slug' => 'rejected'
            ],
            [
                'title' => 'Archived',
                'color' => 'dark',
                'slug' => 'archived'
            ]
        ];
        
        // Insert the status records
        foreach ($statusData as $data) {
            Status::create($data);
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Status data seeded successfully!');
    }
}