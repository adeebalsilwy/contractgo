<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Contract;
use App\Models\ContractQuantity;
use App\Models\ContractApproval;
use App\Models\ContractAmendment;
use App\Models\JournalEntry;
use App\Models\EstimatesInvoice;
use App\Models\Item;
use App\Models\ItemPricing;
use App\Models\Unit;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Client;
use App\Models\Tax;

class CompleteYemeniContractDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التأكد من وجود بيانات أساسية
        $this->ensureBasicDataExists();
        
        // إنشاء بيانات العقود اليمنية
        $this->createYemeniContracts();
        
        // إنشاء بيانات الكميات
        $this->createYemeniContractQuantities();
        
        // إنشاء بيانات الموافقات
        $this->createYemeniContractApprovals();
        
        // إنشاء بيانات التعديلات
        $this->createYemeniContractAmendments();
        
        // إنشاء بيانات القيود المحاسبية
        $this->createYemeniJournalEntries();
        
        // إنشاء بيانات الوحدات
        $this->createYemeniUnits();
        
        // إنشاء بيانات العناصر
        $this->createYemeniItems();
        
        // إنشاء بيانات تسعير العناصر
        $this->createYemeniItemPricings();
        
        // إنشاء بيانات الضرائب
        $this->createYemeniTaxes();
        
        // إنشاء بيانات الاستخلاصات (العروض/الفواتير)
        $this->createYemeniEstimatesInvoices();
        
        // ربط العناصر بالعقود والاستخلاصات
        $this->linkItemsToContractsAndInvoices();
        
        $this->command->info('تم إدخال بيانات العقود والاستخلاصات اليمنية بشكل كامل!');
    }
    
    private function ensureBasicDataExists()
    {
        // التأكد من وجود مساحة عمل
        if (Workspace::count() == 0) {
            Workspace::create([
                'title' => 'شركة البناء اليمنية',
                'user_id' => 1,
                'is_primary' => 1
            ]);
        }
        
        // التأكد من وجود عميل
        if (Client::count() == 0) {
            Client::create([
                'first_name' => 'محمد',
                'last_name' => 'العبيدي',
                'email' => 'mohammed.alabidi@example.com',
                'phone' => '777123456',
                'country_code' => '+967',
                'address' => 'صنعاء - شارع الزبيري',
                'city' => 'صنعاء',
                'state' => 'أمانة العاصمة',
                'country' => 'اليمن',
                'workspace_id' => 1,
                'status' => 1
            ]);
        }
        
        // التأكد من وجود مستخدمين
        if (User::where('email', 'supervisor@example.com')->count() == 0) {
            $supervisor = User::create([
                'first_name' => 'أحمد',
                'last_name' => 'السماعي',
                'email' => 'supervisor@example.com',
                'password' => bcrypt('password'),
                'phone' => '777987654',
                'country_code' => '+967',
                'default_workspace_id' => 1,
                'status' => 1,
                'email_verified_at' => now()
            ]);
            
            $supervisor->assignRole('admin');
        }
        
        if (User::where('email', 'manager@example.com')->count() == 0) {
            $manager = User::create([
                'first_name' => 'صالح',
                'last_name' => 'الحميري',
                'email' => 'manager@example.com',
                'password' => bcrypt('password'),
                'phone' => '777456789',
                'country_code' => '+967',
                'default_workspace_id' => 1,
                'status' => 1,
                'email_verified_at' => now()
            ]);
            
            $manager->assignRole('admin');
        }
        
        if (User::where('email', 'accountant@example.com')->count() == 0) {
            $accountant = User::create([
                'first_name' => 'فاطمة',
                'last_name' => 'القيسي',
                'email' => 'accountant@example.com',
                'password' => bcrypt('password'),
                'phone' => '777321654',
                'country_code' => '+967',
                'default_workspace_id' => 1,
                'status' => 1,
                'email_verified_at' => now()
            ]);
            
            $accountant->assignRole('admin');
        }
    }
    
    private function createYemeniContracts()
    {
        $contracts = [
            [
                'title' => 'مشروع توسعة طريق صنعاء - تعز',
                'description' => 'مشروع توسعة الطريق بين صنعاء وتعز لتحسين حركة النقل التجاري والمسافرين',
                'project_id' => 1,
                'client_id' => 1,
                'value' => 1500000000,
                'start_date' => '2026-03-01',
                'end_date' => '2027-03-01',
                'workflow_status' => 'approved',
                'workspace_id' => 1,
                'created_by' => 'u_1'
            ],
            [
                'title' => 'مشروع صيانة جسور محافظة حضرموت',
                'description' => 'صيانة وتحديث جسور محافظة حضرموت لضمان السلامة المرورية',
                'project_id' => 1,
                'client_id' => 1,
                'value' => 850000000,
                'start_date' => '2026-04-01',
                'end_date' => '2026-12-31',
                'workflow_status' => 'approved',
                'workspace_id' => 1,
                'created_by' => 'u_1'
            ],
            [
                'title' => 'مشروع بناء مجمع سكني في عدن',
                'description' => 'بناء مجمع سكني متكامل في منطقة كريتر بمحافظة عدن',
                'project_id' => 1,
                'client_id' => 1,
                'value' => 2200000000,
                'start_date' => '2026-05-01',
                'end_date' => '2028-05-01',
                'workflow_status' => 'approved',
                'workspace_id' => 1,
                'created_by' => 'u_1'
            ],
            [
                'title' => 'مشروع ترميم المسجد الكبير بصعدة',
                'description' => 'ترميم شامل للمسجد الكبير في مدينة صعدة مع الحفاظ على الطراز المعماري الأصلي',
                'project_id' => 1,
                'client_id' => 1,
                'value' => 650000000,
                'start_date' => '2026-06-01',
                'end_date' => '2027-06-01',
                'workflow_status' => 'approved',
                'workspace_id' => 1,
                'created_by' => 'u_1'
            ],
            [
                'title' => 'مشروع إنشاء مصنع معالجة المياه في إب',
                'description' => 'إنشاء مصنع حديث لمعالجة المياه الخام وتحويلها لمياه صالحة للشرب',
                'project_id' => 1,
                'client_id' => 1,
                'value' => 1800000000,
                'start_date' => '2026-07-01',
                'end_date' => '2028-07-01',
                'workflow_status' => 'approved',
                'workspace_id' => 1,
                'created_by' => 'u_1'
            ]
        ];
        
        foreach ($contracts as $contractData) {
            Contract::create($contractData);
        }
    }
    
    private function createYemeniContractQuantities()
    {
        $quantities = [
            // كميات لمشروع توسعة الطريق
            [
                'contract_id' => 1,
                'user_id' => 2, // المشرف أحمد السماعي
                'workspace_id' => 1,
                'item_description' => 'تنقيب وتجريف الأرض لمسافة 50 كم',
                'requested_quantity' => 50.00,
                'approved_quantity' => 50.00,
                'unit' => 'كيلومتر',
                'unit_price' => 15000000,
                'total_amount' => 750000000,
                'notes' => 'يشمل التنقيب بالآليات الثقيلة والتجريف الكامل',
                'status' => 'approved',
                'submitted_at' => now()->subDays(10),
                'approved_rejected_at' => now()->subDays(5),
                'approved_rejected_by' => 3, // المدير صالح الحميير
                'approval_rejection_notes' => 'تمت الموافقة على الكمية المطلوبة'
            ],
            [
                'contract_id' => 1,
                'user_id' => 2,
                'workspace_id' => 1,
                'item_description' => 'صب الخرسانة المسلحة للمسارات',
                'requested_quantity' => 150000,
                'approved_quantity' => 145000,
                'unit' => 'متر مربع',
                'unit_price' => 25000,
                'total_amount' => 3625000000,
                'notes' => 'خرسانة مقاومة عالية الجودة',
                'status' => 'approved',
                'submitted_at' => now()->subDays(2),
                'approved_rejected_at' => now()->subDays(1),
                'approved_rejected_by' => 3,
                'approval_rejection_notes' => 'تمت الموافقة مع تعديل طفيف في الكمية'
            ],
            [
                'contract_id' => 1,
                'user_id' => 2,
                'workspace_id' => 1,
                'item_description' => 'تركيب الحواجز الخرسانية والأعمدة',
                'requested_quantity' => 2000,
                'approved_quantity' => 2000,
                'unit' => 'وحدة',
                'unit_price' => 120000,
                'total_amount' => 240000000,
                'notes' => 'توفير الحماية اللازمة للمركبات والمشاة',
                'status' => 'approved',
                'submitted_at' => now()->subDays(15),
                'approved_rejected_at' => now()->subDays(8),
                'approved_rejected_by' => 3,
                'approval_rejection_notes' => 'تمت الموافقة مع شرط توفير معايير السلامة'
            ],
            
            // كميات لمشروع الصيانة
            [
                'contract_id' => 2,
                'user_id' => 2,
                'workspace_id' => 1,
                'item_description' => 'إصلاح وصيانة الجسر الرئيسي',
                'requested_quantity' => 1.00,
                'approved_quantity' => 1.00,
                'unit' => 'جسر',
                'unit_price' => 200000000,
                'total_amount' => 200000000,
                'notes' => 'يشمل تقوية الهيكل وتغيير الأجزاء التالفة',
                'status' => 'approved',
                'submitted_at' => now()->subDays(15),
                'approved_rejected_at' => now()->subDays(8),
                'approved_rejected_by' => 3,
                'approval_rejection_notes' => 'تمت الموافقة مع شرط استخدام مواد محلية'
            ],
            [
                'contract_id' => 2,
                'user_id' => 2,
                'workspace_id' => 1,
                'item_description' => 'تجهيز وإصلاح العوارض الفولاذية',
                'requested_quantity' => 50,
                'approved_quantity' => 45,
                'unit' => 'وحدة',
                'unit_price' => 800000,
                'total_amount' => 36000000,
                'notes' => 'توفير عوارض فولاذية عالية المقاومة للصدأ',
                'status' => 'approved',
                'submitted_at' => now()->subDays(3),
                'approved_rejected_at' => now()->subDays(2),
                'approved_rejected_by' => 3,
                'approval_rejection_notes' => 'تمت الموافقة مع تعديل في الكمية'
            ],
            
            // كميات لمشروع المجمع السكني
            [
                'contract_id' => 3,
                'user_id' => 2,
                'workspace_id' => 1,
                'item_description' => 'حفر وتهيئة الأرض للمباني',
                'requested_quantity' => 25000,
                'approved_quantity' => 25000,
                'unit' => 'متر مربع',
                'unit_price' => 15000,
                'total_amount' => 375000000,
                'notes' => 'تهيئة الأرض وحفر القواعد الخرسانية',
                'status' => 'approved',
                'submitted_at' => now()->subDays(1),
                'approved_rejected_at' => now()->subDays(1),
                'approved_rejected_by' => 3,
                'approval_rejection_notes' => 'تمت الموافقة النهائية'
            ],
            [
                'contract_id' => 3,
                'user_id' => 2,
                'workspace_id' => 1,
                'item_description' => 'صب الخرسانة المسلحة للقواعد',
                'requested_quantity' => 12000,
                'approved_quantity' => 12000,
                'unit' => 'متر مكعب',
                'unit_price' => 45000,
                'total_amount' => 540000000,
                'notes' => 'خرسانة مسلحة مقاومة للاهتزازات الزلزالية',
                'status' => 'approved',
                'submitted_at' => now()->subDays(12),
                'approved_rejected_at' => now()->subDays(6),
                'approved_rejected_by' => 3,
                'approval_rejection_notes' => 'تمت الموافقة مع تطبيق معايير السلامة الزلزالية'
            ],
            
            // كميات لمشروع المسجد
            [
                'contract_id' => 4,
                'user_id' => 2,
                'workspace_id' => 1,
                'item_description' => 'ترميم الهيكل الداخلي للمسجد',
                'requested_quantity' => 1500,
                'approved_quantity' => 1500,
                'unit' => 'متر مربع',
                'unit_price' => 30000,
                'total_amount' => 45000000,
                'notes' => 'الحفاظ على الطراز المعماري الأصلي مع الترميم',
                'status' => 'approved',
                'submitted_at' => now()->subDays(20),
                'approved_rejected_at' => now()->subDays(15),
                'approved_rejected_by' => 3,
                'approval_rejection_notes' => 'تمت الموافقة مع الحفاظ على المعالم التاريخية'
            ],
            [
                'contract_id' => 4,
                'user_id' => 2,
                'workspace_id' => 1,
                'item_description' => 'تجديد التكييف والتكييف المركزي',
                'requested_quantity' => 10,
                'approved_quantity' => 10,
                'unit' => 'وحدة',
                'unit_price' => 15000000,
                'total_amount' => 150000000,
                'notes' => 'تركيب نظام تكييف حديث وموفر للطاقة',
                'status' => 'approved',
                'submitted_at' => now()->subDays(18),
                'approved_rejected_at' => now()->subDays(14),
                'approved_rejected_by' => 3,
                'approval_rejection_notes' => 'تمت الموافقة مع تحديد المواصفات التقنية'
            ],
            
            // كميات لمشروع مصنع المياه
            [
                'contract_id' => 5,
                'user_id' => 2,
                'workspace_id' => 1,
                'item_description' => 'توفير الآلات والمعدات الخاصة بمعالجة المياه',
                'requested_quantity' => 15,
                'approved_quantity' => 15,
                'unit' => 'مجموعة معدات',
                'unit_price' => 80000000,
                'total_amount' => 1200000000,
                'notes' => 'تشمل خطوط المعالجة والأواني التخزينية',
                'status' => 'approved',
                'submitted_at' => now()->subDays(25),
                'approved_rejected_at' => now()->subDays(18),
                'approved_rejected_by' => 3,
                'approval_rejection_notes' => 'تمت الموافقة بعد فحص المعدات المعروضة'
            ],
            [
                'contract_id' => 5,
                'user_id' => 2,
                'workspace_id' => 1,
                'item_description' => 'تركيب خط أنابيب التوزيع',
                'requested_quantity' => 50000,
                'approved_quantity' => 48000,
                'unit' => 'متر',
                'unit_price' => 2000,
                'total_amount' => 96000000,
                'notes' => 'توفير شبكة أنابيب حديثة للوصول للمواطن',
                'status' => 'approved',
                'submitted_at' => now()->subDays(22),
                'approved_rejected_at' => now()->subDays(16),
                'approved_rejected_by' => 3,
                'approval_rejection_notes' => 'تمت الموافقة مع تعديل في الكمية حسب الخطة'
            ]
        ];
        
        foreach ($quantities as $quantityData) {
            ContractQuantity::create($quantityData);
        }
    }
    
    private function createYemeniContractApprovals()
    {
        $approvals = [
            [
                'contract_id' => 1,
                'approval_stage' => 'quantity_approval',
                'approver_id' => 3,
                'status' => 'approved',
                'comments' => 'تمت المراجعة والموافقة على العقد',
                'approved_rejected_at' => now()->subDays(20)
            ],
            [
                'contract_id' => 1,
                'approval_stage' => 'management_review',
                'approver_id' => 3,
                'status' => 'approved',
                'comments' => 'تمت المراجعة الإدارية والموافقة',
                'approved_rejected_at' => now()->subDays(18)
            ],
            [
                'contract_id' => 1,
                'approval_stage' => 'accounting_review',
                'approver_id' => 4,
                'status' => 'approved',
                'comments' => 'تمت المراجعة المحاسبية والموافقة',
                'approved_rejected_at' => now()->subDays(16)
            ],
            [
                'contract_id' => 1,
                'approval_stage' => 'final_approval',
                'approver_id' => 3,
                'status' => 'approved',
                'comments' => 'الموافقة النهائية على العقد',
                'approved_rejected_at' => now()->subDays(15)
            ],
            [
                'contract_id' => 2,
                'approval_stage' => 'quantity_approval',
                'approver_id' => 3,
                'status' => 'approved',
                'comments' => 'الموافقة على الكميات لمشروع الصيانة',
                'approved_rejected_at' => now()->subDays(15)
            ],
            [
                'contract_id' => 2,
                'approval_stage' => 'accounting_review',
                'approver_id' => 4,
                'status' => 'approved',
                'comments' => 'تمت المراجعة المالية والموافقة',
                'approved_rejected_at' => now()->subDays(12)
            ],
            [
                'contract_id' => 3,
                'approval_stage' => 'final_approval',
                'approver_id' => 3,
                'status' => 'approved',
                'comments' => 'تمت الموافقة النهائية على العقد',
                'approved_rejected_at' => now()->subDays(10)
            ],
            [
                'contract_id' => 4,
                'approval_stage' => 'quantity_approval',
                'approver_id' => 3,
                'status' => 'approved',
                'comments' => 'الموافقة على كميات مشروع ترميم المسجد',
                'approved_rejected_at' => now()->subDays(8)
            ],
            [
                'contract_id' => 5,
                'approval_stage' => 'management_review',
                'approver_id' => 3,
                'status' => 'approved',
                'comments' => 'تمت المراجعة الإدارية والموافقة',
                'approved_rejected_at' => now()->subDays(6)
            ]
        ];
        
        foreach ($approvals as $approvalData) {
            ContractApproval::create($approvalData);
        }
    }
    
    private function createYemeniContractAmendments()
    {
        $amendments = [
            [
                'contract_id' => 1,
                'requested_by_user_id' => 2,
                'approved_by_user_id' => 3,
                'amendment_type' => 'duration_extension',
                'request_reason' => 'تمديد مدة المشروع بسبب الظروف المناخية',
                'status' => 'approved',
                'approved_at' => now()->subDays(18)
            ],
            [
                'contract_id' => 1,
                'requested_by_user_id' => 2,
                'approved_by_user_id' => 3,
                'amendment_type' => 'budget_adjustment',
                'request_reason' => 'تعديل في الميزانية بسبب ارتفاع أسعار المواد',
                'status' => 'approved',
                'approved_at' => now()->subDays(15)
            ],
            [
                'contract_id' => 2,
                'requested_by_user_id' => 2,
                'approved_by_user_id' => 3,
                'amendment_type' => 'scope_change',
                'request_reason' => 'تعديل في نطاق العمل لتشمل جسور إضافية',
                'status' => 'approved',
                'approved_at' => now()->subDays(12)
            ],
            [
                'contract_id' => 3,
                'requested_by_user_id' => 2,
                'approved_by_user_id' => 3,
                'amendment_type' => 'duration_extension',
                'request_reason' => 'تمديد المدة بسبب تعقيدات التراخيص',
                'status' => 'approved',
                'approved_at' => now()->subDays(8)
            ],
            [
                'contract_id' => 5,
                'requested_by_user_id' => 2,
                'approved_by_user_id' => 3,
                'amendment_type' => 'scope_change',
                'request_reason' => 'تعديل في نطاق المشروع لإضافة وحدات معالجة إضافية',
                'status' => 'pending',
                'approved_at' => null
            ]
        ];
        
        foreach ($amendments as $amendmentData) {
            ContractAmendment::create($amendmentData);
        }
    }
    
    private function createYemeniJournalEntries()
    {
        $journalEntries = [
            [
                'contract_id' => 1,
                'entry_number' => 'JE-2026-001',
                'entry_type' => 'journal',
                'entry_date' => now()->subDays(20),
                'account_code' => '20201',
                'account_name' => 'الذمم المدينة - عقود قيد التنفيذ',
                'reference_number' => 'PAY-001',
                'credit_amount' => 0.00,
                'debit_amount' => 500000000.00,
                'description' => 'دفع دفعة أولى لمشروع توسعة الطريق صنعاء - تعز',
                'status' => 'posted',
                'created_by' => 4
            ],
            [
                'contract_id' => 1,
                'entry_number' => 'JE-2026-002',
                'entry_type' => 'journal',
                'entry_date' => now()->subDays(15),
                'account_code' => '10101',
                'account_name' => 'النقد في البنك',
                'reference_number' => 'PAY-001',
                'credit_amount' => 500000000.00,
                'debit_amount' => 0.00,
                'description' => 'تحويل دفعة لمشروع توسعة الطريق',
                'status' => 'posted',
                'created_by' => 4
            ],
            [
                'contract_id' => 2,
                'entry_number' => 'JE-2026-003',
                'entry_type' => 'journal',
                'entry_date' => now()->subDays(18),
                'account_code' => '20201',
                'account_name' => 'الذمم المدينة - عقود قيد التنفيذ',
                'reference_number' => 'PAY-002',
                'credit_amount' => 0.00,
                'debit_amount' => 300000000.00,
                'description' => 'دفع دفعة لمشروع صيانة جسور حضرموت',
                'status' => 'posted',
                'created_by' => 4
            ],
            [
                'contract_id' => 3,
                'entry_number' => 'JE-2026-004',
                'entry_type' => 'journal',
                'entry_date' => now()->subDays(12),
                'account_code' => '20201',
                'account_name' => 'الذمم المدينة - عقود قيد التنفيذ',
                'reference_number' => 'PAY-003',
                'credit_amount' => 0.00,
                'debit_amount' => 750000000.00,
                'description' => 'دفع دفعة أولى لمشروع المجمع السكني عدن',
                'status' => 'posted',
                'created_by' => 4
            ],
            [
                'contract_id' => 4,
                'entry_number' => 'JE-2026-005',
                'entry_type' => 'journal',
                'entry_date' => now()->subDays(10),
                'account_code' => '20201',
                'account_name' => 'الذمم المدينة - عقود قيد التنفيذ',
                'reference_number' => 'PAY-004',
                'credit_amount' => 0.00,
                'debit_amount' => 200000000.00,
                'description' => 'دفع دفعة لمشروع ترميم المسجد الكبير بصعدة',
                'status' => 'posted',
                'created_by' => 4
            ],
            [
                'contract_id' => 5,
                'entry_number' => 'JE-2026-006',
                'entry_type' => 'journal',
                'entry_date' => now()->subDays(5),
                'account_code' => '20201',
                'account_name' => 'الذمم المدينة - عقود قيد التنفيذ',
                'reference_number' => 'PAY-005',
                'credit_amount' => 0.00,
                'debit_amount' => 1000000000.00,
                'description' => 'دفع دفعة أولى لمشروع مصنع معالجة المياه بإب',
                'status' => 'posted',
                'created_by' => 4
            ],
            [
                'contract_id' => 1,
                'entry_number' => 'JE-2026-007',
                'entry_type' => 'journal',
                'entry_date' => now()->subDays(8),
                'account_code' => '50101',
                'account_name' => 'مصروفات تنفيذ الأعمال',
                'reference_number' => 'EXP-001',
                'credit_amount' => 0.00,
                'debit_amount' => 150000000.00,
                'description' => 'مصروفات تنفيذ أعمال توسعة الطريق',
                'status' => 'posted',
                'created_by' => 4
            ],
            [
                'contract_id' => 2,
                'entry_number' => 'JE-2026-008',
                'entry_type' => 'journal',
                'entry_date' => now()->subDays(6),
                'account_code' => '50102',
                'account_name' => 'مصروفات صيانة المعدات',
                'reference_number' => 'EXP-002',
                'credit_amount' => 0.00,
                'debit_amount' => 50000000.00,
                'description' => 'مصروفات صيانة المعدات لمشروع جسور حضرموت',
                'status' => 'posted',
                'created_by' => 4
            ]
        ];
        
        foreach ($journalEntries as $entryData) {
            JournalEntry::create($entryData);
        }
    }
    
    private function createYemeniUnits()
    {
        $units = [
            ['title' => 'كيلومتر', 'workspace_id' => 1],
            ['title' => 'متر مربع', 'workspace_id' => 1],
            ['title' => 'متر مكعب', 'workspace_id' => 1],
            ['title' => 'وحدة', 'workspace_id' => 1],
            ['title' => 'جسر', 'workspace_id' => 1],
            ['title' => 'متر', 'workspace_id' => 1],
            ['title' => 'مجموعة معدات', 'workspace_id' => 1],
            ['title' => 'لتر', 'workspace_id' => 1],
            ['title' => 'كيلوغرام', 'workspace_id' => 1],
            ['title' => 'طن', 'workspace_id' => 1]
        ];
        
        foreach ($units as $unitData) {
            Unit::firstOrCreate(['title' => $unitData['title']], $unitData);
        }
    }
    
    private function createYemeniItems()
    {
        $items = [
            [
                'title' => 'عملية التنقيب والتجريف',
                'description' => 'خدمة التنقيب والتجريف للأعمال الإنشائية',
                'price' => 15000000,
                'unit_id' => Unit::where('title', 'كيلومتر')->first()->id,
                'workspace_id' => 1
            ],
            [
                'title' => 'الخرسانة المسلحة',
                'description' => 'خدمة صب الخرسانة المسلحة بمواصفات عالية الجودة',
                'price' => 25000,
                'unit_id' => Unit::where('title', 'متر مربع')->first()->id,
                'workspace_id' => 1
            ],
            [
                'title' => 'الحواجز الخرسانية',
                'description' => 'تركيب الحواجز والأعمدة الخرسانية للسلامة',
                'price' => 120000,
                'unit_id' => Unit::where('title', 'وحدة')->first()->id,
                'workspace_id' => 1
            ],
            [
                'title' => 'إصلاح الجسور',
                'description' => 'صيانة وتقوية الجسور المتضررة',
                'price' => 200000000,
                'unit_id' => Unit::where('title', 'جسر')->first()->id,
                'workspace_id' => 1
            ],
            [
                'title' => 'العوارض الفولاذية',
                'description' => 'توريد وتثبيت العوارض الفولاذية المقاومة للصدأ',
                'price' => 800000,
                'unit_id' => Unit::where('title', 'وحدة')->first()->id,
                'workspace_id' => 1
            ],
            [
                'title' => 'الخرسانة للقواعد',
                'description' => 'خرسانة مسلحة مقاومة للاهتزازات الزلزالية',
                'price' => 45000,
                'unit_id' => Unit::where('title', 'متر مكعب')->first()->id,
                'workspace_id' => 1
            ],
            [
                'title' => 'ترميم الهيكل الداخلي',
                'description' => 'ترميم داخلي للمباني الحجرية والخشب',
                'price' => 30000,
                'unit_id' => Unit::where('title', 'متر مربع')->first()->id,
                'workspace_id' => 1
            ],
            [
                'title' => 'نظام التكييف المركزي',
                'description' => 'تركيب نظام تكييف حديث وموفر للطاقة',
                'price' => 15000000,
                'unit_id' => Unit::where('title', 'وحدة')->first()->id,
                'workspace_id' => 1
            ],
            [
                'title' => 'الآلات والمعدات المائية',
                'description' => 'معدات حديثة لمعالجة وتنقية المياه',
                'price' => 80000000,
                'unit_id' => Unit::where('title', 'مجموعة معدات')->first()->id,
                'workspace_id' => 1
            ],
            [
                'title' => 'خط الأنابيب',
                'description' => 'توفير وتركيب خط أنابيب التوزيع',
                'price' => 2000,
                'unit_id' => Unit::where('title', 'متر')->first()->id,
                'workspace_id' => 1
            ]
        ];
        
        foreach ($items as $itemData) {
            Item::create($itemData);
        }
    }
    
    private function createYemeniItemPricings()
    {
        $items = Item::all();
        $accountant = User::where('email', 'accountant@example.com')->first();
        
        foreach ($items as $index => $item) {
            ItemPricing::create([
                'item_id' => $item->id,
                'unit_id' => $item->unit_id,
                'price' => $item->price,
                'cost_price' => $item->price * 0.7,
                'min_selling_price' => $item->price * 0.9,
                'max_selling_price' => $item->price * 1.2,
                'is_active' => true,
                'valid_from' => now()->subDays(30),
                'valid_until' => now()->addDays(365),
                'created_by' => $accountant->id,
                'description' => 'التسعير الموافق للسوق اليمني للعقد ' . ($index + 1)
            ]);
        }
    }
    
    private function createYemeniTaxes()
    {
        $taxes = [
            ['title' => 'ضريبة القيمة المضافة', 'percentage' => 15, 'type' => 'percentage', 'workspace_id' => 1],
            ['title' => 'ضريبة الخدمة', 'percentage' => 10, 'type' => 'percentage', 'workspace_id' => 1],
            ['title' => 'رسم التسجيل', 'amount' => 1000000, 'type' => 'amount', 'workspace_id' => 1]
        ];
        
        foreach ($taxes as $taxData) {
            Tax::firstOrCreate(['title' => $taxData['title']], $taxData);
        }
    }
    
    private function createYemeniEstimatesInvoices()
    {
        $items = Item::all();
        $client = Client::first();
        $accountant = User::where('email', 'accountant@example.com')->first();
        
        // إنشاء عروض وفواتير استخلاصية متنوعة
        $estimates = [
            [
                'name' => 'عرض تنفيذ أعمال توسعة الطريق',
                'address' => 'صنعاء - شارع الزبيري',
                'city' => 'صنعاء',
                'state' => 'أمانة العاصمة',
                'country' => 'اليمن',
                'zip_code' => '00967',
                'phone' => '777123456',
                'type' => 'estimate',
                'status' => 'sent',
                'from_date' => '2026-03-01',
                'to_date' => '2026-03-31',
                'total' => 800000000,
                'tax_amount' => 120000000,
                'final_total' => 920000000,
                'note' => 'عرض شامل لتنفيذ أعمال توسعة الطريق بين صنعاء وتعز',
                'personal_note' => 'عرض مفصل يتضمن جميع الأعمال المطلوبة',
                'client_id' => $client->id,
                'workspace_id' => 1,
                'created_by' => 'u_' . $accountant->id
            ],
            [
                'name' => 'فاتورة استخلاصية لصيانة الجسور',
                'address' => 'مكتب العميل - حضرموت',
                'city' => 'المكلا',
                'state' => 'حضرموت',
                'country' => 'اليمن',
                'zip_code' => '00967',
                'phone' => '777987654',
                'type' => 'invoice',
                'status' => 'paid',
                'from_date' => '2026-04-01',
                'to_date' => '2026-04-30',
                'total' => 250000000,
                'tax_amount' => 37500000,
                'final_total' => 287500000,
                'note' => 'فاتورة استخلاصية لصيانة جسور محافظة حضرموت',
                'personal_note' => 'فاتورة مدفوعة بالكامل',
                'client_id' => $client->id,
                'workspace_id' => 1,
                'created_by' => 'u_' . $accountant->id
            ],
            [
                'name' => 'عرض أعمال المجمع السكني',
                'address' => 'منطقة كريتر - عدن',
                'city' => 'عدن',
                'state' => 'عدن',
                'country' => 'اليمن',
                'zip_code' => '00967',
                'phone' => '777456789',
                'type' => 'estimate',
                'status' => 'accepted',
                'from_date' => '2026-05-01',
                'to_date' => '2026-05-31',
                'total' => 950000000,
                'tax_amount' => 142500000,
                'final_total' => 1092500000,
                'note' => 'عرض متكامل لبناء المجمع السكني في عدن',
                'personal_note' => 'العرض مقبول من قبل العميل',
                'client_id' => $client->id,
                'workspace_id' => 1,
                'created_by' => 'u_' . $accountant->id
            ],
            [
                'name' => 'فاتورة ترميم المسجد',
                'address' => 'مدينة صعدة',
                'city' => 'صعدة',
                'state' => 'صعدة',
                'country' => 'اليمن',
                'zip_code' => '00967',
                'phone' => '777321654',
                'type' => 'invoice',
                'status' => 'partially_paid',
                'from_date' => '2026-06-01',
                'to_date' => '2026-06-30',
                'total' => 200000000,
                'tax_amount' => 30000000,
                'final_total' => 230000000,
                'note' => 'فاتورة استخلاصية لترميم المسجد الكبير بصعدة',
                'personal_note' => 'دفعة أولى مدفوعة، الباقي متأخر',
                'client_id' => $client->id,
                'workspace_id' => 1,
                'created_by' => 'u_' . $accountant->id
            ],
            [
                'name' => 'عرض مصنع معالجة المياه',
                'address' => 'مدينة إب',
                'city' => 'إب',
                'state' => 'إب',
                'country' => 'اليمن',
                'zip_code' => '00967',
                'phone' => '777654321',
                'type' => 'estimate',
                'status' => 'draft',
                'from_date' => '2026-07-01',
                'to_date' => '2026-07-31',
                'total' => 1300000000,
                'tax_amount' => 195000000,
                'final_total' => 1495000000,
                'note' => 'عرض مبدئي لإنشاء مصنع معالجة المياه في إب',
                'personal_note' => 'العرض قيد المراجعة النهائية',
                'client_id' => $client->id,
                'workspace_id' => 1,
                'created_by' => 'u_' . $accountant->id
            ]
        ];
        
        foreach ($estimates as $estimateData) {
            $estimate = EstimatesInvoice::create($estimateData);
            
            // ربط العناصر بالعرض/الفاتورة
            $randomItems = $items->random(rand(2, 4));
            foreach ($randomItems as $item) {
                $quantity = rand(1, 10);
                $rate = $item->price * (0.9 + (rand(0, 30) / 100)); // سعر مع تفاوت 10-30%
                $amount = $quantity * $rate;
                
                $estimate->items()->attach($item->id, [
                    'qty' => $quantity,
                    'unit_id' => $item->unit_id,
                    'rate' => $rate,
                    'tax_id' => rand(1, 2), // ضريبة عشوائية
                    'amount' => $amount
                ]);
            }
        }
    }
    
    private function linkItemsToContractsAndInvoices()
    {
        // ربط العناصر الموجودة مع الكميات في العقود
        $contractQuantities = ContractQuantity::all();
        $items = Item::all();
        
        foreach ($contractQuantities as $quantity) {
            // البحث عن عنصر مطابق لوصف الكمية
            $matchingItem = $items->first(function ($item) use ($quantity) {
                return strpos($quantity->item_description, $item->title) !== false ||
                       strpos($item->title, substr($quantity->item_description, 0, 10)) !== false;
            });
            
            if ($matchingItem) {
                // تحديث وصف الكمية ليتطابق مع اسم العنصر
                $quantity->update([
                    'item_description' => $matchingItem->title,
                    'unit_price' => $matchingItem->price
                ]);
            }
        }
        
        // تحديث المبالغ الإجمالية للعروض والفواتير
        $estimates = EstimatesInvoice::all();
        foreach ($estimates as $estimate) {
            $total = 0;
            foreach ($estimate->items as $item) {
                $total += $item->pivot->amount;
            }
            
            $taxAmount = $total * 0.15; // 15% ضريبة
            $finalTotal = $total + $taxAmount;
            
            $estimate->update([
                'total' => $total,
                'tax_amount' => $taxAmount,
                'final_total' => $finalTotal
            ]);
        }
    }
}