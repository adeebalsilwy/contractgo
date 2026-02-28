<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateExpenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds to create a default expense type
     */
    public function run(): void
    {
        $this->command->info('Creating default expense type...');
        
        // Check if expense type already exists
        $exists = DB::table('expense_types')->where('title', 'General')->first();
        
        if (!$exists) {
            DB::table('expense_types')->insert([
                'title' => 'General',
                'description' => 'General expenses',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->command->info('Default expense type created successfully!');
        } else {
            $this->command->info('Default expense type already exists!');
        }
    }
}