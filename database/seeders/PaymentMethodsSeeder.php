<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default payment methods
        $methods = [
            [
                'workspace_id' => 1,
                'title' => 'نقداً',
                'description' => 'الدفع نقداً',
            ],
            [
                'workspace_id' => 1,
                'title' => 'شيك مصرفي',
                'description' => 'الدفع عن طريق الشيكات البنكية',
            ],
            [
                'workspace_id' => 1,
                'title' => 'تحويل بنكي',
                'description' => 'الدفع عن طريق التحويلات البنكية',
            ],
            [
                'workspace_id' => 1,
                'title' => 'بطاقة ائتمان',
                'description' => 'الدفع عن طريق البطاقات الائتمانية',
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate(
                ['title' => $method['title']],
                $method
            );
        }

        $this->command->info('Payment Methods seeded successfully!');
    }
}