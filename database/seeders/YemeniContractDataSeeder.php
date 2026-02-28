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
use App\Models\User;
use App\Models\Workspace;
use App\Models\Client;

class YemeniContractDataSeeder extends Seeder
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
                'unit' => 'متر مربع',
                'unit_price' => 25000,
                'total_amount' => 3750000000,
                'notes' => 'خرسانة مقاومة عالية الجودة',
                'status' => 'pending',
                'submitted_at' => now()->subDays(2),
                'approved_rejected_at' => null,
                'approved_rejected_by' => null,
                'approval_rejection_notes' => null
            ],
            [
                'contract_id' => 1,
                'user_id' => 2,
                'workspace_id' => 1,
                'item_description' => 'تركيب الحواجز الخرسانية والأعمدة',
                'requested_quantity' => 2000,
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
                'unit' => 'وحدة',
                'unit_price' => 800000,
                'total_amount' => 40000000,
                'notes' => 'توفير عوارض فولاذية عالية المقاومة للصدأ',
                'status' => 'pending',
                'submitted_at' => now()->subDays(3),
                'approved_rejected_at' => null,
                'approved_rejected_by' => null,
                'approval_rejection_notes' => null
            ],
            
            // كميات لمشروع المجمع السكني
            [
                'contract_id' => 3,
                'user_id' => 2,
                'workspace_id' => 1,
                'item_description' => 'حفر وتهيئة الأرض للمباني',
                'requested_quantity' => 25000,
                'unit' => 'متر مربع',
                'unit_price' => 15000,
                'total_amount' => 375000000,
                'notes' => 'تهيئة الأرض وحفر القواعد الخرسانية',
                'status' => 'pending',
                'submitted_at' => now()->subDays(1),
                'approved_rejected_at' => null,
                'approved_rejected_by' => null,
                'approval_rejection_notes' => null
            ],
            [
                'contract_id' => 3,
                'user_id' => 2,
                'workspace_id' => 1,
                'item_description' => 'صب الخرسانة المسلحة للقواعد',
                'requested_quantity' => 12000,
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
            
            // كميات لمشروع مصنع المياه
            [
                'contract_id' => 5,
                'user_id' => 2,
                'workspace_id' => 1,
                'item_description' => 'توفير الآلات والمعدات الخاصة بمعالجة المياه',
                'requested_quantity' => 15,
                'unit' => 'مجموعة معدات',
                'unit_price' => 80000000,
                'total_amount' => 1200000000,
                'notes' => 'تشمل خطوط المعالجة والأواني التخزينية',
                'status' => 'approved',
                'submitted_at' => now()->subDays(25),
                'approved_rejected_at' => now()->subDays(18),
                'approved_rejected_by' => 3,
                'approval_rejection_notes' => 'تمت الموافقة بعد فحص المعدات المعروضة'
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
                'contract_id' => 2,
                'approval_stage' => 'accounting_review',
                'approver_id' => 3,
                'status' => 'approved',
                'comments' => 'تمت المراجعة المالية والموافقة',
                'approved_rejected_at' => now()->subDays(15)
            ],
            [
                'contract_id' => 3,
                'approval_stage' => 'final_approval',
                'approver_id' => 3,
                'status' => 'approved',
                'comments' => 'تمت الموافقة النهائية على العقد',
                'approved_rejected_at' => now()->subDays(12)
            ],
            [
                'contract_id' => 4,
                'approval_stage' => 'quantity_approval',
                'approver_id' => 3,
                'status' => 'approved',
                'comments' => 'الموافقة على كميات مشروع ترميم المسجد',
                'approved_rejected_at' => now()->subDays(10)
            ],
            [
                'contract_id' => 5,
                'approval_stage' => 'management_review',
                'approver_id' => 3,
                'status' => 'pending',
                'comments' => 'قيد المراجعة الإدارية',
                'approved_rejected_at' => null
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
                'contract_id' => 2,
                'requested_by_user_id' => 2,
                'approved_by_user_id' => 3,
                'amendment_type' => 'scope_change',
                'request_reason' => 'تعديل في نطاق العمل لتشمل جسور إضافية',
                'status' => 'approved',
                'approved_at' => now()->subDays(15)
            ],
            [
                'contract_id' => 3,
                'requested_by_user_id' => 2,
                'approved_by_user_id' => 3,
                'amendment_type' => 'budget_adjustment',
                'request_reason' => 'تعديل في الميزانية بسبب ارتفاع أسعار المواد',
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
                'created_by' => 3
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
                'created_by' => 3
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
                'created_by' => 3
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
                'created_by' => 3
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
                'created_by' => 3
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
                'created_by' => 3
            ]
        ];
        
        foreach ($journalEntries as $entryData) {
            JournalEntry::create($entryData);
        }
    }
}