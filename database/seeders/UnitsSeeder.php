<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default units
        $units = [
            [
                'workspace_id' => 1,
                'title' => 'متر مكعب',
                'description' => 'وحدة قياس الحجوم',
            ],
            [
                'workspace_id' => 1,
                'title' => 'طن',
                'description' => 'وحدة قياس الوزن',
            ],
            [
                'workspace_id' => 1,
                'title' => 'كيلوغرام',
                'description' => 'وحدة قياس الوزن',
            ],
            [
                'workspace_id' => 1,
                'title' => 'متر',
                'description' => 'وحدة قياس الطول',
            ],
            [
                'workspace_id' => 1,
                'title' => 'قطعة',
                'description' => 'وحدة قياس عددية',
            ],
            [
                'workspace_id' => 1,
                'title' => 'ساعة',
                'description' => 'وحدة قياس الوقت',
            ],
            [
                'workspace_id' => 1,
                'title' => 'يوم',
                'description' => 'وحدة قياس الوقت',
            ],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(
                ['title' => $unit['title']],
                $unit
            );
        }

        $this->command->info('Units seeded successfully!');
    }
}