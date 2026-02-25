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
            ],
            [
                'workspace_id' => 1,
                'title' => 'شيك مصرفي',
            ],
            [
                'workspace_id' => 1,
                'title' => 'تحويل بنكي',
            ],
            [
                'workspace_id' => 1,
                'title' => 'بطاقة ائتمان',
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