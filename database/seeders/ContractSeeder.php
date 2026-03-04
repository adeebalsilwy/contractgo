<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contract;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\ContractType;
use App\Models\Workspace;
use Illuminate\Support\Facades\DB;

class ContractSeeder extends Seeder
{
    public function run()
    {
        // Ensure related data exists
        $this->call(ContractRelatedSeeder::class);
        
        // Get IDs of existing records
        $users = User::all();
        $clients = Client::all();
        $projects = Project::all();
        $contractTypes = ContractType::all();
        // Use workspace ID 1 only
        $workspace = Workspace::find(1);
        if (!$workspace) {
            $this->command->error('Workspace ID 1 not found!');
            return;
        }
        
        if ($users->isEmpty() || $clients->isEmpty() || $projects->isEmpty() || $contractTypes->isEmpty()) {
            $this->command->error('Missing required data. Please ensure users, clients, projects, contract types, and workspaces exist.');
            return;
        }
        
        // Sample contracts data in Arabic Yemeni style
        $contracts = [
            [
                'workspace_id' => $workspace->id,
                'title' => 'عقد بناء فندق السلام',
                'value' => 5000000,
                'start_date' => '2024-01-15',
                'end_date' => '2025-06-30',
                'client_id' => $clients->random()->id,
                'project_id' => $projects->random()->id,
                'contract_type_id' => $contractTypes->random()->id,
                'description' => 'عقد بناء فندق فخم من 5 طوابق يتضمن جميع الأعمال الإنشائية والتشطيبات الداخلية والخارجية',
                'created_by' => 'u_' . $users->random()->id,
                'site_supervisor_id' => $users->random()->id,
                'quantity_approver_id' => $users->random()->id,
                'accountant_id' => $users->random()->id,
                'reviewer_id' => $users->random()->id,
                'final_approver_id' => $users->random()->id,
                'journal_entry_number' => 'JE-' . rand(1000, 9999),
                'journal_entry_date' => now(),
                'amendment_requested' => false,
                'is_archived' => false,
                'workflow_status' => 'approved', // Changed from 'completed' to 'approved'
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'approved',
                'final_approval_status' => 'approved',
                'financial_status' => 'active',
                'invoice_reference' => null
            ],
            [
                'workspace_id' => $workspace->id,
                'title' => 'عقد تطوير مجمع تجاري الحكمة',
                'value' => 8000000,
                'start_date' => '2024-02-20',
                'end_date' => '2025-08-15',
                'client_id' => $clients->random()->id,
                'project_id' => $projects->random()->id,
                'contract_type_id' => $contractTypes->random()->id,
                'description' => 'تطوير مجمع تجاري متكامل يضم 50 محل تجاري و 10 مكاتب إدارية',
                'created_by' => 'u_' . $users->random()->id,
                'site_supervisor_id' => $users->random()->id,
                'quantity_approver_id' => $users->random()->id,
                'accountant_id' => $users->random()->id,
                'reviewer_id' => $users->random()->id,
                'final_approver_id' => $users->random()->id,
                'journal_entry_number' => 'JE-' . rand(1000, 9999),
                'journal_entry_date' => now(),
                'amendment_requested' => false,
                'is_archived' => false,
                'workflow_status' => 'management_review', // Changed to valid status
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'pending',
                'accounting_review_status' => 'pending',
                'final_approval_status' => 'pending',
                'financial_status' => 'active',
                'invoice_reference' => null
            ],
            [
                'workspace_id' => $workspace->id,
                'title' => 'عقد بناء مدرسة الأمل الابتدائية',
                'value' => 2000000,
                'start_date' => '2024-03-10',
                'end_date' => '2024-12-20',
                'client_id' => $clients->random()->id,
                'project_id' => $projects->random()->id,
                'contract_type_id' => $contractTypes->random()->id,
                'description' => 'بناء مدرسة ابتدائية تتكون من 12 فصلاً دراسياً ومرافق تعليمية متكاملة',
                'created_by' => 'u_' . $users->random()->id,
                'site_supervisor_id' => $users->random()->id,
                'quantity_approver_id' => $users->random()->id,
                'accountant_id' => $users->random()->id,
                'reviewer_id' => $users->random()->id,
                'final_approver_id' => $users->random()->id,
                'journal_entry_number' => 'JE-' . rand(1000, 9999),
                'journal_entry_date' => now(),
                'amendment_requested' => false,
                'is_archived' => false,
                'workflow_status' => 'approved', // Changed from 'completed' to 'approved'
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'approved',
                'final_approval_status' => 'approved',
                'financial_status' => 'completed',
                'invoice_reference' => null
            ],
            [
                'workspace_id' => $workspace->id,
                'title' => 'عقد صيانة وتشغيل المباني الحكومية',
                'value' => 1500000,
                'start_date' => '2024-01-01',
                'end_date' => '2025-12-31',
                'client_id' => $clients->random()->id,
                'project_id' => $projects->random()->id,
                'contract_type_id' => $contractTypes->random()->id,
                'description' => 'صيانة وتشغيل المباني الحكومية في محافظة صنعاء',
                'created_by' => 'u_' . $users->random()->id,
                'site_supervisor_id' => $users->random()->id,
                'quantity_approver_id' => $users->random()->id,
                'accountant_id' => $users->random()->id,
                'reviewer_id' => $users->random()->id,
                'final_approver_id' => $users->random()->id,
                'journal_entry_number' => 'JE-' . rand(1000, 9999),
                'journal_entry_date' => now(),
                'amendment_requested' => true,
                'amendment_reason' => 'تعديل في مدة العقد',
                'amendment_requested_at' => now()->subDays(15),
                'amendment_requested_by' => $users->random()->id,
                'amendment_approved' => true,
                'amendment_approved_at' => now()->subDays(7),
                'amendment_approved_by' => $users->random()->id,
                'is_archived' => false,
                'workflow_status' => 'amendment_approved', // Valid status
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'approved',
                'final_approval_status' => 'approved',
                'financial_status' => 'active',
                'invoice_reference' => null
            ],
            [
                'workspace_id' => $workspace->id,
                'title' => 'عقد توريد معدات البناء',
                'value' => 3500000,
                'start_date' => '2024-04-05',
                'end_date' => '2024-09-30',
                'client_id' => $clients->random()->id,
                'project_id' => $projects->random()->id,
                'contract_type_id' => $contractTypes->random()->id,
                'description' => 'توريد معدات ثقيلة ومعدات بناء متنوعة للمشاريع الحكومية',
                'created_by' => 'u_' . $users->random()->id,
                'site_supervisor_id' => $users->random()->id,
                'quantity_approver_id' => $users->random()->id,
                'accountant_id' => $users->random()->id,
                'reviewer_id' => $users->random()->id,
                'final_approver_id' => $users->random()->id,
                'journal_entry_number' => 'JE-' . rand(1000, 9999),
                'journal_entry_date' => now(),
                'amendment_requested' => false,
                'is_archived' => false,
                'workflow_status' => 'quantity_approval', // Changed to valid status
                'quantity_approval_status' => 'pending',
                'management_review_status' => 'pending',
                'accounting_review_status' => 'pending',
                'final_approval_status' => 'pending',
                'financial_status' => 'pending',
                'invoice_reference' => null
            ]
        ];
        
        foreach ($contracts as $contractData) {
            Contract::firstOrCreate(
                ['title' => $contractData['title']],
                $contractData
            );
        }
    }
}