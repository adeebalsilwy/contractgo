<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContractApproval;
use App\Models\Contract;
use App\Models\User;

class ContractApprovalSeeder extends Seeder
{
    public function run()
    {
        // Ensure related data exists
        $this->call(ContractQuantitySeeder::class);
        
        $contracts = Contract::all();
        $users = User::all();
        
        if ($contracts->isEmpty() || $users->isEmpty()) {
            $this->command->error('Missing required data. Please ensure contracts and users exist.');
            return;
        }
        
        // Sample contract approvals data in Arabic Yemenي style
        $approvals = [];
        
        foreach ($contracts as $contract) {
            // Create approvals for different stages
            $stages = ['quantity_approval', 'management_review', 'accounting_review', 'final_approval'];
            
            foreach ($stages as $stage) {
                $approvals[] = [
                    'contract_id' => $contract->id,
                    'approval_stage' => $stage,
                    'approver_id' => $users->random()->id,
                    'status' => ['approved', 'rejected', 'pending'][rand(0, 2)],
                    'comments' => [
                        'العقد مطابق للمواصفات الفنية',
                        'الوثائق مكتملة وسليمة',
                        'الموافقة مبدئية في انتظار المراجعة النهائية',
                        'الطلب يحتاج لمزيد من المعلومات',
                        'الموافقة مشروطة بتعديل بعض البنود'
                    ][rand(0, 4)],
                    'approved_rejected_at' => rand(0, 1) ? now()->subDays(rand(0, 30)) : null,
                    'approval_signature' => rand(0, 1) ? 'signature_' . rand(1000, 9999) . '.png' : null,
                    'rejection_reason' => rand(0, 1) && rand(0, 2) == 0 ? 
                        [
                            'الوثائق غير مكتملة',
                            'المواصفات لا تتطابق',
                            'الميزانية تتجاوز الحد المسموح',
                            'الموافقات السابقة مفقودة'
                        ][rand(0, 3)] : null
                ];
            }
        }
        
        foreach ($approvals as $approvalData) {
            ContractApproval::firstOrCreate(
                [
                    'contract_id' => $approvalData['contract_id'],
                    'approval_stage' => $approvalData['approval_stage']
                ],
                $approvalData
            );
        }
    }
}