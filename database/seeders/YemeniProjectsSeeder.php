<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class YemeniProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * NOTE: Projects are created in ModernRealEstateCompanySeeder to ensure only 5 projects exist as per requirements.
     * This seeder will only verify the projects exist.
     */
    public function run(): void
    {
        // Verify that exactly 5 projects exist in workspace 1
        $existingProjects = Project::where('workspace_id', 1)->get();
        
        if ($existingProjects->count() >= 5) {
            $this->command->info('Found ' . $existingProjects->count() . ' projects in workspace 1. Skipping creation.');
            return;
        }
        
        // Only create projects if they don't exist
        $users = User::all();
        $clients = Client::all();
        
        // Define exactly 5 Yemeni project data as per requirements
        $projects = [
            [
                'title' => 'مشروع توسعة شارع صنعاء - تعز',
                'description' => 'مشروع توسعة وتطوير الطريق الرئيسي بين محافظتي صنعاء وتعز',
                'workspace_id' => 1,
                'budget' => 50000000.00,
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(365),
            ],
            [
                'title' => 'مشروع صيانة جسور محافظة حضرموت',
                'description' => 'مشروع صيانة وترميم الجسور في محافظة حضرموت',
                'workspace_id' => 1,
                'budget' => 75000000.00,
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(180),
            ],
            [
                'title' => 'مشروع تطوير ميناء الحديدة',
                'description' => 'مشروع تطوير وتوسعة ميناء الحديدة',
                'workspace_id' => 1,
                'budget' => 120000000.00,
                'start_date' => now()->subDays(60),
                'end_date' => now()->addDays(540),
            ],
            [
                'title' => 'مشروع توسعة مستشفى الجمهورية - صنعاء',
                'description' => 'مشروع توسعة وتطوير مستشفى الجمهورية بصنعاء',
                'workspace_id' => 1,
                'budget' => 45000000.00,
                'start_date' => now()->subDays(45),
                'end_date' => now()->addDays(270),
            ],
            [
                'title' => 'مشروع تطوير مجمع تعليمي - تعز',
                'description' => 'مشروع بناء مجمع تعليمي متكامل في محافظة تعز',
                'workspace_id' => 1,
                'budget' => 60000000.00,
                'start_date' => now()->subDays(75),
                'end_date' => now()->addDays(360),
            ],
        ];

        foreach ($projects as $index => $projectData) {
            $existingProject = Project::where('title', $projectData['title'])
                                     ->where('workspace_id', $projectData['workspace_id'])
                                     ->first();
            
            if (!$existingProject) {
                $project = Project::create([
                    'title' => $projectData['title'],
                    'description' => $projectData['description'],
                    'workspace_id' => $projectData['workspace_id'],
                    'user_id' => 'u_' . $users->random()->id,
                    'client_id' => 'c_' . $clients->random()->id,
                    'status' => 'active',
                    'budget' => $projectData['budget'],
                    'start_date' => $projectData['start_date'],
                    'end_date' => $projectData['end_date'],
                    'created_by' => $users->random()->id,
                    'status_id' => rand(1, 3),
                    'priority_id' => rand(1, 3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        $finalCount = Project::where('workspace_id', 1)->count();
        $this->command->info('Ensured ' . $finalCount . ' projects exist in workspace 1.');
    }
}