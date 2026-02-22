<?php

namespace Database\Seeders;

use App\Models\ContractQuantity;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Seeder;

class YemeniContractQuantitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get related models
        $contracts = Contract::all();
        $users = User::all();

        // Define diverse Yemeni contract quantities data
        $quantities = [
            [
                'item_description' => 'ردميات ترابية',
                'requested_quantity' => 1000.00,
                'approved_quantity' => 950.00,
                'unit' => 'متر مكعب',
                'unit_price' => 50.00,
                'total_amount' => 47500.00,
                'status' => 'approved',
                'notes' => 'الكمية تم قياسها في الموقع وفق المواصفات الفنية',
            ],
            [
                'item_description' => 'خرسانة خفيفة',
                'requested_quantity' => 500.00,
                'approved_quantity' => 500.00,
                'unit' => 'متر مكعب',
                'unit_price' => 120.00,
                'total_amount' => 60000.00,
                'status' => 'approved',
                'notes' => 'الكمية مطلوبة لصب الأعمدة الخرسانية',
            ],
            [
                'item_description' => 'حديد تسليح',
                'requested_quantity' => 25.00,
                'approved_quantity' => 25.00,
                'unit' => 'طن',
                'unit_price' => 25000.00,
                'total_amount' => 625000.00,
                'status' => 'approved',
                'notes' => 'الكمية مطلوبة لأعمال التسليح',
            ],
            [
                'item_description' => 'أسفلت ساخن',
                'requested_quantity' => 150.00,
                'approved_quantity' => 145.00,
                'unit' => 'طن',
                'unit_price' => 180.00,
                'total_amount' => 26100.00,
                'status' => 'approved',
                'notes' => 'مطابق للمواصفات الفنية',
            ],
            [
                'item_description' => 'بلاط أرضيات',
                'requested_quantity' => 5000.00,
                'approved_quantity' => 5000.00,
                'unit' => 'متر مربع',
                'unit_price' => 45.00,
                'total_amount' => 225000.00,
                'status' => 'pending',
                'notes' => 'بانتظار الموافقة',
            ],
            [
                'item_description' => 'أعمدة كهرباء',
                'requested_quantity' => 50.00,
                'approved_quantity' => 48.00,
                'unit' => 'قطعة',
                'unit_price' => 1500.00,
                'total_amount' => 72000.00,
                'status' => 'approved',
                'notes' => 'تم التحقق من المواصفات',
            ],
            [
                'item_description' => 'أسلاك كهرباء',
                'requested_quantity' => 10000.00,
                'approved_quantity' => 9800.00,
                'unit' => 'متر',
                'unit_price' => 25.00,
                'total_amount' => 245000.00,
                'status' => 'approved',
                'notes' => 'مطابق للمواصفات الفنية',
            ],
            [
                'item_description' => 'أبواب حديدية',
                'requested_quantity' => 25.00,
                'approved_quantity' => 25.00,
                'unit' => 'وحدة',
                'unit_price' => 8000.00,
                'total_amount' => 200000.00,
                'status' => 'pending',
                'notes' => 'تحت المراجعة',
            ],
            [
                'item_description' => 'نافذة زجاجية',
                'requested_quantity' => 40.00,
                'approved_quantity' => 40.00,
                'unit' => 'وحدة',
                'unit_price' => 15000.00,
                'total_amount' => 600000.00,
                'status' => 'approved',
                'notes' => 'مطابقة للمواصفات',
            ],
            [
                'item_description' => 'أرضية سيراميك',
                'requested_quantity' => 800.00,
                'approved_quantity' => 780.00,
                'unit' => 'متر مربع',
                'unit_price' => 65.00,
                'total_amount' => 50700.00,
                'status' => 'rejected',
                'notes' => 'غير مطابقة للمواصفات',
            ],
        ];

        foreach ($quantities as $quantityData) {
            ContractQuantity::create([
                'contract_id' => $contracts->random()->id,
                'user_id' => $users->random()->id,
                'item_description' => $quantityData['item_description'],
                'requested_quantity' => $quantityData['requested_quantity'],
                'approved_quantity' => $quantityData['approved_quantity'],
                'unit' => $quantityData['unit'],
                'unit_price' => $quantityData['unit_price'],
                'total_amount' => $quantityData['total_amount'],
                'status' => $quantityData['status'],
                'notes' => $quantityData['notes'],
                'submitted_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}