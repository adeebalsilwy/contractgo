<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder creates the default languages (Arabic and English)
     * with proper configuration for the application.
     *
     * @return void
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear existing languages to avoid duplicates
        Language::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $languages = [
            [
                'name' => 'العربية',
                'code' => 'ar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'English',
                'code' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }

        $this->command->info('✓ Languages seeded successfully!');
        $this->command->info('  - Arabic (ar)');
        $this->command->info('  - English (en)');
    }
}
