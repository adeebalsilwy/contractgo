<?php

namespace Database\Seeders;

use App\Models\ContractAmendment;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Seeder;

class YemeniContractAmendmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get related models
        $contracts = Contract::all();
        $users = User::all();

        // Define diverse Yemeni contract amendments data
        $amendments = [
            [
                'amendment_type' => 'price',
                'request_reason' => 'تعديل السعر بسبب ارتفاع تكلفة المواد',
                'details' => 'طلب تعديل سعر العقد من 50,000,000 إلى 55,000,000 ريال يمني بسبب ارتفاع تكلفة المواد الأساسية',
                'original_price' => 50000000.00,
                'new_price' => 55000000.00,
                'status' => 'approved',
            ],
            [
                'amendment_type' => 'quantity',
                'request_reason' => 'تعديل الكمية بسبب تغييرات في التصميم',
                'details' => 'طلب تعديل الكمية من 1000 متر مكعب إلى 1200 متر مكعب بسبب تغييرات في التصميم الهندسي',
                'original_quantity' => 1000.00,
                'new_quantity' => 1200.00,
                'status' => 'approved',
            ],
            [
                'amendment_type' => 'specification',
                'request_reason' => 'تعديل المواصفات الفنية',
                'details' => 'تعديل المواصفات الفنية لبند الحديد التسليحي من نوع A إلى نوع B حسب المعايير الجديدة',
                'original_description' => 'حديد تسليح نوع A',
                'new_description' => 'حديد تسليح نوع B',
                'status' => 'pending',
            ],
            [
                'amendment_type' => 'price',
                'request_reason' => 'تعديل السعر بسبب تغير أسعار النفط',
                'details' => 'طلب تعديل سعر العقد من 75,000,000 إلى 80,000,000 ريال يمني بسبب ارتفاع تكلفة التشغيل',
                'original_price' => 75000000.00,
                'new_price' => 80000000.00,
                'status' => 'rejected',
            ],
            [
                'amendment_type' => 'quantity',
                'request_reason' => 'تعديل الكمية بسبب تغييرات في المخططات',
                'details' => 'تعديل الكمية من 500 متر مكعب إلى 550 متر مكعب حسب المخططات المحدثة',
                'original_quantity' => 500.00,
                'new_quantity' => 550.00,
                'status' => 'approved',
            ],
            [
                'amendment_type' => 'specification',
                'request_reason' => 'تحديث المواصفات حسب المعايير الجديدة',
                'details' => 'تحديث مواصفات البلاط من النوع العادي إلى النوع المقاوم للماء',
                'original_description' => 'بلاط أرضيات عادي',
                'new_description' => 'بلاط أرضيات مقاوم للماء',
                'status' => 'pending',
            ],
            [
                'amendment_type' => 'price',
                'request_reason' => 'تعديل السعر بسبب تغيرات السوق',
                'details' => 'تعديل السعر من 35,000,000 إلى 38,000,000 ريال يمني حسب تغيرات السوق',
                'original_price' => 35000000.00,
                'new_price' => 38000000.00,
                'status' => 'approved',
            ],
            [
                'amendment_type' => 'quantity',
                'request_reason' => 'تعديل الكمية بسبب متطلبات جديدة',
                'details' => 'زيادة الكمية من 150 طن إلى 175 طن لتلبية المتطلبات الجديدة',
                'original_quantity' => 150.00,
                'new_quantity' => 175.00,
                'status' => 'pending',
            ],
            [
                'amendment_type' => 'specification',
                'request_reason' => 'تحديث المواصفات الفنية',
                'details' => 'تحديث مواصفات الأسلاك من النوع القياسي إلى النوع المقاوم للحرارة',
                'original_description' => 'أسلاك كهرباء قياسية',
                'new_description' => 'أسلاك كهرباء مقاومة للحرارة',
                'status' => 'approved',
            ],
            [
                'amendment_type' => 'price',
                'request_reason' => 'تعديل السعر بسبب تغير التكاليف',
                'details' => 'تعديل السعر من 120,000,000 إلى 125,000,000 ريال يمني حسب التكاليف الجديدة',
                'original_price' => 120000000.00,
                'new_price' => 125000000.00,
                'status' => 'approved',
            ],
        ];

        foreach ($amendments as $amendmentData) {
            ContractAmendment::create([
                'contract_id' => $contracts->random()->id,
                'requested_by_user_id' => $users->random()->id,
                'amendment_type' => $amendmentData['amendment_type'],
                'request_reason' => $amendmentData['request_reason'],
                'details' => $amendmentData['details'],
                'original_price' => $amendmentData['original_price'] ?? null,
                'new_price' => $amendmentData['new_price'] ?? null,
                'original_quantity' => $amendmentData['original_quantity'] ?? null,
                'new_quantity' => $amendmentData['new_quantity'] ?? null,
                'original_description' => $amendmentData['original_description'] ?? null,
                'new_description' => $amendmentData['new_description'] ?? null,
                'status' => $amendmentData['status'],
                'approved_at' => $amendmentData['status'] === 'approved' ? now()->subDays(rand(1, 10)) : null,
                'rejected_at' => $amendmentData['status'] === 'rejected' ? now()->subDays(rand(1, 5)) : null,
            ]);
        }
    }
}