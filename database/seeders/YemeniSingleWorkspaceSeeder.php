<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Workspace;
use App\Models\User;

class YemeniSingleWorkspaceSeeder extends Seeder
{
    /**
     * Run the database seeds for creating a single workspace
     * as per requirements: one workspace, 5 projects, 10 contracts
     */
    public function run(): void
    {
        // Create or update single workspace with ID 1
        $workspace = Workspace::updateOrCreate(
            ['id' => 1],
            [
                'user_id' => 1,
                'title' => 'النظام الوطني لإدارة العقود - Yemen National Contract Management System',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        $this->command->info('تم إنشاء workspace واحد بنجاح:');
        $this->command->info('- ID: 1');
        $this->command->info('- الاسم: النظام الوطني لإدارة العقود');
        $this->command->info('- الوصف: النظام الشامل لإدارة مشاريع وعقود الدولة في الجمهورية اليمنية');
    }
}