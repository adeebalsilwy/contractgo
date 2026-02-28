<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContractAmendment;
use App\Models\Contract;
use App\Models\User;

class ContractAmendmentSeeder extends Seeder
{
    public function run()
    {
        // Ensure related data exists
        $this->call(ContractApprovalSeeder::class);
        
        $contracts = Contract::all();
        $users = User::all();
        
        if ($contracts->isEmpty() || $users->isEmpty()) {
            $this->command->error('Missing required data. Please ensure contracts and users exist.');
            return;
        }
        
        // Sample contract amendments data in Arabic Yemenي style
        $amendments = [];
        
        foreach ($contracts as $contract) {
            // Create 0-2 amendments per contract
            $amendmentCount = rand(0, 2);
            
            for ($i = 0; $i < $amendmentCount; $i++) {
                $amendments[] = [
                    'contract_id' => $contract->id,
                    'requested_by_user_id' => $users->random()->id,
                    'approved_by_user_id' => $users->random()->id,
                    'amendment_type' => ['price', 'scope', 'time', 'specification'][rand(0, 3)],
                    'request_reason' => [
                        'تعديل في مواصفات المواد',
                        'زيادة في مدة المشروع',
                        'تغيير في نطاق العمل',
                        'تحديث في أسعار المواد',
                        'تعديل في التصميم الهندسي'
                    ][rand(0, 4)],
                    'details' => [
                        ' amendment details in Arabic Yemenي',
                        'تفاصيل تعديل العقد',
                        'ملاحظات حول التعديل',
                        'المستندات الداعمة للتعديل',
                        'الإجراءات المطلوبة'
                    ][rand(0, 4)],
                    'original_price' => $contract->value,
                    'new_price' => $contract->value * (1 + (rand(-10, 20) / 100)), // Price change -10% to +20%
                    'original_quantity' => rand(100, 1000) / 10,
                    'new_quantity' => (rand(100, 1000) / 10) * (1 + (rand(-5, 10) / 100)), // Quantity change -5% to +10%
                    'original_unit' => ['متر مكعب', 'متر مربع', 'قطعة', 'طن', 'وحدة'][rand(0, 4)],
                    'new_unit' => ['متر مكعب', 'متر مربع', 'قطعة', 'طن', 'وحدة'][rand(0, 4)],
                    'original_description' => $contract->description,
                    'new_description' => [
                        'تفاصيل العقد المحدثة',
                        'نطاق العمل المعدل',
                        'البنود المحدثة',
                        'المواصفات المعدلة',
                        'الشروط الجديدة'
                    ][rand(0, 4)],
                    'status' => ['pending', 'approved', 'rejected'][rand(0, 2)],
                    'approval_comments' => [
                        'التعديل مقبول وفق الشروط المتفق عليها',
                        'الموافقة مشروطة ببعض الشروط',
                        'التعديل مرفوض لعدم توافقه مع الشروط',
                        'في انتظار الموافقات النهائية',
                        'التعديل يحتاج لمراجعة قانونية'
                    ][rand(0, 4)],
                    'approved_at' => rand(0, 1) ? now()->subDays(rand(0, 15)) : null,
                    'rejected_at' => rand(0, 1) && rand(0, 2) == 0 ? now()->subDays(rand(0, 10)) : null,
                    'digital_signature_path' => rand(0, 1) ? 'signatures/amendment_' . rand(1000, 9999) . '.png' : null,
                    'signed_at' => rand(0, 1) ? now()->subDays(rand(0, 5)) : null,
                    'signed_by_user_id' => rand(0, 1) ? $users->random()->id : null
                ];
            }
        }
        
        foreach ($amendments as $amendmentData) {
            ContractAmendment::firstOrCreate(
                [
                    'contract_id' => $amendmentData['contract_id'],
                    'request_reason' => $amendmentData['request_reason']
                ],
                $amendmentData
            );
        }
    }
}