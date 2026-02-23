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
     */
    public function run(): void
    {
        // Get related models
        $users = User::all();
        $clients = Client::all();
        $projects = Project::all();
        $contractTypes = ContractType::all();

        // Define diverse Yemeni contract data
        $contracts = [
            [
                'title' => 'عقد توسعة طريق صنعاء - تعز',
                'value' => 50000000.00,
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(365),
                'description' => 'عقد تنفيذ مشروع توسعة وتطوير الطريق الرئيسي بين محافظتي صنعاء وتعز',
                'workflow_status' => 'site_supervisor_upload',
                'quantity_approval_status' => 'pending',
                'management_review_status' => 'pending',
                'accounting_review_status' => 'pending',
                'final_approval_status' => 'pending',
            ],
            [
                'title' => 'عقد صيانة جسور محافظة حضرموت',
                'value' => 75000000.00,
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(180),
                'description' => 'عقد صيانة وترميم الجسور في محافظة حضرموت',
                'workflow_status' => 'approved',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'approved',
                'final_approval_status' => 'approved',
            ],
            [
                'title' => 'عقد صيانة شبكة الكهرباء - عدن',
                'value' => 35000000.00,
                'start_date' => now()->subDays(400),
                'end_date' => now()->subDays(30),
                'description' => 'مشروع صيانة وتطوير شبكة الكهرباء في مدينة عدن',
                'workflow_status' => 'archived',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'approved',
                'final_approval_status' => 'approved',
                'is_archived' => true,
                'archived_at' => now()->subDays(10),
            ],
            [
                'title' => 'عقد تطوير ميناء الحديدة',
                'value' => 120000000.00,
                'start_date' => now()->subDays(60),
                'end_date' => now()->addDays(540),
                'description' => 'عقد تطوير وتوسعة ميناء الحديدة',
                'workflow_status' => 'management_review',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'pending',
                'accounting_review_status' => 'pending',
                'final_approval_status' => 'pending',
            ],
            [
                'title' => 'عقد توسعة مطار صنعاء',
                'value' => 85000000.00,
                'start_date' => now()->subDays(90),
                'end_date' => now()->addDays(450),
                'description' => 'عقد توسعة وتطوير مدرجات مطار صنعاء الدولي',
                'workflow_status' => 'quantity_approval',
                'quantity_approval_status' => 'pending',
                'management_review_status' => 'pending',
                'accounting_review_status' => 'pending',
                'final_approval_status' => 'pending',
            ],
            [
                'title' => 'عقد تطوير ميناء عدن',
                'value' => 95000000.00,
                'start_date' => now()->subDays(120),
                'end_date' => now()->addDays(600),
                'description' => 'عقد تطوير وتحديث ميناء عدن',
                'workflow_status' => 'approved',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'approved',
                'final_approval_status' => 'approved',
            ],
            [
                'title' => 'عقد توسعة مستشفى الجمهورية - صنعاء',
                'value' => 45000000.00,
                'start_date' => now()->subDays(45),
                'end_date' => now()->addDays(270),
                'description' => 'عقد توسعة وتطوير مستشفى الجمهورية بصنعاء',
                'workflow_status' => 'accounting_processing',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'pending',
                'final_approval_status' => 'pending',
            ],
            [
                'title' => 'عقد تطوير مجمع تعليمي - تعز',
                'value' => 60000000.00,
                'start_date' => now()->subDays(75),
                'end_date' => now()->addDays(360),
                'description' => 'عقد بناء مجمع تعليمي متكامل في محافظة تعز',
                'workflow_status' => 'final_review',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'approved',
                'final_approval_status' => 'pending',
            ],
            [
                'title' => 'عقد تطوير مطار المكلا',
                'value' => 40000000.00,
                'start_date' => now()->subDays(100),
                'end_date' => now()->addDays(300),
                'description' => 'عقد تطوير وتحديث مطار المكلا',
                'workflow_status' => 'approved',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'approved',
                'final_approval_status' => 'approved',
            ],
            [
                'title' => 'عقد تطوير ميناء المخا',
                'value' => 30000000.00,
                'start_date' => now()->subDays(200),
                'end_date' => now()->addDays(200),
                'description' => 'عقد تطوير ميناء المخا في محافظة الحديدة',
                'workflow_status' => 'archived',
                'quantity_approval_status' => 'approved',
                'management_review_status' => 'approved',
                'accounting_review_status' => 'approved',
                'final_approval_status' => 'approved',
                'is_archived' => true,
                'archived_at' => now()->subDays(5),
            ],
        ];

        foreach ($contracts as $index => $contractData) {
            Contract::create([
                'title' => $contractData['title'],
                'value' => $contractData['value'],
                'start_date' => $contractData['start_date'],
                'end_date' => $contractData['end_date'],
                'client_id' => $clients->random()->id,
                'project_id' => $projects->random()->id,
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
                'quantity_approval_status' => $contractData['quantity_approval_status'],
                'management_review_status' => $contractData['management_review_status'],
                'accounting_review_status' => $contractData['accounting_review_status'],
                'final_approval_status' => $contractData['final_approval_status'],
                
                // Archive data if needed
                'is_archived' => isset($contractData['is_archived']) ? $contractData['is_archived'] : false,
                'archived_at' => isset($contractData['archived_at']) ? $contractData['archived_at'] : null,
                'archived_by' => isset($contractData['is_archived']) && $contractData['is_archived'] ? $users->random()->id : null,
                
                // Financial integration - for demo purposes we'll set some to have invoices
                'financial_status' => $index % 3 == 0 ? 'invoiced' : ($index % 3 == 1 ? 'completed' : 'pending'),
                'invoice_reference' => $index % 4 == 0 ? rand(1, 5) : null,
            ]);
        }
    }
}