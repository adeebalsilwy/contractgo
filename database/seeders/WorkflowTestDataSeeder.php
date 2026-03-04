<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\ContractQuantity;
use App\Models\ContractApproval;
use App\Models\ContractAmendment;
use App\Models\JournalEntry;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;

class WorkflowTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🧪 Starting Workflow Test Data Seeder...');
        
        // Get existing workspace (should be only 1)
        $workspace = \App\Models\Workspace::first();
        if (!$workspace) {
            $this->command->error('❌ No workspace found. Please run CompleteWorkflowSeeder first.');
            return;
        }
        
        // Get existing users
        $users = \App\Models\User::where('workspace_id', $workspace->id)->get();
        if ($users->isEmpty()) {
            $this->command->error('❌ No users found. Please run CompleteWorkflowSeeder first.');
            return;
        }
        
        // Get EXISTING contracts (should be exactly 10)
        $contracts = Contract::where('workspace_id', $workspace->id)->get();
        if ($contracts->isEmpty()) {
            $this->command->error('❌ No contracts found. Please run CompleteWorkflowSeeder first.');
            return;
        }
        
        $this->command->info('Found ' . $contracts->count() . ' existing contracts');
        
        // Get EXISTING projects (should be exactly 5)
        $projects = \App\Models\Project::where('workspace_id', $workspace->id)->get();
        $this->command->info('Found ' . $projects->count() . ' existing projects');
        
        $items = \App\Models\Item::where('workspace_id', $workspace->id)->get();
        
        // Create test data for each workflow stage using EXISTING data only
        $this->createQuantities($contracts, $users, $items, $workspace);
        $this->createApprovals($contracts, $users);
        $this->createAmendments($contracts, $users);
        $this->createJournalEntries($contracts, $users, $workspace);
        $this->createActivityLogs($contracts, $users);
        
        $this->command->info('✅ Workflow Test Data Seeder completed!');
        $this->command->info('✓ Total contracts: ' . $contracts->count());
        $this->command->info('✓ Total projects: ' . $projects->count());
    }
    
    /**
     * Create contract quantities for testing on ALL 10 contracts
     */
    private function createQuantities($contracts, $users, $items, $workspace)
    {
        $this->command->info('Creating contract quantities for all ' . $contracts->count() . ' contracts...');
        
        $quantitiesData = [];
        
        foreach ($contracts as $contract) {
            // Check if quantities already exist for this contract
            $existingCount = ContractQuantity::where('contract_id', $contract->id)->count();
            if ($existingCount > 0) {
                $this->command->warn('⚠ Skipping contract #' . $contract->id . ' - already has ' . $existingCount . ' quantities');
                continue;
            }
            
            // Create 2-3 quantities for each contract
            $itemsToUse = $items->take(min(3, $items->count()));
            foreach ($itemsToUse as $item) {
                $quantity = ContractQuantity::create([
                    'contract_id' => $contract->id,
                    'user_id' => $contract->site_supervisor_id ?? $users[1]->id,
                    'workspace_id' => $workspace->id,
                    'item_id' => $item->id,
                    'item_description' => $item->title,
                    'requested_quantity' => rand(10, 100),
                    'approved_quantity' => null,
                    'unit' => $item->unit->title ?? 'Unit',
                    'unit_price' => $item->price,
                    'total_amount' => rand(10, 100) * $item->price,
                    'notes' => 'كميات منفذة للمراجعة',
                    'supporting_documents' => [],
                    'status' => ['pending', 'approved', 'rejected'][array_rand(['pending', 'approved', 'rejected'])],
                    'submitted_at' => now()->subDays(rand(1, 30)),
                ]);
                
                $quantitiesData[] = $quantity;
            }
        }
        
        $this->command->info('✓ Created ' . count($quantitiesData) . ' new contract quantities');
    }
    
    /**
     * Create contract approvals for testing on ALL contracts
     */
    private function createApprovals($contracts, $users)
    {
        $this->command->info('Creating contract approvals for all contracts...');
        
        $approvalsData = [];
        $approvalStages = ['quantity_approval', 'management_approval', 'final_approval'];
        
        foreach ($contracts as $contract) {
            foreach ($approvalStages as $stage) {
                // Check if approval already exists
                $existingApproval = ContractApproval::where('contract_id', $contract->id)
                    ->where('approval_stage', $stage)
                    ->first();
                
                if ($existingApproval) {
                    $this->command->warn('⚠ Skipping ' . $stage . ' for contract #' . $contract->id . ' - already exists');
                    continue;
                }
                
                $approverId = $contract->{$stage . '_id'} ?? $users[array_rand($users)]->id;
                
                $approval = ContractApproval::create([
                    'contract_id' => $contract->id,
                    'approval_stage' => $stage,
                    'approver_id' => $approverId,
                    'status' => ['pending', 'approved', 'rejected'][array_rand(['pending', 'approved', 'rejected'])],
                    'comments' => 'تمت المراجعة والاعتماد',
                    'approved_rejected_at' => now()->subDays(rand(1, 20)),
                    'approval_signature' => null, // Will be added via UI
                    'rejection_reason' => null,
                ]);
                
                $approvalsData[] = $approval;
            }
        }
        
        $this->command->info('✓ Created ' . count($approvalsData) . ' new contract approvals');
    }
    
    /**
     * Create contract amendments for testing
     */
    private function createAmendments($contracts, $users)
    {
        $this->command->info('Creating contract amendments...');
        
        $amendmentsData = [];
        
        // Create amendments for some contracts
        foreach ($contracts->take(2) as $contract) {
            $amendment = ContractAmendment::create([
                'contract_id' => $contract->id,
                'requested_by_user_id' => $contract->site_supervisor_id ?? $users[1]->id,
                'approved_by_user_id' => null,
                'amendment_type' => ['price', 'quantity', 'description'][array_rand(['price', 'quantity', 'description'])],
                'request_reason' => 'تعديل ضروري بسبب تغييرات في الموقع',
                'details' => [
                    'original_value' => rand(1000, 5000),
                    'new_value' => rand(5000, 10000),
                ],
                'original_price' => rand(1000, 5000),
                'new_price' => rand(5000, 10000),
                'original_quantity' => rand(10, 50),
                'new_quantity' => rand(50, 100),
                'original_unit' => 'Meter',
                'new_unit' => 'Meter',
                'original_description' => 'الوصف الأصلي',
                'new_description' => 'الوصف المعدل',
                'status' => ['pending', 'approved', 'rejected'][array_rand(['pending', 'approved', 'rejected'])],
                'approval_comments' => $contract->workflow_status === 'approved' ? 'تمت الموافقة على التعديل' : null,
                'approved_at' => $contract->workflow_status === 'approved' ? now()->subDays(5) : null,
                'rejected_at' => null,
                'digital_signature_path' => null,
                'signed_at' => null,
                'signed_by_user_id' => null,
            ]);
            
            $amendmentsData[] = $amendment;
        }
        
        $this->command->info('✓ Created ' . count($amendmentsData) . ' contract amendments');
    }
    
    /**
     * Create journal entries for testing
     */
    private function createJournalEntries($contracts, $users, $workspace)
    {
        $this->command->info('Creating journal entries...');
        
        $entriesData = [];
        
        // Create journal entries for approved contracts
        foreach ($contracts->where('workflow_status', 'approved') as $contract) {
            $entry = JournalEntry::create([
                'contract_id' => $contract->id,
                'invoice_id' => null,
                'entry_number' => 'JE-' . date('Ymd') . '-' . rand(1000, 9999),
                'entry_type' => 'journal',
                'entry_date' => now()->subDays(rand(1, 15)),
                'reference_number' => 'REF-' . rand(10000, 99999),
                'description' => 'قيد محاسبي للعقد: ' . $contract->title,
                'debit_amount' => $contract->value * 0.5,
                'credit_amount' => $contract->value * 0.5,
                'account_code' => '4000',
                'account_name' => 'إيرادات المبيعات',
                'created_by' => $contract->accountant_id ?? $users[3]->id,
                'status' => ['pending', 'posted'][array_rand(['pending', 'posted'])],
                'posted_at' => null,
                'posted_by' => null,
                'posting_notes' => null,
                'integration_data' => ['onyx_pro_synced' => false],
                'workspace_id' => $workspace->id,
            ]);
            
            $entriesData[] = $entry;
        }
        
        $this->command->info('✓ Created ' . count($entriesData) . ' journal entries');
    }
    
    /**
     * Create activity logs for audit trail testing
     */
    private function createActivityLogs($contracts, $users)
    {
        $this->command->info('Creating activity logs...');
        
        $logsData = [];
        $actions = [
            'contract_created',
            'quantity_submitted',
            'quantity_approved',
            'workflow_stage_transition',
            'electronic_signature_applied',
            'amendment_requested',
            'journal_entry_created',
        ];
        
        foreach ($contracts as $contract) {
            // Create multiple activity logs for each contract
            for ($i = 0; $i < 5; $i++) {
                $log = ActivityLog::create([
                    'user_id' => $users[array_rand($users)]->id,
                    'action' => $actions[array_rand($actions)],
                    'entity_type' => 'contract',
                    'entity_id' => $contract->id,
                    'description' => 'نشاط تجريبي للعقد: ' . $contract->title,
                    'metadata' => [
                        'test_data' => true,
                        'timestamp' => now()->subDays(rand(1, 30))->toIso8601String(),
                        'contract_title' => $contract->title,
                    ],
                ]);
                
                $logsData[] = $log;
            }
        }
        
        $this->command->info('✓ Created ' . count($logsData) . ' activity logs');
    }
}
