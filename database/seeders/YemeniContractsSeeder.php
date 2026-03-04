<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\ContractType;
use Illuminate\Database\Seeder;

class YemeniContractsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * NOTE: Contracts are created in ModernRealEstateCompanySeeder to ensure only 10 contracts exist as per requirements.
     * This seeder will only verify the contracts exist.
     */
    public function run(): void
    {
        // Verify that exactly 10 contracts exist in workspace 1
        $existingContracts = Contract::where('workspace_id', 1)->get();
        
        if ($existingContracts->count() >= 10) {
            $this->command->info('Found ' . $existingContracts->count() . ' contracts in workspace 1. Skipping creation.');
            return;
        }
        
        // Get related models
        $users = User::all();
        $clients = Client::all();
        $projects = Project::where('workspace_id', 1)->get();
        $contractTypes = ContractType::all();

        // Define exactly 10 Yemeni contract data as per requirements
        $contracts = [
            [
                'title' => 'عقد توسعة طريق صنعاء - تعز - المرحلة الأولى',
                'value' => 30000000.00,
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(200),
                'description' => 'عقد تنفيذ المرحلة الأولى من مشروع توسعة وتطوير الطريق الرئيسي بين محافظتي صنعاء وتعز',
                'project_id' => $projects[0]->id ?? 1,
                'workflow_status' => 'site_supervisor_upload',
                'quantity_approval_status' => 'pending',
                'management_review_status' => 'pending',
                'accounting_review_status' => 'pending',
                'final_approval_status' => 'pending',
            ],
            [
                'title' => 'عقد توسعة طريق صنعاء - تعز - المرحلة الثانية',
                'value' => 20000000.00,
                'start_date' => now()->subDays(10),
                'end_date' => now()->addDays(180),
                'description' => 'عقد تنفيذ المرحلة الثانية من مشروع توسعة وتطوير الطريق الرئيسي بين محافظتي صنعاء وتعز',
                'project_id' => $projects[0]->id ?? 1,
                'workflow_status' => 'quantity_approval',
                'quantity_approval_status' => 'pending',
                'management_review_status' => 'pending',
                'accounting_review_status' => 'pending',
                'final_approval_status' => 'pending',
            ],
            [
                'title' => 'عقد صيانة جسور محافظة حضرموت - المنطقة الشرقية',
                'value' => 45000000.00,
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(120),
                'description' => 'عقد صيانة وترميم الجسور في المنطقة الشرقية من محافظة حضرموت',
                'project_id' => $projects[1]->id ?? 1,
                'workflow_status' => 'management_review',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'pending',
                'accounting_review_status' => 'pending',
                'final_approval_status' => 'pending',
            ],
            [
                'title' => 'عقد صيانة جسور محافظة حضرموت - المنطقة الغربية',
                'value' => 30000000.00,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(150),
                'description' => 'عقد صيانة وترميم الجسور في المنطقة الغربية من محافظة حضرموت',
                'project_id' => $projects[1]->id ?? 1,
                'workflow_status' => 'accounting_processing',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'pending',
                'final_approval_status' => 'pending',
            ],
            [
                'title' => 'عقد تطوير ميناء الحديدة - المرسى الجنوبي',
                'value' => 70000000.00,
                'start_date' => now()->subDays(60),
                'end_date' => now()->addDays(300),
                'description' => 'عقد تطوير وتوسعة المرسى الجنوبي في ميناء الحديدة',
                'project_id' => $projects[2]->id ?? 1,
                'workflow_status' => 'final_review',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'approved',
                'final_approval_status' => 'pending',
            ],
            [
                'title' => 'عقد تطوير ميناء الحديدة - المرسى الشمالي',
                'value' => 50000000.00,
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(360),
                'description' => 'عقد تطوير وتوسعة المرسى الشمالي في ميناء الحديدة',
                'project_id' => $projects[2]->id ?? 1,
                'workflow_status' => 'approved',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'approved',
                'final_approval_status' => 'approved',
            ],
            [
                'title' => 'عقد توسعة مستشفى الجمهورية - قسم الجراحة',
                'value' => 25000000.00,
                'start_date' => now()->subDays(45),
                'end_date' => now()->addDays(180),
                'description' => 'عقد توسعة وتطوير قسم الجراحة في مستشفى الجمهورية بصنعاء',
                'project_id' => $projects[3]->id ?? 1,
                'workflow_status' => 'archived',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'approved',
                'final_approval_status' => 'approved',
                'is_archived' => true,
                'archived_at' => now()->subDays(10),
            ],
            [
                'title' => 'عقد توسعة مستشفى الجمهورية - قسم الأطفال',
                'value' => 20000000.00,
                'start_date' => now()->subDays(20),
                'end_date' => now()->addDays(210),
                'description' => 'عقد توسعة وتطوير قسم الأطفال في مستشفى الجمهورية بصنعاء',
                'project_id' => $projects[3]->id ?? 1,
                'workflow_status' => 'quantity_approval',
                'quantity_approval_status' => 'pending',
                'management_review_status' => 'pending',
                'accounting_review_status' => 'pending',
                'final_approval_status' => 'pending',
            ],
            [
                'title' => 'عقد تطوير مجمع تعليمي - المدرسة الابتدائية',
                'value' => 35000000.00,
                'start_date' => now()->subDays(75),
                'end_date' => now()->addDays(240),
                'description' => 'عقد بناء مدرسة ابتدائية ضمن المجمع التعليمي في محافظة تعز',
                'project_id' => $projects[4]->id ?? 1,
                'workflow_status' => 'approved',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'approved',
                'final_approval_status' => 'approved',
            ],
            [
                'title' => 'عقد تطوير مجمع تعليمي - المدرسة الثانوية',
                'value' => 25000000.00,
                'start_date' => now()->subDays(50),
                'end_date' => now()->addDays(270),
                'description' => 'عقد بناء مدرسة ثانوية ضمن المجمع التعليمي في محافظة تعز',
                'project_id' => $projects[4]->id ?? 1,
                'workflow_status' => 'management_review',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'pending',
                'accounting_review_status' => 'pending',
                'final_approval_status' => 'pending',
            ],
        ];

        foreach ($contracts as $index => $contractData) {
            $existingContract = Contract::where('title', $contractData['title'])
                                    ->where('workspace_id', 1)
                                    ->first();
            
            if (!$existingContract) {
                // Get random users for different roles
                $createdByUser = $users->random();
                $siteSupervisor = $users->random();
                $quantityApprover = $users->random();
                $accountant = $users->random();
                $reviewer = $users->random();
                $finalApprover = $users->random();
                
                Contract::create([
                    'title' => $contractData['title'],
                    'value' => $contractData['value'],
                    'start_date' => $contractData['start_date'],
                    'end_date' => $contractData['end_date'],
                    'client_id' => $clients->random()->id,
                    'project_id' => $contractData['project_id'],
                    'workspace_id' => 1,
                    'created_by' => $createdByUser->id,

                    'contract_type_id' => $contractTypes->random()->id,
                    'description' => $contractData['description'],
                    
                    // Assign workflow roles with proper permissions
                    'site_supervisor_id' => $siteSupervisor->id,
                    'quantity_approver_id' => $quantityApprover->id,
                    'accountant_id' => $accountant->id,
                    'reviewer_id' => $reviewer->id,
                    'final_approver_id' => $finalApprover->id,
                    
                    // Workflow status
                    'workflow_status' => $contractData['workflow_status'],
                    'quantity_approval_status' => $contractData['quantity_approval_status'],
                    'management_review_status' => $contractData['management_review_status'],
                    'accounting_review_status' => $contractData['accounting_review_status'],
                    'final_approval_status' => $contractData['final_approval_status'],
                    
                    // Archive data if needed
                    'is_archived' => isset($contractData['is_archived']) ? $contractData['is_archived'] : false,
                    'archived_at' => isset($contractData['archived_at']) ? $contractData['archived_at'] : null,
                    'archived_by' => isset($contractData['is_archived']) && $contractData['is_archived'] ? $users->random()->id : null,
                    
                    // Financial integration
                    'financial_status' => $index % 3 == 0 ? 'invoiced' : ($index % 3 == 1 ? 'completed' : 'pending'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        $finalCount = Contract::where('workspace_id', 1)->count();
        $this->command->info('Ensured ' . $finalCount . ' contracts exist in workspace 1.');
    }
}