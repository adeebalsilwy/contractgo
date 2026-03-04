<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Profession;
use App\Models\Item;
use App\Models\Unit;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class CompleteWorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Complete Workflow Seeder...');
        
        // Get or create SINGLE workspace
        $workspace = Workspace::firstOrCreate(
            ['name' => 'Main Workspace'],
            ['name' => 'Main Workspace', 'is_default' => true]
        );
        
        $this->command->info('✓ Workspace ready: ' . $workspace->name);
        
        // Create Units
        $units = $this->createUnits();
        $this->command->info('✓ Created ' . count($units) . ' units');
        
        // Create Statuses
        $statuses = $this->createStatuses();
        $this->command->info('✓ Created ' . count($statuses) . ' statuses');
        
        // Create Priorities
        $priorities = $this->createPriorities();
        $this->command->info('✓ Created ' . count($priorities) . ' priorities');
        
        // Create Professions
        $professions = $this->createProfessions($workspace);
        $this->command->info('✓ Created ' . count($professions) . ' professions');
        
        // Create Users with different roles
        $users = $this->createUsers($workspace, $professions);
        $this->command->info('✓ Created ' . count($users) . ' users');
        
        // Create Clients
        $clients = $this->createClients($workspace, $professions);
        $this->command->info('✓ Created ' . count($clients) . ' clients');
        
        // Create EXACTLY 5 Projects
        $projects = $this->createProjects($workspace, $clients, $statuses, $priorities, $users);
        $this->command->info('✓ Created exactly 5 projects');
        
        // Create Contract Types
        $contractTypes = $this->createContractTypes($workspace);
        $this->command->info('✓ Created ' . count($contractTypes) . ' contract types');
        
        // Create Items (BOQ)
        $items = $this->createItems($workspace, $units, $professions);
        $this->command->info('✓ Created ' . count($items) . ' items');
        
        // Create EXACTLY 10 Contracts distributed across projects
        $contracts = $this->createContracts(
            $workspace,
            $clients,
            $projects,
            $contractTypes,
            $users,
            $professions,
            $items
        );
        $this->command->info('✓ Created exactly 10 contracts');
        
        // Create Tasks for all 10 contracts and 5 projects
        $this->createTasks($projects, $contracts, $users, $statuses, $priorities);
        $this->command->info('✓ Created tasks for all contracts and projects');
        
        $this->command->info('✅ Complete Workflow Seeder finished successfully!');
    }
    
    /**
     * Create units
     */
    private function createUnits()
    {
        $unitsData = [
            ['title' => 'Unit', 'title_ar' => 'وحدة'],
            ['title' => 'Meter', 'title_ar' => 'متر'],
            ['title' => 'Square Meter', 'title_ar' => 'متر مربع'],
            ['title' => 'Cubic Meter', 'title_ar' => 'متر مكعب'],
            ['title' => 'Kilogram', 'title_ar' => 'كيلوجرام'],
            ['title' => 'Ton', 'title_ar' => 'طن'],
            ['title' => 'Piece', 'title_ar' => 'قطعة'],
            ['title' => 'Set', 'title_ar' => 'مجموعة'],
            ['title' => 'Liter', 'title_ar' => 'لتر'],
            ['title' => 'Hour', 'title_ar' => 'ساعة'],
        ];
        
        $units = [];
        foreach ($unitsData as $data) {
            $unit = Unit::firstOrCreate(['title' => $data['title']], $data);
            $units[] = $unit;
        }
        
        return $units;
    }
    
    /**
     * Create statuses
     */
    private function createStatuses()
    {
        $statusesData = [
            ['title' => 'Planning', 'title_ar' => 'تخطيط', 'color' => '#63ed7a'],
            ['title' => 'In Progress', 'title_ar' => 'قيد التنفيذ', 'color' => '#ffa426'],
            ['title' => 'On Hold', 'title_ar' => 'معلق', 'color' => '#fc544b'],
            ['title' => 'Completed', 'title_ar' => 'مكتمل', 'color' => '#6777ef'],
            ['title' => 'Approved', 'title_ar' => 'معتمد', 'color' => '#00cc66'],
            ['title' => 'Pending Approval', 'title_ar' => 'بانتظار الاعتماد', 'color' => '#ff9900'],
            ['title' => 'Rejected', 'title_ar' => 'مرفوض', 'color' => '#ff3300'],
        ];
        
        $statuses = [];
        foreach ($statusesData as $data) {
            $status = Status::firstOrCreate(['title' => $data['title']], $data);
            $statuses[] = $status;
        }
        
        return $statuses;
    }
    
    /**
     * Create priorities
     */
    private function createPriorities()
    {
        $prioritiesData = [
            ['title' => 'Low', 'title_ar' => 'منخفضة', 'color' => '#63ed7a'],
            ['title' => 'Medium', 'title_ar' => 'متوسطة', 'color' => '#ffa426'],
            ['title' => 'High', 'title_ar' => 'عالية', 'color' => '#fc544b'],
            ['title' => 'Critical', 'title_ar' => 'حرجة', 'color' => '#ff0000'],
        ];
        
        $priorities = [];
        foreach ($prioritiesData as $data) {
            $priority = Priority::firstOrCreate(['title' => $data['title']], $data);
            $priorities[] = $priority;
        }
        
        return $priorities;
    }
    
    /**
     * Create professions
     */
    private function createProfessions($workspace)
    {
        $professionsData = [
            ['title' => 'Project Manager', 'title_ar' => 'مدير مشروع'],
            ['title' => 'Site Supervisor', 'title_ar' => 'مشرف موقع'],
            ['title' => 'Quantity Surveyor', 'title_ar' => 'مساح كميات'],
            ['title' => 'Accountant', 'title_ar' => 'محاسب'],
            ['title' => 'Reviewer', 'title_ar' => 'مراجع'],
            ['title' => 'Final Approver', 'title_ar' => 'معتمد نهائي'],
            ['title' => 'Engineer', 'title_ar' => 'مهندس'],
            ['title' => 'Architect', 'title_ar' => 'مهندس معماري'],
            ['title' => 'Consultant', 'title_ar' => 'استشاري'],
        ];
        
        $professions = [];
        foreach ($professionsData as $data) {
            $profession = Profession::firstOrCreate(
                ['title' => $data['title'], 'workspace_id' => $workspace->id],
                $data
            );
            $professions[] = $profession;
        }
        
        return $professions;
    }
    
    /**
     * Create users with workflow roles
     */
    private function createUsers($workspace, $professions)
    {
        $usersData = [
            [
                'email' => 'admin@workflow.com',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'role' => 'admin',
                'profession_id' => null,
            ],
            [
                'email' => 'supervisor@workflow.com',
                'first_name' => 'Site',
                'last_name' => 'Supervisor',
                'role' => 'user',
                'profession_id' => $professions[1]->id ?? null, // Site Supervisor
            ],
            [
                'email' => 'quantity@workflow.com',
                'first_name' => 'Quantity',
                'last_name' => 'Approver',
                'role' => 'user',
                'profession_id' => $professions[2]->id ?? null, // Quantity Surveyor
            ],
            [
                'email' => 'accountant@workflow.com',
                'first_name' => 'Accountant',
                'last_name' => 'User',
                'role' => 'user',
                'profession_id' => $professions[3]->id ?? null, // Accountant
            ],
            [
                'email' => 'reviewer@workflow.com',
                'first_name' => 'Reviewer',
                'last_name' => 'User',
                'role' => 'user',
                'profession_id' => $professions[4]->id ?? null, // Reviewer
            ],
            [
                'email' => 'approver@workflow.com',
                'first_name' => 'Final',
                'last_name' => 'Approver',
                'role' => 'user',
                'profession_id' => $professions[5]->id ?? null, // Final Approver
            ],
        ];
        
        $users = [];
        foreach ($usersData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password' => bcrypt('password'),
                    'workspace_id' => $workspace->id,
                ])
            );
            
            // Assign to workspace if not already
            if (!$user->workspaces->contains($workspace)) {
                $user->workspaces()->attach($workspace);
            }
            
            $users[] = $user;
        }
        
        return $users;
    }
    
    /**
     * Create clients
     */
    private function createClients($workspace, $professions)
    {
        $clientsData = [
            [
                'first_name' => 'محمد',
                'last_name' => 'أحمد',
                'email' => 'client1@workflow.com',
                'phone' => '+967777000001',
                'profession_id' => null,
            ],
            [
                'first_name' => 'شركة البناء',
                'last_name' => 'المقاولات',
                'email' => 'client2@workflow.com',
                'phone' => '+967777000002',
                'profession_id' => $professions[0]->id ?? null, // Project Manager
            ],
            [
                'first_name' => 'مؤسسة',
                'last_name' => 'التنمية',
                'email' => 'client3@workflow.com',
                'phone' => '+967777000003',
                'profession_id' => null,
            ],
        ];
        
        $clients = [];
        foreach ($clientsData as $data) {
            $client = Client::firstOrCreate(
                ['email' => $data['email'], 'workspace_id' => $workspace->id],
                $data
            );
            $clients[] = $client;
        }
        
        return $clients;
    }
    
    /**
     * Create EXACTLY 5 projects
     */
    private function createProjects($workspace, $clients, $statuses, $priorities, $users)
    {
        $projectsData = [
            [
                'title' => 'مشروع البناء السكني الأول',
                'description' => 'بناء مجمع سكني متكامل - المرحلة الأولى',
                'status_id' => $statuses[1]->id ?? null, // In Progress
                'priority_id' => $priorities[2]->id ?? null, // High
                'budget' => 1500000,
            ],
            [
                'title' => 'مشروع الطريق الرئيسي',
                'description' => 'إنشاء طريق رئيسي يربط المناطق',
                'status_id' => $statuses[0]->id ?? null, // Planning
                'priority_id' => $priorities[3]->id ?? null, // Critical
                'budget' => 2000000,
            ],
            [
                'title' => 'مشروع الجسر الحديث',
                'description' => 'بناء جسر حديث فوق النهر',
                'status_id' => $statuses[3]->id ?? null, // Completed
                'priority_id' => $priorities[2]->id ?? null, // High
                'budget' => 3000000,
            ],
            [
                'title' => 'مشروع المركز التجاري',
                'description' => 'تشييد مركز تجاري ضخم',
                'status_id' => $statuses[1]->id ?? null, // In Progress
                'priority_id' => $priorities[1]->id ?? null, // Medium
                'budget' => 2500000,
            ],
            [
                'title' => 'مشروع المستشفى المتخصص',
                'description' => 'بناء مستشفى متخصص مجهز',
                'status_id' => $statuses[0]->id ?? null, // Planning
                'priority_id' => $priorities[3]->id ?? null, // Critical
                'budget' => 4000000,
            ],
        ];
        
        $projects = [];
        foreach ($projectsData as $index => $data) {
            $project = Project::create(array_merge($data, [
                'user_id' => $users[0]->id ?? 1,
                'workspace_id' => $workspace->id,
                'start_date' => now()->subMonths(6 - $index),
                'end_date' => now()->addMonths(12 + $index),
            ]));
            
            // Attach 1-2 clients to each project
            $randomClients = $clients->take(min(2, $clients->count()))->pluck('id')->toArray();
            $project->clients()->attach($randomClients);
            
            // Attach 2-3 users to each project
            $randomUsers = $users->take(min(3, $users->count()))->slice(0, 3)->pluck('id')->toArray();
            $project->users()->attach($randomUsers);
            
            $projects[] = $project;
        }
        
        return $projects;
    }
    
    /**
     * Create contract types
     */
    private function createContractTypes($workspace)
    {
        $typesData = [
            ['type' => 'Construction', 'type_ar' => 'مقاولات بناء'],
            ['type' => 'Maintenance', 'type_ar' => 'صيانة'],
            ['type' => 'Consulting', 'type_ar' => 'استشارات'],
            ['type' => 'Supply', 'type_ar' => 'توريد'],
            ['type' => 'Design', 'type_ar' => 'تصميم'],
        ];
        
        $types = [];
        foreach ($typesData as $data) {
            $type = ContractType::firstOrCreate(
                ['type' => $data['type'], 'workspace_id' => $workspace->id],
                $data
            );
            $types[] = $type;
        }
        
        return $types;
    }
    
    /**
     * Create items (BOQ)
     */
    private function createItems($workspace, $units, $professions)
    {
        $itemsData = [
            ['title' => 'Concrete Work', 'title_ar' => 'أعمال الخرسانة', 'price' => 150.00],
            ['title' => 'Steel Reinforcement', 'title_ar' => 'أعمال التسليح', 'price' => 850.00],
            ['title' => 'Brick Work', 'title_ar' => 'أعمال الطوب', 'price' => 45.00],
            ['title' => 'Plastering', 'title_ar' => 'أعمال اللياسة', 'price' => 25.00],
            ['title' => 'Painting', 'title_ar' => 'أعمال الدهان', 'price' => 15.00],
            ['title' => 'Electrical Work', 'title_ar' => 'أعمال الكهرباء', 'price' => 120.00],
            ['title' => 'Plumbing Work', 'title_ar' => 'أعمال السباكة', 'price' => 95.00],
            ['title' => 'Flooring', 'title_ar' => 'أعمال الأرضيات', 'price' => 75.00],
            ['title' => 'Windows Installation', 'title_ar' => 'تركيب النوافذ', 'price' => 200.00],
            ['title' => 'Doors Installation', 'title_ar' => 'تركيب الأبواب', 'price' => 180.00],
        ];
        
        $items = [];
        foreach ($itemsData as $data) {
            $item = Item::create(array_merge($data, [
                'workspace_id' => $workspace->id,
                'unit_id' => $units[array_rand($units)]->id,
                'profession_id' => $professions[array_rand($professions)]->id,
            ]));
            $items[] = $item;
        }
        
        return $items;
    }
    
    /**
     * Create EXACTLY 10 contracts distributed across the 5 projects (2 contracts per project)
     */
    private function createContracts($workspace, $clients, $projects, $contractTypes, $users, $professions, $items)
    {
        $contracts = [];
        
        // Create exactly 2 contracts for each of the 5 projects = 10 contracts total
        $contractIndex = 1;
        foreach ($projects as $project) {
            for ($i = 0; $i < 2; $i++) {
                $contract = Contract::create([
                    'workspace_id' => $workspace->id,
                    'title' => 'عقد مشروع ' . $project->title . ' - رقم ' . ($i + 1),
                    'value' => rand(500000, 1500000),
                    'start_date' => now()->subMonths(rand(1, 6)),
                    'end_date' => now()->addMonths(rand(6, 18)),
                    'client_id' => $clients->random()->id,
                    'project_id' => $project->id,
                    'contract_type_id' => $contractTypes->random()->id,
                    'profession_id' => $professions[0]->id ?? null,
                    'description' => 'وصف تفصيلي للعقد يشمل جميع البنود والمواصفات المطلوبة للمشروع',
                    'created_by' => $users[0]->id ?? 1,
                    
                    // Workflow role assignments
                    'site_supervisor_id' => $users[1]->id ?? null, // Site Supervisor
                    'quantity_approver_id' => $users[2]->id ?? null, // Quantity Approver
                    'accountant_id' => $users[3]->id ?? null, // Accountant
                    'reviewer_id' => $users[4]->id ?? null, // Reviewer
                    'final_approver_id' => $users[5]->id ?? null, // Final Approver
                    
                    // Varied workflow status for testing
                    'workflow_status' => ['draft', 'quantity_approval', 'management_approval', 'approved', 'archived'][array_rand(['draft', 'quantity_approval', 'management_approval', 'approved', 'archived'])],
                    'quantity_approval_status' => 'pending',
                    'management_review_status' => 'pending',
                    'accounting_review_status' => 'pending',
                    'final_approval_status' => 'pending',
                ]);
                
                $contracts[] = $contract;
                $contractIndex++;
            }
        }
        
        return $contracts;
    }
    
    /**
     * Create tasks for all 10 contracts and 5 projects
     */
    private function createTasks($projects, $contracts, $users, $statuses, $priorities)
    {
        $tasksData = [];
        
        // Create 3 tasks for each contract (10 contracts × 3 = 30 tasks)
        foreach ($contracts as $contract) {
            for ($i = 0; $i < 3; $i++) {
                $tasksData[] = [
                    'type' => 'contract',
                    'entity_id' => $contract->id,
                    'title' => 'مهمة العقد ' . $contract->title . ' - رقم ' . ($i + 1),
                    'description' => 'وصف المهمة المتعلقة بالعقد',
                    'status_id' => $statuses[array_rand($statuses)]->id,
                    'priority_id' => $priorities[array_rand($priorities)]->id,
                ];
            }
        }
        
        // Create 5 tasks for each project (5 projects × 5 = 25 tasks)
        foreach ($projects as $project) {
            for ($i = 0; $i < 5; $i++) {
                $tasksData[] = [
                    'type' => 'project',
                    'entity_id' => $project->id,
                    'title' => 'مهمة المشروع ' . $project->title . ' - رقم ' . ($i + 1),
                    'description' => 'وصف المهمة المتعلقة بالمشروع',
                    'status_id' => $statuses[array_rand($statuses)]->id,
                    'priority_id' => $priorities[array_rand($priorities)]->id,
                ];
            }
        }
        
        // Create all tasks
        foreach ($tasksData as $taskData) {
            $task = \App\Models\Task::create([
                'workspace_id' => $projects->first()->workspace_id,
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'status_id' => $taskData['status_id'],
                'priority_id' => $taskData['priority_id'],
                'user_id' => $users[0]->id ?? 1,
                'created_by' => $users[0]->id ?? 1,
                'contract_id' => $taskData['type'] === 'contract' ? $taskData['entity_id'] : null,
                'project_id' => $taskData['type'] === 'project' ? $taskData['entity_id'] : null,
                'start_date' => now()->subDays(rand(1, 30)),
                'due_date' => now()->addDays(rand(1, 60)),
            ]);
            
            // Assign task to 1-2 users
            $assignedUsers = $users->take(rand(1, 2))->pluck('id')->toArray();
            $task->users()->attach($assignedUsers);
        }
    }
}
