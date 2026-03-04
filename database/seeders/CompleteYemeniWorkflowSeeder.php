<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Workspace;
use App\Models\User;
use App\Models\Project;
use App\Models\Contract;
use App\Models\ContractItem;
use App\Models\ContractQuantity;
use App\Models\EstimatesInvoice;
use App\Models\Task;
use App\Models\Client;
use App\Models\Profession;
use App\Models\ContractType;
use App\Models\Item;
use App\Models\ContractObligation;
use Carbon\Carbon;

class CompleteYemeniWorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds with complete workflow implementation
     * Following the scenario: one workspace, 5 projects, 10 contracts, 
     * distributed tasks, and complete workflow process
     */
    public function run(): void
    {
        // Create/update single workspace
        $workspace = Workspace::updateOrCreate(
            ['id' => 1],
            [
                'user_id' => 1,
                'title' => 'ال contratosystem - النظام الشامل لإدارة العقود',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Get all necessary models
        $users = User::all();
        $clients = Client::all();
        $professions = Profession::all();
        $contractTypes = ContractType::all();
        $items = Item::all();

        if ($users->isEmpty() || $clients->isEmpty() || $professions->isEmpty() || $contractTypes->isEmpty() || $items->isEmpty()) {
            $this->command->error('Please run basic seeders first to create users, clients, professions, contract types, and items');
            return;
        }

        // Clear existing data for this workspace to avoid duplicates
        Task::where('workspace_id', 1)->delete();
        EstimatesInvoice::where('workspace_id', 1)->delete();
        ContractQuantity::where('workspace_id', 1)->delete();
        Contract::where('workspace_id', 1)->delete();
        Project::where('workspace_id', 1)->delete();

        // Create exactly 5 projects as per requirements
        $yemeniProjects = [
            [
                'title' => 'مشروع توسعة شارع صنعاء - تعز',
                'description' => 'مشروع توسعة وتطوير الطريق الرئيسي بين محافظتي صنعاء وتعز',
                'budget' => 50000000.00,
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(365),
            ],
            [
                'title' => 'مشروع صيانة جسور محافظة حضرموت',
                'description' => 'مشروع صيانة وترميم الجسور في محافظة حضرموت',
                'budget' => 75000000.00,
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(180),
            ],
            [
                'title' => 'مشروع تطوير ميناء الحديدة',
                'description' => 'مشروع تطوير وتوسعة ميناء الحديدة',
                'budget' => 120000000.00,
                'start_date' => now()->subDays(60),
                'end_date' => now()->addDays(540),
            ],
            [
                'title' => 'مشروع توسعة مستشفى الجمهورية - صنعاء',
                'description' => 'مشروع توسعة وتطوير مستشفى الجمهورية بصنعاء',
                'budget' => 45000000.00,
                'start_date' => now()->subDays(45),
                'end_date' => now()->addDays(270),
            ],
            [
                'title' => 'مشروع تطوير مجمع تعليمي - تعز',
                'description' => 'مشروع بناء مجمع تعليمي متكامل في محافظة تعز',
                'budget' => 60000000.00,
                'start_date' => now()->subDays(75),
                'end_date' => now()->addDays(360),
            ]
        ];

        $projects = [];
        foreach ($yemeniProjects as $projectData) {
            $project = Project::create([
                'title' => $projectData['title'],
                'description' => $projectData['description'],
                'workspace_id' => 1,
                'user_id' => $users->random()->id,
                'client_id' => $clients->random()->id,
                'status' => 'active',
                'budget' => $projectData['budget'],
                'start_date' => $projectData['start_date'],
                'end_date' => $projectData['end_date'],
                'created_by' => $users->random()->id,
            ]);
            $projects[] = $project;
        }

        // Create exactly 10 contracts distributed across the 5 projects
        $yemeniContracts = [
            [
                'title' => 'عقد توسعة طريق صنعاء - تعز - المرحلة الأولى',
                'value' => 30000000.00,
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(200),
                'description' => 'عقد تنفيذ المرحلة الأولى من مشروع توسعة وتطوير الطريق الرئيسي بين محافظتي صنعاء وتعز',
                'project_id' => $projects[0]->id,
                'workflow_status' => 'site_supervisor_upload',
            ],
            [
                'title' => 'عقد توسعة طريق صنعاء - تعز - المرحلة الثانية',
                'value' => 20000000.00,
                'start_date' => now()->subDays(10),
                'end_date' => now()->addDays(180),
                'description' => 'عقد تنفيذ المرحلة الثانية من مشروع توسعة وتطوير الطريق الرئيسي بين محافظتي صنعاء وتعز',
                'project_id' => $projects[0]->id,
                'workflow_status' => 'quantity_approval',
            ],
            [
                'title' => 'عقد صيانة جسور محافظة حضرموت - المنطقة الشرقية',
                'value' => 45000000.00,
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(120),
                'description' => 'عقد صيانة وترميم الجسور في المنطقة الشرقية من محافظة حضرموت',
                'project_id' => $projects[1]->id,
                'workflow_status' => 'management_review',
            ],
            [
                'title' => 'عقد صيانة جسور محافظة حضرموت - المنطقة الغربية',
                'value' => 30000000.00,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(150),
                'description' => 'عقد صيانة وترميم الجسور في المنطقة الغربية من محافظة حضرموت',
                'project_id' => $projects[1]->id,
                'workflow_status' => 'accounting_processing',
            ],
            [
                'title' => 'عقد تطوير ميناء الحديدة - المرسى الجنوبي',
                'value' => 70000000.00,
                'start_date' => now()->subDays(60),
                'end_date' => now()->addDays(300),
                'description' => 'عقد تطوير وتوسعة المرسى الجنوبي في ميناء الحديدة',
                'project_id' => $projects[2]->id,
                'workflow_status' => 'final_review',
            ],
            [
                'title' => 'عقد تطوير ميناء الحديدة - المرسى الشمالي',
                'value' => 50000000.00,
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(360),
                'description' => 'عقد تطوير وتوسعة المرسى الشمالي في ميناء الحديدة',
                'project_id' => $projects[2]->id,
                'workflow_status' => 'approved',
            ],
            [
                'title' => 'عقد توسعة مستشفى الجمهورية - قسم الجراحة',
                'value' => 25000000.00,
                'start_date' => now()->subDays(45),
                'end_date' => now()->addDays(180),
                'description' => 'عقد توسعة وتطوير قسم الجراحة في مستشفى الجمهورية بصنعاء',
                'project_id' => $projects[3]->id,
                'workflow_status' => 'archived',
                'is_archived' => true,
                'archived_at' => now()->subDays(10),
            ],
            [
                'title' => 'عقد توسعة مستشفى الجمهورية - قسم الأطفال',
                'value' => 20000000.00,
                'start_date' => now()->subDays(20),
                'end_date' => now()->addDays(210),
                'description' => 'عقد توسعة وتطوير قسم الأطفال في مستشفى الجمهورية بصنعاء',
                'project_id' => $projects[3]->id,
                'workflow_status' => 'quantity_approval',
            ],
            [
                'title' => 'عقد تطوير مجمع تعليمي - المدرسة الابتدائية',
                'value' => 35000000.00,
                'start_date' => now()->subDays(75),
                'end_date' => now()->addDays(240),
                'description' => 'عقد بناء مدرسة ابتدائية ضمن المجمع التعليمي في محافظة تعز',
                'project_id' => $projects[4]->id,
                'workflow_status' => 'approved',
            ],
            [
                'title' => 'عقد تطوير مجمع تعليمي - المدرسة الثانوية',
                'value' => 25000000.00,
                'start_date' => now()->subDays(50),
                'end_date' => now()->addDays(270),
                'description' => 'عقد بناء مدرسة ثانوية ضمن المجمع التعليمي في محافظة تعز',
                'project_id' => $projects[4]->id,
                'workflow_status' => 'management_review',
            ]
        ];

        $contracts = [];
        foreach ($yemeniContracts as $contractData) {
            $contract = Contract::create([
                'title' => $contractData['title'],
                'value' => $contractData['value'],
                'start_date' => $contractData['start_date'],
                'end_date' => $contractData['end_date'],
                'client_id' => $clients->random()->id,
                'project_id' => $contractData['project_id'],
                'workspace_id' => 1,
                'created_by' => $users->random()->id,
                'contract_type_id' => $contractTypes->random()->id,
                'description' => $contractData['description'],
                
                // Assign workflow roles
                'site_supervisor_id' => $users->random()->id,
                'quantity_approver_id' => $users->random()->id,
                'accountant_id' => $users->random()->id,
                'reviewer_id' => $users->random()->id,
                'final_approver_id' => $users->random()->id,
                
                // Workflow status
                'workflow_status' => $contractData['workflow_status'],
                'quantity_approval_status' => $this->getStatusForWorkflow($contractData['workflow_status'], 'quantity_approval'),
                'management_review_status' => $this->getStatusForWorkflow($contractData['workflow_status'], 'management_review'),
                'accounting_review_status' => $this->getStatusForWorkflow($contractData['workflow_status'], 'accounting_processing'),
                'final_approval_status' => $this->getStatusForWorkflow($contractData['workflow_status'], 'final_review'),
                
                // Archive data if needed
                'is_archived' => $contractData['is_archived'] ?? false,
                'archived_at' => $contractData['archived_at'] ?? null,
                'archived_by' => $contractData['is_archived'] ?? false ? $users->random()->id : null,
                
                // Financial integration
                'financial_status' => $this->getFinancialStatus($contractData['workflow_status']),
            ]);
            $contracts[] = $contract;
        }

        // Create contract quantities for site supervisor uploads
        $this->createContractQuantities($contracts, $users);

        // Create obligations for contracts
        $this->createContractObligations($contracts, $users);

        // Create extracts/invoices for accounting
        $this->createEstimatesInvoices($contracts, $clients, $users);

        // Create tasks distributed across contracts and projects
        $this->createTasks($contracts, $projects, $users);

        $this->command->info('تم إنشاء النظام الشامل لإدارة العقود بنجاح:');
        $this->command->info('- Workspace: النظام الشامل لإدارة العقود (ID: 1)');
        $this->command->info('- عدد المشاريع: ' . count($projects) . ' مشروع');
        $this->command->info('- عدد العقود: ' . count($contracts) . ' عقد');
        $this->command->info('- عدد الكميات: ' . ContractQuantity::count() . ' كمية');
        $this->command->info('- عدد المستخلصات: ' . EstimatesInvoice::where('workspace_id', 1)->count() . ' مستخلص');
        $this->command->info('- عدد المهام: ' . Task::where('workspace_id', 1)->count() . ' مهمة');
        $this->command->info('- عدد الالتزامات: ' . ContractObligation::count() . ' التزام');
    }

    private function getStatusForWorkflow($workflowStatus, $stage) {
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
    }

    private function getFinancialStatus($workflowStatus) {
        if ($workflowStatus === 'archived') return 'completed';
        if (in_array($workflowStatus, ['approved', 'final_review', 'accounting_processing'])) return 'invoiced';
        return 'pending';
    }

    private function createContractQuantities($contracts, $users) {
        $quantityStages = ['site_supervisor_upload', 'quantity_approval', 'management_review', 'accounting_processing', 'final_review', 'approved', 'archived'];

        foreach ($contracts as $contract) {
            if (in_array($contract->workflow_status, $quantityStages)) {
                // Create 2-3 quantity records per contract that are in the right stage
                $numQuantities = rand(2, 3);
                
                for ($i = 0; $i < $numQuantities; $i++) {
                    ContractQuantity::create([
                        'contract_id' => $contract->id,
                        'user_id' => $contract->site_supervisor_id,
                        'workspace_id' => 1,
                        'item_description' => 'بند ' . ($i + 1) . ' - ' . $contract->title,
                        'requested_quantity' => rand(10, 100),
                        'approved_quantity' => in_array($contract->workflow_status, ['quantity_approval', 'management_review', 'accounting_processing', 'final_review', 'approved', 'archived']) ? rand(10, 100) : null,
                        'unit' => ['متر', 'طن', 'وحدة', 'قطعة'][rand(0, 3)],
                        'unit_price' => rand(500, 5000),
                        'total_amount' => rand(10000, 500000),
                        'status' => $this->getQuantityStatus($contract->workflow_status),
                        'submitted_at' => now()->subDays(rand(0, 30)),
                        'approved_rejected_at' => in_array($contract->workflow_status, ['quantity_approval', 'management_review', 'accounting_processing', 'final_review', 'approved', 'archived']) ? now()->subDays(rand(0, 15)) : null,
                        'approved_rejected_by' => in_array($contract->workflow_status, ['quantity_approval', 'management_review', 'accounting_processing', 'final_review', 'approved', 'archived']) ? $contract->quantity_approver_id : null,
                        'approval_rejection_notes' => in_array($contract->workflow_status, ['quantity_approval', 'management_review', 'accounting_processing', 'final_review', 'approved', 'archived']) ? 'تمت الموافقة على الكمية وفق المواصفات' : null,
                        'created_at' => now()->subDays(rand(0, 30)),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function getQuantityStatus($workflowStatus) {
        if (in_array($workflowStatus, ['approved', 'archived', 'final_review', 'accounting_processing'])) return 'approved';
        if (in_array($workflowStatus, ['quantity_approval', 'management_review'])) return 'pending';
        return 'pending';
    }

    private function createContractObligations($contracts, $users) {
        $obligationTypes = ['payment', 'delivery', 'performance', 'compliance', 'reporting'];
        $obligationPriorities = ['low', 'medium', 'high', 'critical'];
        $obligationStatuses = ['pending', 'in_progress', 'completed', 'overdue', 'cancelled'];
        $obligationCompliance = ['compliant', 'non_compliant', 'partially_compliant'];

        foreach ($contracts as $contract) {
            // Create 1-2 obligations per contract
            $numObligations = rand(1, 2);
            
            for ($i = 0; $i < $numObligations; $i++) {
                ContractObligation::create([
                    'contract_id' => $contract->id,
                    'party_id' => $users->random()->id, // Using a user ID as the party
                    'party_type' => 'client',
                    'title' => 'الالتزام ' . ($i + 1) . ' - ' . $contract->title,
                    'description' => 'وصف التزام ' . ($i + 1) . ' لعقد ' . $contract->title . '. يتضمن متطلبات التزام محددة حسب نوع الالتزام.',
                    'obligation_type' => $obligationTypes[array_rand($obligationTypes)],
                    'assigned_to' => $users->random()->id,
                    'priority' => $obligationPriorities[array_rand($obligationPriorities)],
                    'status' => $obligationStatuses[array_rand($obligationStatuses)],
                    'compliance_status' => $obligationCompliance[array_rand($obligationCompliance)],
                    'due_date' => now()->addDays(rand(30, 180)),
                    'completed_date' => in_array($obligationStatuses[array_rand($obligationStatuses)], ['completed']) ? now()->subDays(rand(0, 30)) : null,
                    'notes' => 'ملاحظات حول تنفيذ الالتزام',
                    'created_at' => now()->subDays(rand(0, 60)),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function createEstimatesInvoices($contracts, $clients, $users) {
        $extractTypes = ['estimate', 'invoice'];

        foreach ($contracts as $contract) {
            // Create 1-2 extracts per contract depending on its status
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
                    'from_date' => now()->subDays(rand(0, 60)),
                    'to_date' => now()->addDays(rand(1, 30)),
                    'total' => ($contract->value * (0.2 + ($i * 0.1))) / $extractsCount,
                    'tax_amount' => ($contract->value * 0.05) / $extractsCount,
                    'final_total' => ($contract->value * (0.25 + ($i * 0.1))) / $extractsCount,
                    'status' => $this->getExtractStatus($contract->workflow_status),
                    'created_by' => 'u_' . $users->random()->id,
                    'created_at' => now()->subDays(rand(0, 30)),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function getExtractStatus($workflowStatus) {
        if ($workflowStatus === 'archived') return 'paid';
        if (in_array($workflowStatus, ['approved', 'final_review', 'accounting_processing'])) return 'sent';
        if (in_array($workflowStatus, ['management_review', 'quantity_approval'])) return 'draft';
        return 'pending';
    }

    private function createTasks($contracts, $projects, $users) {
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
        $statusIds = [1, 2, 3]; // Assuming 1=pending, 2=in progress, 3=completed

        // Create tasks distributed among contracts and projects
        for ($i = 0; $i < 20; $i++) { // Create 20 tasks total
            $contract = $contracts[array_rand($contracts)];
            $project = $projects[array_rand($projects)];
            
            Task::create([
                'title' => $taskTitles[array_rand($taskTitles)],
                'description' => $taskDescriptions[array_rand($taskDescriptions)],
                'project_id' => $project->id,
                'contract_id' => $contract->id, // Link task to a specific contract
                'user_id' => 'u_' . $users->random()->id,
                'workspace_id' => 1,
                'status_id' => $statusIds[array_rand($statusIds)],
                'status' => $taskStatuses[array_rand($taskStatuses)],
                'start_date' => now()->subDays(rand(0, 60)),
                'due_date' => now()->addDays(rand(7, 90)),
                'created_by' => $users->random()->id,
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now(),
            ]);
        }
    }
}