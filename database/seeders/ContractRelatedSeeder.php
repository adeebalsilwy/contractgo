<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContractType;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Workspace;

class ContractRelatedSeeder extends Seeder
{
    public function run()
    {
        // Create sample users first (needed for workspace user_id)
        $users = [
            [
                'first_name' => 'أحمد',
                'last_name' => 'المداني',
                'email' => 'ahmed@example.com',
                'password' => bcrypt('password'),
                'phone' => '+967771234567',
                'address' => 'صنعاء، شارع الستين',
                'status' => 1
            ],
            [
                'first_name' => 'فاطمة',
                'last_name' => 'الصوفي',
                'email' => 'fatima@example.com',
                'password' => bcrypt('password'),
                'phone' => '+967771234568',
                'address' => 'تعز، شارع المدرسة',
                'status' => 1
            ],
            [
                'first_name' => 'محمد',
                'last_name' => 'القاضي',
                'email' => 'mohammed@example.com',
                'password' => bcrypt('password'),
                'phone' => '+967771234569',
                'address' => 'عدن، شارع المطار',
                'status' => 1
            ],
            [
                'first_name' => 'سارة',
                'last_name' => 'الحبيشي',
                'email' => 'sara@example.com',
                'password' => bcrypt('password'),
                'phone' => '+967771234570',
                'address' => 'الحديدة، شارع البحر',
                'status' => 1
            ],
            [
                'first_name' => 'علي',
                'last_name' => 'الشامي',
                'email' => 'ali@example.com',
                'password' => bcrypt('password'),
                'phone' => '+967771234571',
                'address' => 'إب، شارع الجامعة',
                'status' => 1
            ]
        ];
        
        $createdUsers = [];
        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
            $createdUsers[] = $user;
        }
        
        // Create sample workspaces if they don't exist (using first user as default)
        $workspaces = [
            ['title' => 'مكتب إدارة المشاريع', 'user_id' => $createdUsers[0]->id ?? 1],
            ['title' => 'مكتب البناء', 'user_id' => $createdUsers[1]->id ?? 2],
            ['title' => 'مكتب البنية التحتية', 'user_id' => $createdUsers[2]->id ?? 3]
        ];
        
        foreach ($workspaces as $workspaceData) {
            Workspace::firstOrCreate(
                ['title' => $workspaceData['title']],
                $workspaceData
            );
        }
        
        // Update users to assign default_workspace_ids now that workspaces exist
        $workspacesCreated = Workspace::all();
        for ($i = 0; $i < min(count($createdUsers), $workspacesCreated->count()); $i++) {
            $createdUsers[$i]->update(['default_workspace_id' => $workspacesCreated[$i]->id]);
        }
        
        // Create sample clients if they don't exist
        $clients = [
            [
                'first_name' => 'شركة',
                'last_name' => 'البناء الحديثة',
                'email' => 'modern.construction@example.com',
                'password' => bcrypt('password'),
                'phone' => '+967771234580',
                'company' => 'شركة البناء الحديثة',
                'address' => 'صنعاء، شارع صنعاء القديم',
                'city' => 'صنعاء',
                'status' => 1
            ],
            [
                'first_name' => 'مؤسسة',
                'last_name' => 'التطوير العمراني',
                'email' => 'urban.development@example.com',
                'password' => bcrypt('password'),
                'phone' => '+967771234581',
                'company' => 'مؤسسة التطوير العمراني',
                'address' => 'تعز، شارع الرباط',
                'city' => 'تعز',
                'status' => 1
            ],
            [
                'first_name' => 'شركة',
                'last_name' => 'الإعمار اليمنية',
                'email' => 'yemen.construction@example.com',
                'password' => bcrypt('password'),
                'phone' => '+967771234582',
                'company' => 'شركة الإعمار اليمنية',
                'address' => 'عدن، شارع الجمهورية',
                'city' => 'عدن',
                'status' => 1
            ]
        ];
        
        $createdClients = [];
        foreach ($clients as $clientData) {
            $client = Client::firstOrCreate(
                ['email' => $clientData['email']],
                $clientData
            );
            $createdClients[] = $client;
        }
        
        // Create sample projects if they don't exist
        $projects = [
            [
                'title' => 'مشروع بناء فندق السلام',
                'description' => 'بناء فندق فخم من 5 طوابق في وسط العاصمة',
                'status' => 'active',
                'budget' => 5000000,
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(365),
                'user_id' => $createdUsers[0]->id ?? 1,
                'client_id' => $createdClients[0]->id ?? 1,
                'workspace_id' => $workspacesCreated->first()->id ?? 1,
                'created_by' => $createdUsers[0]->id ?? 1,
                'status_id' => 1,
                'priority_id' => 1
            ],
            [
                'title' => 'مشروع تطوير مجمع تجاري',
                'description' => 'تطوير مجمع تجاري متكامل مع مواقف سيارات',
                'status' => 'active',
                'budget' => 8000000,
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(540),
                'user_id' => $createdUsers[1]->id ?? 2,
                'client_id' => $createdClients[1]->id ?? 2,
                'workspace_id' => $workspacesCreated->skip(1)->first()->id ?? 2,
                'created_by' => $createdUsers[1]->id ?? 2,
                'status_id' => 1,
                'priority_id' => 2
            ],
            [
                'title' => 'مشروع بناء مدرسة الأمل',
                'description' => 'بناء مدرسة ابتدائية لخدمة أبناء المنطقة',
                'status' => 'active',
                'budget' => 2000000,
                'start_date' => now()->subDays(45),
                'end_date' => now()->addDays(180),
                'user_id' => $createdUsers[2]->id ?? 3,
                'client_id' => $createdClients[2]->id ?? 3,
                'workspace_id' => $workspacesCreated->skip(2)->first()->id ?? 3,
                'created_by' => $createdUsers[2]->id ?? 3,
                'status_id' => 1,
                'priority_id' => 1
            ]
        ];
        
        foreach ($projects as $projectData) {
            Project::firstOrCreate(
                ['title' => $projectData['title']],
                $projectData
            );
        }
        
        // Create contract types if they don't exist
        $contractTypes = [
            ['type' => 'عقد إنشاءات', 'workspace_id' => $workspacesCreated->first()->id ?? 1],
            ['type' => 'عقد صيانة', 'workspace_id' => $workspacesCreated->first()->id ?? 1],
            ['type' => 'عقد توريد مواد', 'workspace_id' => $workspacesCreated->skip(1)->first()->id ?? 2],
            ['type' => 'عقد استشارات هندسية', 'workspace_id' => $workspacesCreated->skip(1)->first()->id ?? 2],
            ['type' => 'عقد تشييد وبناء', 'workspace_id' => $workspacesCreated->skip(2)->first()->id ?? 3],
            ['type' => 'عقد تجهيزات كهربائية', 'workspace_id' => $workspacesCreated->skip(2)->first()->id ?? 3],
        ];
        
        foreach ($contractTypes as $contractType) {
            ContractType::firstOrCreate(
                ['type' => $contractType['type']],
                $contractType
            );
        }
    }
}