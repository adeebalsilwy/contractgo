<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class YemeniUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define diverse Yemeni user data
        $users = [
            [
                'first_name' => 'أحمد',
                'last_name' => 'المصري',
                'email' => 'ahmed.almasri@example.com',
                'password' => Hash::make('12345678'),
                'default_workspace_id' => 1,
                'phone' => '+967771234567',
                'address' => 'صنعاء، شارع الزبيري',
                'city' => 'صنعاء',
                'country' => 'اليمن',
            ],
            [
                'first_name' => 'فاطمة',
                'last_name' => 'النعمان',
                'email' => 'fatima.alnuman@example.com',
                'password' => Hash::make('12345678'),
                'default_workspace_id' => 1,
                'phone' => '+967772345678',
                'address' => 'صنعاء، حي شملان',
                'city' => 'صنعاء',
                'country' => 'اليمن',
            ],
            [
                'first_name' => 'محمد',
                'last_name' => 'السيد',
                'email' => 'mohammed.alsayed@example.com',
                'password' => Hash::make('12345678'),
                'default_workspace_id' => 1,
                'phone' => '+967773456789',
                'address' => 'تعز، شارع 26 سبتمبر',
                'city' => 'تعز',
                'country' => 'اليمن',
            ],
            [
                'first_name' => 'أمينة',
                'last_name' => 'الغريب',
                'email' => 'amina.alghareeb@example.com',
                'password' => Hash::make('12345678'),
                'default_workspace_id' => 1,
                'phone' => '+967774567890',
                'address' => 'عدن، كريتر',
                'city' => 'عدن',
                'country' => 'اليمن',
            ],
            [
                'first_name' => 'خالد',
                'last_name' => 'الوهبي',
                'email' => 'khaled.alwahbi@example.com',
                'password' => Hash::make('12345678'),
                'default_workspace_id' => 1,
                'phone' => '+967775678901',
                'address' => 'المكلا، شارع باوزير',
                'city' => 'المكلا',
                'country' => 'اليمن',
            ],
            [
                'first_name' => 'نورا',
                'last_name' => 'الدبعي',
                'email' => 'noura.aldabaie@example.com',
                'password' => Hash::make('12345678'),
                'default_workspace_id' => 1,
                'phone' => '+967776789012',
                'address' => 'الحديدة، شارع صنعاء',
                'city' => 'الحديدة',
                'country' => 'اليمن',
            ],
            [
                'first_name' => 'ياسر',
                'last_name' => 'العكبري',
                'email' => 'yasser.alkabeeri@example.com',
                'password' => Hash::make('12345678'),
                'default_workspace_id' => 1,
                'phone' => '+967777890123',
                'address' => 'إب، شارع جامعة إب',
                'city' => 'إب',
                'country' => 'اليمن',
            ],
            [
                'first_name' => 'سارة',
                'last_name' => 'الصوفي',
                'email' => 'sara.alsufi@example.com',
                'password' => Hash::make('12345678'),
                'default_workspace_id' => 1,
                'phone' => '+967778901234',
                'address' => 'ذمار، شارع تعز',
                'city' => 'ذمار',
                'country' => 'اليمن',
            ],
            [
                'first_name' => 'عمر',
                'last_name' => 'ال frequently_used',
                'email' => 'omar.alfrequently@example.com',
                'password' => Hash::make('12345678'),
                'default_workspace_id' => 1,
                'phone' => '+967779012345',
                'address' => 'ريمة، شارع العامرية',
                'city' => 'ريمة',
                'country' => 'اليمن',
            ],
            [
                'first_name' => 'ليلى',
                'last_name' => 'الشامي',
                'email' => 'layla.ashami@example.com',
                'password' => Hash::make('12345678'),
                'default_workspace_id' => 1,
                'phone' => '+967770123456',
                'address' => 'صعدة، شارع صنعاء',
                'city' => 'صعدة',
                'country' => 'اليمن',
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}