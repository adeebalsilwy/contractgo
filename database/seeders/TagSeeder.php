<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the tags table exists
        if (!Schema::hasTable('tags')) {
            $this->command->warn('tags table does not exist, skipping seeding.');
            return;
        }
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing tag data to avoid duplicates
        Tag::truncate();
        
        // Sample tag data with comprehensive fields
        $tagData = [
            [
                'title' => 'Important',
                'color' => 'danger',
                'slug' => 'important'
            ],
            [
                'title' => 'Urgent',
                'color' => 'primary',
                'slug' => 'urgent'
            ],
            [
                'title' => 'Development',
                'color' => 'info',
                'slug' => 'development'
            ],
            [
                'title' => 'Design',
                'color' => 'success',
                'slug' => 'design'
            ],
            [
                'title' => 'Testing',
                'color' => 'warning',
                'slug' => 'testing'
            ],
            [
                'title' => 'Bug',
                'color' => 'danger',
                'slug' => 'bug'
            ],
            [
                'title' => 'Feature',
                'color' => 'primary',
                'slug' => 'feature'
            ],
            [
                'title' => 'Documentation',
                'color' => 'secondary',
                'slug' => 'documentation'
            ],
            [
                'title' => 'Client Request',
                'color' => 'info',
                'slug' => 'client-request'
            ],
            [
                'title' => 'Maintenance',
                'color' => 'light',
                'slug' => 'maintenance'
            ]
        ];
        
        // Insert the tag records
        foreach ($tagData as $data) {
            Tag::create($data);
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Tag data seeded successfully!');
    }
}