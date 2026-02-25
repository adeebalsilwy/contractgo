<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\Status;
use App\Models\Priority;
use App\Models\Tag;
use App\Models\Unit;
use App\Models\Item;
use App\Models\ItemPricing;
use App\Models\Contract;
use App\Models\ContractQuantity;

class YemeniComprehensiveSeeder extends Seeder
{
    /**
     * Run the database seeds with Yemeni data in Arabic.
     */
    public function run(): void
    {
        $this->command->info('بدء تهيئة البيانات الشاملة باللغة العربية اليمنية...');
        
        // تأكد من وجود الجداول المطلوبة
        $this->ensureTablesExist();
        
        // تهيئة المستخدمين
        $this->seedYemeniUsers();
        
        // تهيئة العملاء
        $this->seedYemeniClients();
        
        // تهيئة الوحدات
        $this->seedYemeniUnits();
        
        // تهيئة العناصر
        $this->seedYemeniItems();
        
        // تهيئة التسعير
        $this->seedYemeniItemPricing();
        
        // تهيئة الحالات
        $this->seedYemeniStatuses();
        
        // تهيئة الأولويات
        $this->seedYemeniPriorities();
        
        // تهيئة العلامات
        $this->seedYemeniTags();
        
        // تهيئة المشاريع
        $this->seedYemeniProjects();
        
        // تهيئة المهام
        $this->seedYemeniTasks();
        
        // تهيئة العقود
        $this->seedYemeniContracts();
        
        // تهيئة كميات العقد
        $this->seedYemeniContractQuantities();
        
        $this->command->info('اكتملت تهيئة البيانات الشاملة باللغة العربية اليمنية بنجاح!');
    }
    
    private function ensureTablesExist(): void
    {
        $tables = [
            'users', 'clients', 'projects', 'tasks', 'statuses', 
            'priorities', 'tags', 'units', 'items', 'item_pricing',
            'contracts', 'contract_quantities'
        ];
        
        foreach ($tables as $table) {
            if (!DB::getSchemaBuilder()->hasTable($table)) {
                $this->command->warn("الجدول {$table} غير موجود، يرجى تشغيل المهاجرات أولاً.");
                exit(1);
            }
        }
    }
    
    private function seedYemeniUsers(): void
    {
        $this->command->info('تهيئة مستخدمي اليمن...');
        
        // مسح البيانات السابقة
        DB::table('users')->truncate();
        
        $users = [
            [
                'workspace_id' => 1,
                'first_name' => 'أحمد',
                'last_name' => 'المصري',
                'email' => 'ahmed.misri@example.com',
                'password' => Hash::make('password'),
                'phone' => '+967771234567',
                'country' => 'اليمن',
                'address' => 'صنعاء، شارع الزبيري',
                'city' => 'صنعاء',
                'email_verified_at' => now(),
                'default_workspace_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'workspace_id' => 1,
                'first_name' => 'فاطمة',
                'last_name' => 'النعمان',
                'email' => 'fatima.almnomhan@example.com',
                'password' => Hash::make('password'),
                'phone' => '+967772345678',
                'country' => 'اليمن',
                'address' => 'صنعاء، حي شملان',
                'city' => 'صنعاء',
                'email_verified_at' => now(),
                'default_workspace_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'workspace_id' => 1,
                'first_name' => 'محمد',
                'last_name' => 'الحمضاني',
                'email' => 'mohammed.hamdani@example.com',
                'password' => Hash::make('password'),
                'phone' => '+967773456789',
                'country' => 'اليمن',
                'address' => 'تعز، شارع جمال',
                'city' => 'تعز',
                'email_verified_at' => now(),
                'default_workspace_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'workspace_id' => 1,
                'first_name' => 'منى',
                'last_name' => 'الوصابي',
                'email' => 'mona.wosabi@example.com',
                'password' => Hash::make('password'),
                'phone' => '+967774567890',
                'country' => 'اليمن',
                'address' => 'عدن، كريتر',
                'city' => 'عدن',
                'email_verified_at' => now(),
                'default_workspace_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'workspace_id' => 1,
                'first_name' => 'علي',
                'last_name' => 'الرضومي',
                'email' => 'ali.radoumi@example.com',
                'password' => Hash::make('password'),
                'phone' => '+967775678901',
                'country' => 'اليمن',
                'address' => 'الحديدة، شارع الميناء',
                'city' => 'الحديدة',
                'email_verified_at' => now(),
                'default_workspace_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        
        foreach ($users as $user) {
            User::create($user);
        }
    }
    
    private function seedYemeniClients(): void
    {
        $this->command->info('تهيئة عملاء اليمن...');
        
        // مسح البيانات السابقة
        DB::table('clients')->truncate();
        
        $clients = [
            [
                'workspace_id' => 1,
                'first_name' => 'شركة',
                'last_name' => 'الانماء للتجارة',
                'email' => 'info@alinmaa-trade.com',
                'password' => Hash::make('password'),
                'phone' => '+96712345678',
                'country' => 'اليمن',
                'address' => 'صنعاء، شارع الثورة',
                'city' => 'صنعاء',
                'company_name' => 'شركة الانماء للتجارة',
                'tax_number' => '123456789',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'workspace_id' => 1,
                'first_name' => 'مؤسسة',
                'last_name' => 'الرحمن للإنشاءات',
                'email' => 'contact@alrahman-construction.com',
                'password' => Hash::make('password'),
                'phone' => '+96712345679',
                'country' => 'اليمن',
                'address' => 'تعز، شارع الستين',
                'city' => 'تعز',
                'company_name' => 'مؤسسة الرحمن للإنشاءات',
                'tax_number' => '987654321',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'workspace_id' => 1,
                'first_name' => 'شركة',
                'last_name' => 'العصر الجديد',
                'email' => 'info@alaser-aljadid.com',
                'password' => Hash::make('password'),
                'phone' => '+96712345680',
                'country' => 'اليمن',
                'address' => 'عدن، شارع الجمهورية',
                'city' => 'عدن',
                'company_name' => 'شركة العصر الجديد',
                'tax_number' => '456789123',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        
        foreach ($clients as $client) {
            Client::create($client);
        }
    }
    
    private function seedYemeniUnits(): void
    {
        $this->command->info('تهيئة الوحدات...');
        
        // مسح البيانات السابقة
        DB::table('units')->truncate();
        
        $units = [
            [
                'workspace_id' => 1,
                'title' => 'قطعة',
                'description' => 'وحدة القطعة',
            ],
            [
                'workspace_id' => 1,
                'title' => 'صندوق',
                'description' => 'وحدة الصندوق',
            ],
            [
                'workspace_id' => 1,
                'title' => 'كيلوجرام',
                'description' => 'وحدة الوزن الكيلوجرام',
            ],
            [
                'workspace_id' => 1,
                'title' => 'متر',
                'description' => 'وحدة الطول المتر',
            ],
            [
                'workspace_id' => 1,
                'title' => 'لتر',
                'description' => 'وحدة الحجم اللتر',
            ],
            [
                'workspace_id' => 1,
                'title' => 'طن',
                'description' => 'وحدة الوزن الطن',
            ],
            [
                'workspace_id' => 1,
                'title' => 'جالون',
                'description' => 'وحدة السعة الجالون',
            ],
            [
                'workspace_id' => 1,
                'title' => 'كيس',
                'description' => 'وحدة الكيس',
            ]
        ];
        
        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
    
    private function seedYemeniItems(): void
    {
        $this->command->info('تهيئة العناصر...');
        
        // مسح البيانات السابقة
        DB::table('items')->truncate();
        
        $items = [
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'خرسانة مسلحة',
                'price' => 120.00,
                'description' => 'خرسانة مسلحة عالية القوة لمشاريع البناء',
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'حديد التسليح',
                'price' => 85.00,
                'description' => 'حديد تسليح عيار 12 مم لتعزيز الخرسانة',
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'أسلاك كهربائية',
                'price' => 250.00,
                'description' => 'أسلاك كهربائية مغلفة بالنحاس عالي الجودة',
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'أكياس الإسمنت',
                'price' => 60.00,
                'description' => 'إسمنت بورتلاندي معبأ في أكياس 50 كجم',
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'ألواح خشبية',
                'price' => 45.00,
                'description' => 'ألواح خشبية مختارة للاستخدام في النجارة',
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'طلاء جدران',
                'price' => 75.00,
                'description' => 'طلاء داخلي وخارجي عالي الجودة',
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'عزل حراري',
                'price' => 35.00,
                'description' => 'مواد عزل حراري لبناء المنازل',
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'أنابيب بلاستيك',
                'price' => 25.00,
                'description' => 'أنابيب بلاستيكية لشبكات المياه',
            ]
        ];
        
        foreach ($items as $item) {
            Item::create($item);
        }
    }
    
    private function seedYemeniItemPricing(): void
    {
        $this->command->info('تهيئة تسعيرات العناصر...');
        
        // مسح البيانات السابقة
        DB::table('item_pricing')->truncate();
        
        $items = Item::all();
        $units = Unit::all();
        $users = User::all();
        
        $pricingRecords = [
            // تسعيرة الخرسانة المسلحة
            [
                'item_id' => $items->first()->id,
                'unit_id' => $units->first()->id,
                'price' => 120.00,
                'description' => 'تسعيرة الخرسانة المسلحة عالية الجودة',
            ],
            // تسعيرة حديد التسليح
            [
                'item_id' => $items->skip(1)->first()->id,
                'unit_id' => $units->first()->id,
                'price' => 85.00,
                'description' => 'تسعيرة حديد التسليح عيار 12 مم',
            ],
            // تسعيرة الأسلاك الكهربائية
            [
                'item_id' => $items->skip(2)->first()->id,
                'unit_id' => $units->first()->id,
                'price' => 250.00,
                'description' => 'تسعيرة الأسلاك الكهربائية النحاسية',
            ],
            // تسعيرة أكياس الإسمنت
            [
                'item_id' => $items->skip(3)->first()->id,
                'unit_id' => $units->first()->id,
                'price' => 60.00,
                'description' => 'تسعيرة أكياس الإسمنت الصلب',
            ],
            // تسعيرة الألواح الخشبية
            [
                'item_id' => $items->skip(4)->first()->id,
                'unit_id' => $units->first()->id,
                'price' => 45.00,
                'description' => 'تسعيرة الألواح الخشبية المختارة',
            ]
        ];
        
        foreach ($pricingRecords as $record) {
            ItemPricing::create($record);
        }
    }
    
    private function seedYemeniStatuses(): void
    {
        $this->command->info('تهيئة حالات المشاريع...');
        
        // مسح البيانات السابقة
        DB::table('status')->truncate();
        
        $statuses = [
            [
                'title' => 'معلق',
                'color' => 'warning',
                'slug' => 'pending'
            ],
            [
                'title' => 'قيد التنفيذ',
                'color' => 'primary',
                'slug' => 'in-progress'
            ],
            [
                'title' => 'مكتمل',
                'color' => 'success',
                'slug' => 'completed'
            ],
            [
                'title' => 'ملغي',
                'color' => 'danger',
                'slug' => 'cancelled'
            ],
            [
                'title' => 'معلق مؤقتاً',
                'color' => 'secondary',
                'slug' => 'on-hold'
            ],
            [
                'title' => 'تحت المراجعة',
                'color' => 'info',
                'slug' => 'review'
            ],
            [
                'title' => 'مسودة',
                'color' => 'light',
                'slug' => 'draft'
            ],
            [
                'title' => 'معتمد',
                'color' => 'success',
                'slug' => 'approved'
            ],
            [
                'title' => 'مرفوض',
                'color' => 'danger',
                'slug' => 'rejected'
            ],
            [
                'title' => 'مؤرشف',
                'color' => 'dark',
                'slug' => 'archived'
            ]
        ];
        
        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
    
    private function seedYemeniPriorities(): void
    {
        $this->command->info('تهيئة أولويات المهام...');
        
        // مسح البيانات السابقة
        DB::table('priorities')->truncate();
        
        $priorities = [
            [
                'title' => 'منخفض',
                'color' => 'success',
                'slug' => 'low'
            ],
            [
                'title' => 'متوسط',
                'color' => 'warning',
                'slug' => 'medium'
            ],
            [
                'title' => 'مرتفع',
                'color' => 'danger',
                'slug' => 'high'
            ],
            [
                'title' => 'حرج',
                'color' => 'dark',
                'slug' => 'critical'
            ],
            [
                'title' => 'عاجل',
                'color' => 'primary',
                'slug' => 'urgent'
            ]
        ];
        
        foreach ($priorities as $priority) {
            Priority::create($priority);
        }
    }
    
    private function seedYemeniTags(): void
    {
        $this->command->info('تهيئة علامات المشاريع...');
        
        // مسح البيانات السابقة
        DB::table('tags')->truncate();
        
        $tags = [
            [
                'title' => 'مهم',
                'color' => 'danger',
                'slug' => 'important'
            ],
            [
                'title' => 'عاجل',
                'color' => 'primary',
                'slug' => 'urgent'
            ],
            [
                'title' => 'تطوير',
                'color' => 'info',
                'slug' => 'development'
            ],
            [
                'title' => 'تصميم',
                'color' => 'success',
                'slug' => 'design'
            ],
            [
                'title' => 'اختبار',
                'color' => 'warning',
                'slug' => 'testing'
            ],
            [
                'title' => 'خطأ',
                'color' => 'danger',
                'slug' => 'bug'
            ],
            [
                'title' => 'ميزة',
                'color' => 'primary',
                'slug' => 'feature'
            ],
            [
                'title' => 'توثيق',
                'color' => 'secondary',
                'slug' => 'documentation'
            ],
            [
                'title' => 'طلب عميل',
                'color' => 'info',
                'slug' => 'client-request'
            ],
            [
                'title' => 'صيانة',
                'color' => 'light',
                'slug' => 'maintenance'
            ]
        ];
        
        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
    
    private function seedYemeniProjects(): void
    {
        $this->command->info('تهيئة مشاريع اليمن...');
        
        // مسح البيانات السابقة
        DB::table('projects')->truncate();
        
        $users = User::all();
        $clients = Client::all();
        $statuses = Status::all();
        $tags = Tag::all();
        
        $projects = [
            [
                'workspace_id' => 1,
                'title' => 'بناء مجمع سكني',
                'description' => 'بناء مجمع سكني مكون من 5 طوابق في صنعاء',
                'budget' => 5000000.00,
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(10),
                'client_id' => $clients->first()->id,
                'created_by' => $users->first()->id,
                'status_id' => $statuses->first()->id,
                'progress' => 45,
                'note' => 'يتطلب مراقبة دقيقة لضمان الجودة',
            ],
            [
                'workspace_id' => 1,
                'title' => 'تجديد مكتب الشركة',
                'description' => 'تجديد وتصميم داخلي لمكتب شركة الانماء للتجارة',
                'budget' => 2500000.00,
                'start_date' => now()->subMonth(),
                'end_date' => now()->addMonths(6),
                'client_id' => $clients->skip(1)->first()->id,
                'created_by' => $users->skip(1)->first()->id,
                'status_id' => $statuses->skip(1)->first()->id,
                'progress' => 30,
                'note' => 'يجب الالتزام بالجدول الزمني بدقة',
            ],
            [
                'workspace_id' => 1,
                'title' => 'مشروع البنية التحتية',
                'description' => 'بناء شبكة طرق رئيسية في محافظة تعز',
                'budget' => 10000000.00,
                'start_date' => now()->subWeeks(2),
                'end_date' => now()->addYear(),
                'client_id' => $clients->skip(2)->first()->id,
                'created_by' => $users->skip(2)->first()->id,
                'status_id' => $statuses->skip(2)->first()->id,
                'progress' => 20,
                'note' => 'يتطلب تنسيق مع الجهات الحكومية',
            ]
        ];
        
        foreach ($projects as $project) {
            $newProject = Project::create($project);
            
            // إضافة علامات عشوائية للمشروع
            $randomTags = $tags->random(rand(1, 3));
            foreach ($randomTags as $tag) {
                DB::table('project_tag')->insert([
                    'project_id' => $newProject->id,
                    'tag_id' => $tag->id,
                ]);
            }
        }
    }
    
    private function seedYemeniTasks(): void
    {
        $this->command->info('تهيئة مهام اليمن...');
        
        // مسح البيانات السابقة
        DB::table('tasks')->truncate();
        
        $users = User::all();
        $projects = Project::all();
        $statuses = Status::all();
        $priorities = Priority::all();
        $tags = Tag::all();
        
        $tasks = [
            [
                'workspace_id' => 1,
                'title' => 'صب الخرسانة',
                'description' => 'صب الخرسانة المسلحة للأرضية الأولى',
                'project_id' => $projects->first()->id,
                'created_by' => $users->first()->id,
                'status_id' => $statuses->first()->id,
                'priority_id' => $priorities->skip(1)->first()->id,
                'due_date' => now()->addWeek(),
                'estimated_hours' => 40,
                'progress' => 75,
            ],
            [
                'workspace_id' => 1,
                'title' => 'تركيب الكهرباء',
                'description' => 'تركيب شبكات الكهرباء في الطابق الثاني',
                'project_id' => $projects->first()->id,
                'created_by' => $users->skip(1)->first()->id,
                'status_id' => $statuses->skip(1)->first()->id,
                'priority_id' => $priorities->first()->id,
                'due_date' => now()->addDays(10),
                'estimated_hours' => 30,
                'progress' => 40,
            ],
            [
                'workspace_id' => 1,
                'title' => 'دهان الجدران',
                'description' => 'دهان الجدران الداخلية بطلاء عالي الجودة',
                'project_id' => $projects->skip(1)->first()->id,
                'created_by' => $users->skip(2)->first()->id,
                'status_id' => $statuses->skip(2)->first()->id,
                'priority_id' => $priorities->skip(2)->first()->id,
                'due_date' => now()->addDays(5),
                'estimated_hours' => 20,
                'progress' => 10,
            ],
            [
                'workspace_id' => 1,
                'title' => 'تمهيد الأرض',
                'description' => 'تمهيد الأرض قبل تركيب البلاط',
                'project_id' => $projects->skip(2)->first()->id,
                'created_by' => $users->skip(3)->first()->id,
                'status_id' => $statuses->skip(3)->first()->id,
                'priority_id' => $priorities->first()->id,
                'due_date' => now()->addDays(3),
                'estimated_hours' => 15,
                'progress' => 90,
            ]
        ];
        
        foreach ($tasks as $task) {
            $newTask = Task::create($task);
            
            // إضافة علامات عشوائية للمهمة
            $randomTags = $tags->random(rand(1, 2));
            foreach ($randomTags as $tag) {
                DB::table('task_tag')->insert([
                    'task_id' => $newTask->id,
                    'tag_id' => $tag->id,
                ]);
            }
            
            // تعيين مستخدمين عشوائيين للمهمة
            $randomUsers = $users->random(rand(1, 2));
            foreach ($randomUsers as $user) {
                DB::table('task_user')->insert([
                    'task_id' => $newTask->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
    
    private function seedYemeniContracts(): void
    {
        $this->command->info('تهيئة عقود اليمن...');
        
        // مسح البيانات السابقة
        DB::table('contracts')->truncate();
        
        $projects = Project::all();
        $clients = Client::all();
        $users = User::all();
        
        $contracts = [
            [
                'workspace_id' => 1,
                'title' => 'عقد بناء المجمع السكني',
                'value' => 5000000.00,
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(10),
                'client_id' => $clients->first()->id,
                'project_id' => $projects->first()->id,
                'contract_type_id' => 1,
                'description' => 'عقد بناء مجمع سكني مكون من 5 طوابق في صنعاء',
                'created_by' => 'u_' . $users->first()->id,
            ],
            [
                'workspace_id' => 1,
                'title' => 'عقد تجديد مكتب الشركة',
                'value' => 2500000.00,
                'start_date' => now()->subMonth(),
                'end_date' => now()->addMonths(6),
                'client_id' => $clients->skip(1)->first()->id,
                'project_id' => $projects->skip(1)->first()->id,
                'contract_type_id' => 2,
                'description' => 'تجديد وتصميم داخلي لمكتب شركة الانماء للتجارة',
                'created_by' => 'u_' . $users->skip(1)->first()->id,
            ],
            [
                'workspace_id' => 1,
                'title' => 'عقد مشروع البنية التحتية',
                'value' => 10000000.00,
                'start_date' => now()->subWeeks(2),
                'end_date' => now()->addYear(),
                'client_id' => $clients->skip(2)->first()->id,
                'project_id' => $projects->skip(2)->first()->id,
                'contract_type_id' => 3,
                'description' => 'بناء شبكة طرق رئيسية في محافظة تعز',
                'created_by' => 'u_' . $users->skip(2)->first()->id,
            ]
        ];
        
        foreach ($contracts as $contract) {
            Contract::create($contract);
        }
    }
    
    private function seedYemeniContractQuantities(): void
    {
        $this->command->info('تهيئة كميات عقود اليمن...');
        
        // مسح البيانات السابقة
        DB::table('contract_quantities')->truncate();
        
        $contracts = Contract::all();
        $users = User::all();
        $items = Item::all();
        
        $quantities = [
            // عقد المجمع السكني - الخرسانة المسلحة
            [
                'contract_id' => $contracts->first()->id,
                'user_id' => $users->first()->id,
                'item_description' => $items->first()->title, // خرسانة مسلحة
                'requested_quantity' => 500.00,
                'approved_quantity' => 480.00,
                'unit' => 'متر مكعب',
                'unit_price' => 120.00,
                'total_amount' => 57600.00,
                'notes' => 'خرسانة عالية الجودة مطلوبة لأساسات المبنى',
                'supporting_documents' => json_encode(['specifications.pdf', 'quality_certificate.pdf']),
                'status' => 'approved',
                'submitted_at' => now()->subDays(10),
                'approved_rejected_at' => now()->subDays(5),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'تمت الموافقة عليها مع تحمل 4% تفاوت',
                'quantity_approval_signature' => 'signature_1.png'
            ],
            // عقد المجمع السكني - حديد التسليح
            [
                'contract_id' => $contracts->first()->id,
                'user_id' => $users->first()->id,
                'item_description' => $items->skip(1)->first()->title, // حديد التسليح
                'requested_quantity' => 250.00,
                'approved_quantity' => 245.00,
                'unit' => 'طن',
                'unit_price' => 85.00,
                'total_amount' => 20825.00,
                'notes' => 'حديد تسليح عيار 40 لتعزيز الهياكل',
                'supporting_documents' => json_encode(['grade_certificate.pdf']),
                'status' => 'approved',
                'submitted_at' => now()->subDays(8),
                'approved_rejected_at' => now()->subDays(3),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'تمت الموافقة عليها مع تعديل طفيف',
                'quantity_approval_signature' => 'signature_2.png'
            ],
            // عقد تجديد المكتب - طلاء الجدران
            [
                'contract_id' => $contracts->skip(1)->first()->id,
                'user_id' => $users->skip(1)->first()->id,
                'item_description' => $items->skip(5)->first()->title, // طلاء الجدران
                'requested_quantity' => 200.00,
                'approved_quantity' => 190.00,
                'unit' => 'جالون',
                'unit_price' => 75.00,
                'total_amount' => 14250.00,
                'notes' => 'طلاء داخلي وخارجي عالي الجودة',
                'supporting_documents' => json_encode(['color_samples.pdf']),
                'status' => 'approved',
                'submitted_at' => now()->subDays(7),
                'approved_rejected_at' => now()->subDays(2),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'تمت الموافقة عليها مع 5% تقليل لفحص الجودة',
                'quantity_approval_signature' => 'signature_3.png'
            ],
            // عقد مشروع البنية التحتية - مواد العزل
            [
                'contract_id' => $contracts->skip(2)->first()->id,
                'user_id' => $users->skip(2)->first()->id,
                'item_description' => $items->skip(6)->first()->title, // عزل حراري
                'requested_quantity' => 1000.00,
                'approved_quantity' => null, // في انتظار الموافقة
                'unit' => 'متر مربع',
                'unit_price' => 35.00,
                'total_amount' => null,
                'notes' => 'مواد عزل حراري لمشاريع الطرق',
                'supporting_documents' => json_encode(['technical_specs.pdf']),
                'status' => 'pending',
                'submitted_at' => now()->subDay(),
                'approved_rejected_at' => null,
                'approved_rejected_by' => null,
                'approval_rejection_notes' => null,
                'quantity_approval_signature' => null
            ],
            // عقد مشروع البنية التحتية - أنابيب بلاستيك
            [
                'contract_id' => $contracts->skip(2)->first()->id,
                'user_id' => $users->first()->id,
                'item_description' => $items->skip(7)->first()->title, // أنابيب بلاستيك
                'requested_quantity' => 5000.00,
                'approved_quantity' => 4800.00,
                'unit' => 'متر',
                'unit_price' => 25.00,
                'total_amount' => 120000.00,
                'notes' => 'أنابيب مياه لشبكة الصرف الصحي',
                'supporting_documents' => json_encode(['pipe_specs.pdf', 'installation_guide.pdf']),
                'status' => 'approved',
                'submitted_at' => now()->subDays(12),
                'approved_rejected_at' => now()->subDays(8),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'تمت الموافقة الكاملة',
                'quantity_approval_signature' => 'signature_4.png'
            ],
            // رفض كمية سابقة
            [
                'contract_id' => $contracts->skip(1)->first()->id,
                'user_id' => $users->skip(1)->first()->id,
                'item_description' => $items->skip(2)->first()->title, // أسلاك كهربائية
                'requested_quantity' => 100.00,
                'approved_quantity' => 0.00, // تم الرفض
                'unit' => 'لفة',
                'unit_price' => 250.00,
                'total_amount' => 0.00,
                'notes' => 'أسلاك الكهرباء لم تطابق معايير السلامة',
                'supporting_documents' => json_encode(['inspection_report.pdf']),
                'status' => 'rejected',
                'submitted_at' => now()->subDays(15),
                'approved_rejected_at' => now()->subDays(10),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'مرفوضة بسبب مسائل تتعلق بالسلامة',
                'quantity_approval_signature' => 'signature_5.png'
            ]
        ];
        
        foreach ($quantities as $quantity) {
            ContractQuantity::create($quantity);
        }
    }
}