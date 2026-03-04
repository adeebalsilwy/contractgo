<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class YemeniClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define diverse Yemeni client data
        $clients = [
            [
                'first_name' => 'وزارة',
                'last_name' => 'الأشغال العامة والطرق',
                'company' => 'وزارة الأشغال العامة والطرق',
                'email' => 'info@mopw.gov.ye',
                'phone' => '+967123456789',
                'address' => 'صنعاء، الجمهورية اليمنية',
                'city' => 'صنعاء',
                'country' => 'اليمن',
                'default_workspace_id' => 1,
                'password' => bcrypt('12345678'),
                'state' => 'صنعاء', // Default state
                'zip' => '11111', // Default zip code
            ],
            [
                'first_name' => 'الهيئة',
                'last_name' => 'العامة للكهرباء',
                'company' => 'الهيئة العامة للكهرباء',
                'email' => 'info@yepco.gov.ye',
                'phone' => '+967123456790',
                'address' => 'صنعاء، الجمهورية اليمنية',
                'city' => 'صنعاء',
                'country' => 'اليمن',
                'default_workspace_id' => 1,
                'password' => bcrypt('12345678'),
                'state' => 'صنعاء', // Default state
                'zip' => '11111', // Default zip code
            ],
            [
                'first_name' => 'شركة',
                'last_name' => 'الnational construction',
                'company' => 'شركة البناء الوطنية',
                'email' => 'contact@national-construction.co.ye',
                'phone' => '+967987654321',
                'address' => 'صنعاء، الجمهورية اليمنية',
                'city' => 'صنعاء',
                'country' => 'اليمن',
                'default_workspace_id' => 1,
                'password' => bcrypt('12345678'),
                'state' => 'صنعاء', // Default state
                'zip' => '11111', // Default zip code
            ],
            [
                'first_name' => 'مؤسسة',
                'last_name' => 'ال specialized engineering',
                'company' => 'مؤسسة المهندسون المتخصصون',
                'email' => 'info@specialized-eng.co.ye',
                'phone' => '+967987654322',
                'address' => 'تعز، الجمهورية اليمنية',
                'city' => 'تعز',
                'country' => 'اليمن',
                'default_workspace_id' => 1,
                'password' => bcrypt('12345678'),
                'state' => 'صنعاء', // Default state
                'zip' => '11111', // Default zip code
            ],
            [
                'first_name' => 'شركة',
                'last_name' => 'development group',
                'company' => 'مجموعة التطوير',
                'email' => 'contact@dev-group.co.ye',
                'phone' => '+967987654323',
                'address' => 'عدن، الجمهورية اليمنية',
                'city' => 'عدن',
                'country' => 'اليمن',
                'default_workspace_id' => 1,
                'password' => bcrypt('12345678'),
                'state' => 'صنعاء', // Default state
                'zip' => '11111', // Default zip code
            ],
            [
                'first_name' => 'الهيئة',
                'last_name' => 'العامة للمياه',
                'company' => 'الهيئة العامة لمشروعات المياه',
                'email' => 'info@wra.gov.ye',
                'phone' => '+967123456791',
                'address' => 'صنعاء، الجمهورية اليمنية',
                'city' => 'صنعاء',
                'country' => 'اليمن',
                'default_workspace_id' => 1,
                'password' => bcrypt('12345678'),
                'state' => 'صنعاء', // Default state
                'zip' => '11111', // Default zip code
            ],
            [
                'first_name' => 'شركة',
                'last_name' => 'build tech',
                'company' => 'شركة التقنية للبناء',
                'email' => 'info@buildtech.co.ye',
                'phone' => '+967987654324',
                'address' => 'الحديدة، الجمهورية اليمنية',
                'city' => 'الحديدة',
                'country' => 'اليمن',
                'default_workspace_id' => 1,
                'password' => bcrypt('12345678'),
                'state' => 'صنعاء', // Default state
                'zip' => '11111', // Default zip code
            ],
            [
                'first_name' => 'مؤسسة',
                'last_name' => 'ال infrastructure',
                'company' => 'مؤسسة البنية التحتية',
                'email' => 'contact@infrastructure.co.ye',
                'phone' => '+967987654325',
                'address' => 'المكلا، الجمهورية اليمنية',
                'city' => 'المكلا',
                'country' => 'اليمن',
                'default_workspace_id' => 1,
                'password' => bcrypt('12345678'),
                'state' => 'صنعاء', // Default state
                'zip' => '11111', // Default zip code
            ],
            [
                'first_name' => 'شركة',
                'last_name' => 'yemen contracting',
                'company' => 'شركة اليمن للإنشاءات',
                'email' => 'info@yemen-contracting.co.ye',
                'phone' => '+967987654326',
                'address' => 'ريمة، الجمهورية اليمنية',
                'city' => 'ريمة',
                'country' => 'اليمن',
                'default_workspace_id' => 1,
                'password' => bcrypt('12345678'),
                'state' => 'صنعاء', // Default state
                'zip' => '11111', // Default zip code
            ],
            [
                'first_name' => 'الهيئة',
                'last_name' => 'العامة للطيران',
                'company' => 'الهيئة العامة للطيران المدني',
                'email' => 'info@gcaa.gov.ye',
                'phone' => '+967123456792',
                'address' => 'صنعاء، الجمهورية اليمنية',
                'city' => 'صنعاء',
                'country' => 'اليمن',
                'default_workspace_id' => 1,
                'password' => bcrypt('12345678'),
                'state' => 'صنعاء', // Default state
                'zip' => '11111', // Default zip code
            ],
        ];

        foreach ($clients as $index => $clientData) {
            // Check if client already exists
            if (!Client::where('email', $clientData['email'])->exists()) {
                $clientWithProfession = array_merge($clientData, [
                    'profession_id' => ($index % 4) + 1, // Cycle through professions 1-4
                ]);
                Client::create($clientWithProfession);
                $this->command->info('Created client: ' . $clientData['first_name'] . ' ' . $clientData['last_name']);
            } else {
                $this->command->info('Client already exists: ' . $clientData['first_name'] . ' ' . $clientData['last_name']);
            }
        }
    }
}