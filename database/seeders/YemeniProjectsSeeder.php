<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class YemeniProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users for project creators
        $users = User::all();
        
        // Define diverse Yemeni project data
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
                'title' => 'مشروع صيانة شبكة الكهرباء - عدن',
                'description' => 'مشروع صيانة وتطوير شبكة الكهرباء في مدينة عدن',
                'workspace_id' => 1,
                'budget' => 35000000.00,
                'start_date' => now()->subDays(400),
                'end_date' => now()->subDays(30),
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
                'title' => 'مشروع توسعة مطار صنعاء',
                'description' => 'مشروع توسعة وتطوير مدرجات مطار صنعاء الدولي',
                'workspace_id' => 1,
                'budget' => 85000000.00,
                'start_date' => now()->subDays(90),
                'end_date' => now()->addDays(450),
            ],
            [
                'title' => 'مشروع تطوير ميناء عدن',
                'description' => 'مشروع تطوير وتحديث ميناء عدن',
                'workspace_id' => 1,
                'budget' => 95000000.00,
                'start_date' => now()->subDays(120),
                'end_date' => now()->addDays(600),
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
            [
                'title' => 'مشروع تطوير مطار المكلا',
                'description' => 'مشروع تطوير وتحديث مطار المكلا',
                'workspace_id' => 1,
                'budget' => 40000000.00,
                'start_date' => now()->subDays(100),
                'end_date' => now()->addDays(300),
            ],
            [
                'title' => 'مشروع تطوير ميناء المخا',
                'description' => 'مشروع تطوير ميناء المخا في محافظة الحديدة',
                'workspace_id' => 1,
                'budget' => 30000000.00,
                'start_date' => now()->subDays(200),
                'end_date' => now()->addDays(200),
            ],
        ];

        foreach ($projects as $index => $projectData) {
            $project = Project::create([
                'title' => $projectData['title'],
                'description' => $projectData['description'],
                'workspace_id' => $projectData['workspace_id'],
                'budget' => $projectData['budget'],
                'start_date' => $projectData['start_date'],
                'end_date' => $projectData['end_date'],
                'created_by' => $users->random()->id,
            ]);
        }
    }
}