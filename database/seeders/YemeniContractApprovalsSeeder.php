<?php

namespace Database\Seeders;

use App\Models\ContractApproval;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Seeder;

class YemeniContractApprovalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get related models
        $contracts = Contract::all();
        $users = User::all();

        // Define diverse Yemeni contract approvals data
        $approvals = [
            [
                'approval_stage' => 'quantity_approval',
                'status' => 'approved',
                'comments' => 'الكميات مطابقة للمواصفات الفنية وال contruction drawings',
                'approved_rejected_at' => now()->subDays(5),
            ],
            [
                'approval_stage' => 'management_review',
                'status' => 'approved',
                'comments' => 'الكميات والأسعار مقبولة حسب المواصفات',
                'approved_rejected_at' => now()->subDays(3),
            ],
            [
                'approval_stage' => 'accounting_review',
                'status' => 'approved',
                'comments' => 'القيود المحاسبية تم التحقق منها',
                'approved_rejected_at' => now()->subDays(2),
            ],
            [
                'approval_stage' => 'final_approval',
                'status' => 'approved',
                'comments' => 'الموافقة النهائية على المستخلص',
                'approved_rejected_at' => now()->subDays(1),
            ],
            [
                'approval_stage' => 'quantity_approval',
                'status' => 'pending',
                'comments' => 'في انتظار المراجعة والموافقة على الكميات',
                'approved_rejected_at' => now(),
            ],
            [
                'approval_stage' => 'management_review',
                'status' => 'pending',
                'comments' => 'تحت المراجعة الإدارية',
                'approved_rejected_at' => now(),
            ],
            [
                'approval_stage' => 'accounting_review',
                'status' => 'rejected',
                'comments' => 'هناك مخالفات في القيود المحاسبية',
                'rejection_reason' => 'مبلغ غير مطابق لل contruction drawings',
                'approved_rejected_at' => now()->subDays(4),
            ],
            [
                'approval_stage' => 'final_approval',
                'status' => 'pending',
                'comments' => 'في انتظار الموافقة النهائية',
                'approved_rejected_at' => now(),
            ],
            [
                'approval_stage' => 'quantity_approval',
                'status' => 'approved',
                'comments' => 'الكميات مقبولة وفق المعايير الفنية',
                'approved_rejected_at' => now()->subDays(7),
            ],
            [
                'approval_stage' => 'management_review',
                'status' => 'approved',
                'comments' => 'الكميات مقبولة حسب تقرير المفتش',
                'approved_rejected_at' => now()->subDays(6),
            ],
        ];

        foreach ($approvals as $approvalData) {
            ContractApproval::create([
                'contract_id' => $contracts->random()->id,
                'approver_id' => $users->random()->id,
                'approval_stage' => $approvalData['approval_stage'],
                'status' => $approvalData['status'],
                'comments' => $approvalData['comments'],
                'approved_rejected_at' => $approvalData['approved_rejected_at'],
                'rejection_reason' => $approvalData['rejection_reason'] ?? null,
            ]);
        }
    }
}