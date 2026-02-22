<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Contract;
use App\Models\ContractQuantity;
use App\Models\ContractApproval;
use App\Models\JournalEntry;
use App\Models\ContractAmendment;
use App\Models\ContractType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ArabicYemeniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'first_name' => 'مدير',
            'last_name' => 'النظام',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'default_workspace_id' => 1,
        ]);

        // Create site supervisor
        $supervisor = User::create([
            'first_name' => 'مشرف',
            'last_name' => 'الموقع',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('password'),
            'default_workspace_id' => 1,
        ]);

        // Create quantity approver
        $approver = User::create([
            'first_name' => 'معتمد',
            'last_name' => 'الكميات',
            'email' => 'approver@example.com',
            'password' => Hash::make('password'),
            'default_workspace_id' => 1,
        ]);

        // Create accountant
        $accountant = User::create([
            'first_name' => 'المحاسب',
            'last_name' => '',
            'email' => 'accountant@example.com',
            'password' => Hash::make('password'),
            'default_workspace_id' => 1,
        ]);

        // Create reviewer
        $reviewer = User::create([
            'first_name' => 'المراجع',
            'last_name' => '',
            'email' => 'reviewer@example.com',
            'password' => Hash::make('password'),
            'default_workspace_id' => 1,
        ]);

        // Create final approver
        $finalApprover = User::create([
            'first_name' => 'المعتمد',
            'last_name' => 'النهائي',
            'email' => 'final@approver.com',
            'password' => Hash::make('password'),
            'default_workspace_id' => 1,
        ]);

        // Create sample client (Ministry of Public Works)
        $client = Client::create([
            'first_name' => 'وزارة',
            'last_name' => 'الأشغال',
            'company' => 'وزارة الأشغال العامة والطرق',
            'email' => 'info@mopw.gov.ye',
            'phone' => '+967123456789',
            'address' => 'صنعاء، الجمهورية اليمنية',
            'default_workspace_id' => 1,
        ]);

        // Create sample contractor
        $contractor = Client::create([
            'first_name' => 'شركة',
            'last_name' => 'البناء',
            'company' => 'شركة البناء الوطنية',
            'email' => 'contact@national-construction.co.ye',
            'phone' => '+967987654321',
            'address' => 'تعز، الجمهورية اليمنية',
            'default_workspace_id' => 1,
        ]);

        // Create sample project
        $project = Project::create([
            'title' => 'مشروع توسعة شارع صنعاء - تعز',
            'description' => 'مشروع توسعة وتطوير الطريق الرئيسي بين محافظتي صنعاء وتعز',
            'workspace_id' => 1,
            'created_by' => $admin->id,
        ]);

        // Create contract type
        $contractType = ContractType::create([
            'type' => 'contruction_contract',
            'workspace_id' => 1,
        ]);

        // Create contract
        $contract = Contract::create([
            'title' => 'عقد توسعة طريق صنعاء - تعز',
            'value' => 50000000.00, // 50 مليون ريال يمني
            'start_date' => now()->subDays(30),
            'end_date' => now()->addDays(365),
            'client_id' => $contractor->id,
            'project_id' => $project->id,
            'workspace_id' => 1,
            'created_by' => $admin->id,
            'contract_type_id' => $contractType->id,
            'description' => 'عقد تنفيذ مشروع توسعة وتطوير الطريق الرئيسي بين محافظتي صنعاء وتعز',
            // Assign workflow roles
            'site_supervisor_id' => $supervisor->id,
            'quantity_approver_id' => $approver->id,
            'accountant_id' => $accountant->id,
            'reviewer_id' => $reviewer->id,
            'final_approver_id' => $finalApprover->id,
            // Set initial workflow status
            'workflow_status' => 'site_supervisor_upload',
            'quantity_approval_status' => 'pending',
            'management_review_status' => 'pending',
            'accounting_review_status' => 'pending',
            'final_approval_status' => 'pending',
        ]);

        // Create sample contract quantities (uploaded by site supervisor)
        $quantity1 = ContractQuantity::create([
            'contract_id' => $contract->id,
            'user_id' => $supervisor->id,
            'item_description' => 'ردميات ترابية',
            'requested_quantity' => 1000.00,
            'unit' => 'متر مكعب',
            'unit_price' => 50.00,
            'total_amount' => 50000.00,
            'status' => 'pending',
            'notes' => 'الكمية تم قياسها في الموقع وفق المواصفات الفنية',
        ]);

        $quantity2 = ContractQuantity::create([
            'contract_id' => $contract->id,
            'user_id' => $supervisor->id,
            'item_description' => 'خرسانة خفيفة',
            'requested_quantity' => 500.00,
            'unit' => 'متر مكعب',
            'unit_price' => 120.00,
            'total_amount' => 60000.00,
            'status' => 'pending',
            'notes' => 'الكمية مطلوبة لصب الأعمدة الخرسانية',
        ]);

        $quantity3 = ContractQuantity::create([
            'contract_id' => $contract->id,
            'user_id' => $supervisor->id,
            'item_description' => 'حديد تسليح',
            'requested_quantity' => 25.00,
            'unit' => 'طن',
            'unit_price' => 25000.00,
            'total_amount' => 625000.00,
            'status' => 'pending',
            'notes' => 'الكمية مطلوبة لأعمال التسليح',
        ]);

        // Create contract approval record
        $approval = ContractApproval::create([
            'contract_id' => $contract->id,
            'approver_id' => $approver->id,
            'approval_stage' => 'quantity_approval',
            'status' => 'pending',
            'comments' => 'في انتظار المراجعة والموافقة على الكميات المرفوعة',
            'approved_rejected_at' => now(),
        ]);

        // Create journal entry for accounting integration
        $journalEntry = JournalEntry::create([
            'contract_id' => $contract->id,
            'entry_number' => 'JE-2026-001',
            'entry_type' => 'project_revenue',
            'account_code' => 'REV-001',
            'account_name' => 'الذمم المدينة',
            'reference_number' => 'الأيرادات المؤجلة',
            'credit_amount' => 735000.00,
            'debit_amount' => 0.00,
            'description' => 'قيد أولي لمستخلص مشروع توسعة طريق صنعاء - تعز',
            'entry_date' => now(),
            'status' => 'pending',
            'created_by' => $admin->id,
        ]);

        // Create another contract for demonstration
        $contract2 = Contract::create([
            'title' => 'عقد صيانة جسور محافظة حضرموت',
            'value' => 75000000.00,
            'start_date' => now()->subDays(15),
            'end_date' => now()->addDays(180),
            'client_id' => $contractor->id,
            'project_id' => $project->id,
            'workspace_id' => 1,
            'created_by' => $admin->id,
            'contract_type_id' => $contractType->id,
            'description' => 'عقد صيانة وترميم الجسور في محافظة حضرموت',
            // Assign workflow roles
            'site_supervisor_id' => $supervisor->id,
            'quantity_approver_id' => $approver->id,
            'accountant_id' => $accountant->id,
            'reviewer_id' => $reviewer->id,
            'final_approver_id' => $finalApprover->id,
            // Set workflow status to approved for demonstration
            'workflow_status' => 'approved',
            'quantity_approval_status' => 'approved',
            'management_review_status' => 'approved',
            'accounting_review_status' => 'approved',
            'final_approval_status' => 'approved',
        ]);

        // Create archived contract
        $archivedContract = Contract::create([
            'title' => 'عقد صيانة شبكة الكهرباء - عدن',
            'value' => 35000000.00,
            'start_date' => now()->subDays(400),
            'end_date' => now()->subDays(30),
            'client_id' => $contractor->id,
            'project_id' => $project->id,
            'workspace_id' => 1,
            'created_by' => $admin->id,
            'contract_type_id' => $contractType->id,
            'description' => 'مشروع صيانة وتطوير شبكة الكهرباء في مدينة عدن',
            // Assign workflow roles
            'site_supervisor_id' => $supervisor->id,
            'quantity_approver_id' => $approver->id,
            'accountant_id' => $accountant->id,
            'reviewer_id' => $reviewer->id,
            'final_approver_id' => $finalApprover->id,
            // Mark as archived
            'workflow_status' => 'archived',
            'quantity_approval_status' => 'approved',
            'management_review_status' => 'approved',
            'accounting_review_status' => 'approved',
            'final_approval_status' => 'approved',
            'is_archived' => true,
            'archived_at' => now()->subDays(10),
            'archived_by' => $admin->id,
        ]);

        // Create amendment request for the first contract
        $amendment = ContractAmendment::create([
            'contract_id' => $contract->id,
            'requested_by_user_id' => $admin->id,
            'amendment_type' => 'price',
            'request_reason' => 'تعديل السعر بسبب ارتفاع تكلفة المواد',
            'details' => 'طلب تعديل سعر العقد من 50,000,000 إلى 55,000,000 ريال يمني بسبب ارتفاع تكلفة المواد الأساسية',
            'original_price' => 50000000.00,
            'new_price' => 55000000.00,
            'status' => 'pending',
        ]);

        // Output success message
        $this->command->info('Seeder بيانات العربية اليمنية تم تنفيذه بنجاح!');
    }
}