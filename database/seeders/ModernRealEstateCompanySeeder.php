<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Workspace;
use App\Models\Contract;
use App\Models\EstimatesInvoice;
use App\Models\Task;
use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use App\Models\Profession;
use App\Models\ContractQuantity;
use App\Models\Item;
use Carbon\Carbon;

class ModernRealEstateCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have workspace ID 1 with the correct name
        $workspace = Workspace::updateOrCreate(
            ['id' => 1],
            [
                'user_id' => 1,
                'title' => 'الشركة العقارية الحديثة',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Get or create a user for this workspace
        $user = User::firstOrCreate(
            ['email' => 'admin@modernrealestate.com'],
            [
                'first_name' => 'مدير',
                'last_name' => 'الشركة',
                'password' => bcrypt('12345678'),
                'email_verified_at' => now(),
                'status' => 1,
                'default_workspace_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        // Get all users, clients, and other models
        $users = User::all()->toArray();
        $clients = Client::all()->toArray();
        
        // Convert back to collections for random selection
        $usersCollection = User::all();
        $clientsCollection = Client::all();

        // Create or get existing professions
        $constructionProfession = Profession::firstOrCreate(
            ['name' => 'الإنشاءات والتشييد', 'workspace_id' => 1],
            ['description' => 'مهنة البناء والتشييد والمشاريع الإنشائية']
        );

        $realEstateProfession = Profession::firstOrCreate(
            ['name' => 'ال.transactions العقارية', 'workspace_id' => 1],
            ['description' => 'مهنة البيع والشراء والتأجير العقاري']
        );
        
        $engineeringProfession = Profession::firstOrCreate(
            ['name' => 'الهندسة', 'workspace_id' => 1],
            ['description' => 'مهندسي المشروع ومشرفي الموقع']
        );
        
        $accountingProfession = Profession::firstOrCreate(
            ['name' => 'المحاسبة', 'workspace_id' => 1],
            ['description' => 'المحاسبين والماليين']
        );
        
        // Also create a general profession
        $generalProfession = Profession::firstOrCreate(
            ['name' => 'عام', 'workspace_id' => 1],
            ['description' => 'مهنة عامة لجميع المستخدمين']
        );

        // Create contract types
        $contractType = \App\Models\ContractType::firstOrCreate(
            ['type' => 'عقد إنشاءات', 'workspace_id' => 1]
        );

        // Create or get clients
        $clients = [];
        $clientNames = [
            ['first_name' => 'محمد', 'last_name' => 'العمري', 'company' => 'شركة العمري للتجارة'],
            ['first_name' => ' أحمد', 'last_name' => 'الصوفي', 'company' => 'مؤسسة الصوفي الهندسية'],
            ['first_name' => 'فاطمة', 'last_name' => 'العدوي', 'company' => 'شركة العدوي العقارية'],
            ['first_name' => 'علي', 'last_name' => 'الشهري', 'company' => 'مكتب الشهري للمقاولات'],
            ['first_name' => 'سارة', 'last_name' => 'الحميدي', 'company' => 'شركة الحميدي للتطوير العقاري']
        ];

        foreach ($clientNames as $index => $clientData) {
            $client = Client::firstOrCreate(
                ['email' => 'client' . ($index + 1) . '@example.com'],
                array_merge($clientData, [
                    'password' => bcrypt('12345678'),
                    'email_verified_at' => now(),
                    'status' => 1,
                    'profession_id' => $index % 2 == 0 ? $constructionProfession->id : $realEstateProfession->id,
                    'default_workspace_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
            
            // Attach client to workspace
            if (!$client->workspaces()->where('workspace_id', 1)->exists()) {
                $client->workspaces()->attach(1);
            }
            
            $clients[] = $client;
        }

        // Create exactly 5 Yemeni projects as per requirements
        $projects = [];
        $yemeniProjectNames = [
            'مشروع توسعة شارع صنعاء - تعز',
            'مشروع صيانة جسور محافظة حضرموت',
            'مشروع تطوير ميناء الحديدة',
            'مشروع توسعة مستشفى الجمهورية - صنعاء',
            'مشروع تطوير مجمع تعليمي - تعز'
        ];

        foreach ($yemeniProjectNames as $index => $projectName) {
            $project = Project::firstOrCreate(
                ['title' => $projectName, 'workspace_id' => 1],
                [
                    'user_id' => 'u_' . $usersCollection->random()->id,
                    'client_id' => 'c_' . $clientsCollection->random()->id,
                    'description' => 'مشروع ' . $projectName . ' في الجمهورية اليمنية',
                    'status' => 'active',
                    'start_date' => Carbon::now()->subDays(rand(0, 100)),
                    'end_date' => Carbon::now()->addDays(rand(180, 720)),
                    'budget' => (rand(30, 120)) * 1000000, // Random budget between 30-120 million
                    'status_id' => 1,
                    'priority_id' => rand(1, 3), // Random priority
                    'created_by' => $usersCollection->random()->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
            
            $projects[] = $project;
        }
        
        // Note: profession_id is not added to projects as it doesn't exist in the projects table

        // Create exactly 10 contracts distributed across the 5 projects as per requirements
        Contract::where('workspace_id', 1)->delete(); // Clean existing contracts for workspace 1
        
        $yemeniContractData = [
            // Contracts for Project 0: مشروع توسعة شارع صنعاء - تعز
            [
                'title' => 'عقد توسعة طريق صنعاء - تعز - المرحلة الأولى',
                'value' => '30000000.00',
                'description' => 'عقد تنفيذ المرحلة الأولى من مشروع توسعة وتطوير الطريق الرئيسي بين محافظتي صنعاء وتعز',
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(200),
                'project_id' => $projects[0]->id,
                'client_id' => $clients[0]->id,
                'profession_id' => $constructionProfession->id,
                'workflow_status' => 'site_supervisor_upload'
            ],
            [
                'title' => 'عقد توسعة طريق صنعاء - تعز - المرحلة الثانية',
                'value' => '20000000.00',
                'description' => 'عقد تنفيذ المرحلة الثانية من مشروع توسعة وتطوير الطريق الرئيسي بين محافظتي صنعاء وتعز',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(180),
                'project_id' => $projects[0]->id,
                'client_id' => $clients[1]->id,
                'profession_id' => $constructionProfession->id,
                'workflow_status' => 'quantity_approval'
            ],
            // Contracts for Project 1: مشروع صيانة جسور محافظة حضرموت
            [
                'title' => 'عقد صيانة جسور محافظة حضرموت - المنطقة الشرقية',
                'value' => '45000000.00',
                'description' => 'عقد صيانة وترميم الجسور في المنطقة الشرقية من محافظة حضرموت',
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->addDays(120),
                'project_id' => $projects[1]->id,
                'client_id' => $clients[2]->id,
                'profession_id' => $constructionProfession->id,
                'workflow_status' => 'management_review'
            ],
            [
                'title' => 'عقد صيانة جسور محافظة حضرموت - المنطقة الغربية',
                'value' => '30000000.00',
                'description' => 'عقد صيانة وترميم الجسور في المنطقة الغربية من محافظة حضرموت',
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(150),
                'project_id' => $projects[1]->id,
                'client_id' => $clients[3]->id,
                'profession_id' => $constructionProfession->id,
                'workflow_status' => 'accounting_processing'
            ],
            // Contracts for Project 2: مشروع تطوير ميناء الحديدة
            [
                'title' => 'عقد تطوير ميناء الحديدة - المرسى الجنوبي',
                'value' => '70000000.00',
                'description' => 'عقد تطوير وتوسعة المرسى الجنوبي في ميناء الحديدة',
                'start_date' => Carbon::now()->subDays(60),
                'end_date' => Carbon::now()->addDays(300),
                'project_id' => $projects[2]->id,
                'client_id' => $clients[4]->id,
                'profession_id' => $constructionProfession->id,
                'workflow_status' => 'final_review'
            ],
            [
                'title' => 'عقد تطوير ميناء الحديدة - المرسى الشمالي',
                'value' => '50000000.00',
                'description' => 'عقد تطوير وتوسعة المرسى الشمالي في ميناء الحديدة',
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(360),
                'project_id' => $projects[2]->id,
                'client_id' => $clients[0]->id,
                'profession_id' => $constructionProfession->id,
                'workflow_status' => 'approved'
            ],
            // Contracts for Project 3: مشروع توسعة مستشفى الجمهورية - صنعاء
            [
                'title' => 'عقد توسعة مستشفى الجمهورية - قسم الجراحة',
                'value' => '25000000.00',
                'description' => 'عقد توسعة وتطوير قسم الجراحة في مستشفى الجمهورية بصنعاء',
                'start_date' => Carbon::now()->subDays(45),
                'end_date' => Carbon::now()->addDays(180),
                'project_id' => $projects[3]->id,
                'client_id' => $clients[1]->id,
                'profession_id' => $constructionProfession->id,
                'workflow_status' => 'archived',
                'is_archived' => true,
                'archived_at' => Carbon::now()->subDays(10)
            ],
            [
                'title' => 'عقد توسعة مستشفى الجمهورية - قسم الأطفال',
                'value' => '20000000.00',
                'description' => 'عقد توسعة وتطوير قسم الأطفال في مستشفى الجمهورية بصنعاء',
                'start_date' => Carbon::now()->subDays(20),
                'end_date' => Carbon::now()->addDays(210),
                'project_id' => $projects[3]->id,
                'client_id' => $clients[2]->id,
                'profession_id' => $constructionProfession->id,
                'workflow_status' => 'quantity_approval'
            ],
            // Contracts for Project 4: مشروع تطوير مجمع تعليمي - تعز
            [
                'title' => 'عقد تطوير مجمع تعليمي - المدرسة الابتدائية',
                'value' => '35000000.00',
                'description' => 'عقد بناء مدرسة ابتدائية ضمن المجمع التعليمي في محافظة تعز',
                'start_date' => Carbon::now()->subDays(75),
                'end_date' => Carbon::now()->addDays(240),
                'project_id' => $projects[4]->id,
                'client_id' => $clients[3]->id,
                'profession_id' => $constructionProfession->id,
                'workflow_status' => 'approved'
            ],
            [
                'title' => 'عقد تطوير مجمع تعليمي - المدرسة الثانوية',
                'value' => '25000000.00',
                'description' => 'عقد بناء مدرسة ثانوية ضمن المجمع التعليمي في محافظة تعز',
                'start_date' => Carbon::now()->subDays(50),
                'end_date' => Carbon::now()->addDays(270),
                'project_id' => $projects[4]->id,
                'client_id' => $clients[4]->id,
                'profession_id' => $constructionProfession->id,
                'workflow_status' => 'management_review'
            ]
        ];

        // Helper functions for workflow statuses
        $getStatusForWorkflow = function($workflowStatus, $stage) {
            switch ($stage) {
                case 'quantity_approval':
                    return in_array($workflowStatus, ['quantity_approval', 'management_review', 'accounting_processing', 'final_review', 'approved', 'archived']) ? 'approved' : 'pending';
                case 'management_review':
                    return in_array($workflowStatus, ['management_review', 'accounting_processing', 'final_review', 'approved', 'archived']) ? 'approved' : 'pending';
                case 'accounting_processing':
                    return in_array($workflowStatus, ['accounting_processing', 'final_review', 'approved', 'archived']) ? 'approved' : 'pending';
                case 'final_review':
                    return in_array($workflowStatus, ['final_review', 'approved', 'archived']) ? 'approved' : 'pending';
                default:
                    return 'pending';
            }
        };
        
        $getFinancialStatus = function($workflowStatus) {
            if ($workflowStatus === 'archived') return 'completed';
            if (in_array($workflowStatus, ['approved', 'final_review', 'accounting_processing'])) return 'invoiced';
            return 'pending';
        };
        
        $contracts = [];
        foreach ($yemeniContractData as $index => $data) {
            // Select different users for different roles
            $siteSupervisor = $usersCollection->random();
            $quantityApprover = $usersCollection->random();
            $accountant = $usersCollection->random();
            $reviewer = $usersCollection->random();
            $finalApprover = $usersCollection->random();
            
            $contract = Contract::create(array_merge($data, [
                'workspace_id' => 1,
                'contract_type_id' => $contractType->id,
                'created_by' => 'u_' . $usersCollection->random()->id,
                // Assign workflow roles with proper permissions
                'site_supervisor_id' => $siteSupervisor->id,
                'quantity_approver_id' => $quantityApprover->id,
                'accountant_id' => $accountant->id,
                'reviewer_id' => $reviewer->id,
                'final_approver_id' => $finalApprover->id,
                'profession_id' => [$constructionProfession->id, $engineeringProfession->id, $accountingProfession->id, $generalProfession->id][rand(0, 3)],
                // Set appropriate statuses based on workflow stage
                'quantity_approval_status' => $getStatusForWorkflow($data['workflow_status'], 'quantity_approval'),
                'management_review_status' => $getStatusForWorkflow($data['workflow_status'], 'management_review'),
                'accounting_review_status' => $getStatusForWorkflow($data['workflow_status'], 'accounting_processing'),
                'final_approval_status' => $getStatusForWorkflow($data['workflow_status'], 'final_review'),
                'financial_status' => $getFinancialStatus($data['workflow_status']),
                'created_at' => now(),
                'updated_at' => now()
            ]));
            
            $contracts[] = $contract;
        }

        // Create items for contract quantities
        $items = [];
        $itemNames = [
            'مواد البناء الأساسية',
            'الصلب والمعدن',
            'الكهرباء والتركيبات',
            'الصرف الصحي والمواسير',
            'الدهانات والألوان',
            'الأبواب والنوافذ',
            'البلاط والسيراميك',
            'الأثاث والمفروشات',
            'المعدات الطبية',
            'الألعاب الترفيهية'
        ];

        foreach ($itemNames as $index => $itemName) {
            $item = Item::firstOrCreate(
                ['title' => $itemName, 'workspace_id' => 1],
                [
                    'description' => 'وصف ' . $itemName,
                    'price' => ($index + 1) * 1000,
                    'unit_id' => 1,
                    'profession_id' => $index % 2 == 0 ? $constructionProfession->id : $realEstateProfession->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
            $items[] = $item;
        }

        // Create contract quantities for each contract
        foreach ($contracts as $contractIndex => $contract) {
            $quantities = [
                ['item_id' => $items[$contractIndex]->id, 'quantity' => 50, 'unit_price' => ($contractIndex + 1) * 1000],
                ['item_id' => $items[($contractIndex + 1) % count($items)]->id, 'quantity' => 30, 'unit_price' => ($contractIndex + 2) * 800]
            ];
            
            foreach ($quantities as $quantityData) {
                ContractQuantity::create([
                    'contract_id' => $contract->id,
                    'user_id' => $user->id,
                    'workspace_id' => 1,
                    'item_description' => Item::find($quantityData['item_id'])->title,
                    'requested_quantity' => $quantityData['quantity'],
                    'approved_quantity' => $quantityData['quantity'],
                    'unit' => 'وحدة',
                    'unit_price' => $quantityData['unit_price'],
                    'total_amount' => $quantityData['quantity'] * $quantityData['unit_price'],
                    'status' => 'approved',
                    'submitted_at' => now(),
                    'approved_rejected_at' => now(),
                    'approved_rejected_by' => $user->id,
                    'approval_rejection_notes' => 'تمت الموافقة تلقائياً',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Create extracts (estimates_invoices) - 1-2 per contract
        EstimatesInvoice::where('workspace_id', 1)->delete(); // Clean existing extracts for workspace 1
        
        $extractTypes = ['estimate', 'invoice'];
        foreach ($contracts as $contractIndex => $contract) {
            // Create 1-2 extracts per contract
            $extractsCount = rand(1, 2);
            
            for ($i = 0; $i < $extractsCount; $i++) {
                $extract = EstimatesInvoice::create([
                    'workspace_id' => 1,
                    'client_id' => $contract->client_id,
                    'contract_id' => $contract->id,
                    'type' => $extractTypes[$i % 2],
                    'name' => 'مستخلص ' . ($i + 1) . ' - ' . $contract->title,
                    'address' => 'صنعاء',
                    'city' => 'صنعاء',
                    'state' => 'صنعاء',
                    'country' => 'اليمن',
                    'zip_code' => 00000,
                    'phone' => 000000000,
                    'from_date' => Carbon::now(),
                    'to_date' => Carbon::now()->addMonth(),
                    'total' => ($contract->value * 0.3) / $extractsCount,
                    'tax_amount' => ($contract->value * 0.15) / $extractsCount,
                    'final_total' => ($contract->value * 0.35) / $extractsCount,
                    'status' => 'sent',
                    'created_by' => 'u_' . $user->id,
                    'created_at' => now()->addDays($i * 7),
                    'updated_at' => now()->addDays($i * 7)
                ]);
            }
        }

        // Create professional Arabic tasks related to contracts and projects
        Task::where('workspace_id', 1)->delete(); // Clean existing tasks for workspace 1
        
        // Create 20 tasks distributed across contracts and projects
        $taskTitles = [
            'مراجعة شروط العقد وإعداد المستندات القانونية',
            'التنسيق مع المقاولين لتنفيذ الأعمال',
            'متابعة جودة المواد المستخدمة في المشروع',
            'إعداد تقارير التقدم الشهرية للعقود',
            'مراجعة وتصدير المستخلصات المالية',
            'تنظيم اجتماعات المتابعة مع العملاء',
            'التحقق من الامتثال للمواصفات الفنية',
            'إعداد وثائق الاستلام والتسليم',
            'تحديث سجل الأحداث والتوقيعات',
            'إدارة الالتزامات والمستندات الداعمة',
            'متابعة الحالة والتقدم في الالتزامات',
            'إرسال إشعارات التذكير بالمواعيد النهائية',
            'تحليل حالة الامتثال الشامل',
            'إعداد تقارير الأداء والإنجاز'
        ];
        
        $taskDescriptions = [
            'مراجعة شاملة لجميع شروط العقد وإعداد المستندات القانونية المطلوبة',
            'التنسيق مع المقاولين والموردين لتنفيذ أعمال البناء وفقاً للمواصفات المطلوبة',
            'متابعة ومراقبة جودة جميع المواد المستخدمة في المشروع والتأكد من توافقها مع المواصفات',
            'إعداد تقارير شهرية مفصلة عن تقدم تنفيذ العقود والمقارنة بالجداول الزمنية',
            'مراجعة المستخلصات المالية المستلمة والتأكد من دقتها قبل تصديرها للمحاسبة',
            'تنظيم وجدولة اجتماعات دورية مع العملاء لمتابعة تقدم المشاريع وحل المشكلات',
            'التحقق من امتثال جميع الأعمال المنفذة للمواصفات الفنية والمعايير المطلوبة',
            'إعداد جميع الوثائق المطلوبة لاستلام وتسليم المشاريع المكتملة',
            'تحديث سجل الأحداث والتوقيعات الإلكترونية لكل عملية في دورة حياة العقد',
            'إدارة الالتزامات المختلفة والمستندات الداعمة لكل التزام',
            'مراقبة الحالة والتقدم في تنفيذ جميع الالتزامات المحددة',
            'إرسال إشعارات تلقائية عند اقتراب المواعيد النهائية للالتزامات',
            'تحليل شامل لحالة الامتثال لجميع الالتزامات في العقد',
            'إعداد تقارير مفصلة عن الأداء والإنجاز في تنفيذ الالتزامات'
        ];
        
        $taskStatuses = ['active', 'in_progress', 'completed'];
        $statusIds = [1, 2, 3];
        
        // Create tasks distributed among contracts and projects
        for ($i = 0; $i < 20; $i++) { // Create 20 tasks total
            $contract = $contracts[array_rand($contracts)];
            $project = $projects[array_rand($projects)];
            $assignedUser = $usersCollection->random();
            
            Task::create([
                'title' => $taskTitles[array_rand($taskTitles)],
                'description' => $taskDescriptions[array_rand($taskDescriptions)],
                'project_id' => $project->id,
                'contract_id' => $contract->id, // Link task to a specific contract
                'user_id' => 'u_' . $assignedUser->id,
                'workspace_id' => 1,
                'status_id' => $statusIds[array_rand($statusIds)],
                'status' => $taskStatuses[array_rand($taskStatuses)],
                'start_date' => Carbon::now()->subDays(rand(0, 60)),
                'due_date' => Carbon::now()->addDays(rand(7, 90)),
                'created_by' => $assignedUser->id,
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('تم إنشاء البيانات بنجاح:');
        $this->command->info('-_workspace: الشركة العقارية الحديثة (ID: 1)');
        $this->command->info('- العقود: ' . Contract::where('workspace_id', 1)->count() . ' عقد');
        $this->command->info('- المستخلصات: ' . EstimatesInvoice::where('workspace_id', 1)->count() . ' مستخلص');
        $this->command->info('- المهام: ' . Task::where('workspace_id', 1)->count() . ' مهمة');
        $this->command->info('- العملاء: ' . Client::where('default_workspace_id', 1)->count() . ' عميل');
        $this->command->info('- المشاريع: ' . Project::where('workspace_id', 1)->count() . ' مشروع');
    }
}