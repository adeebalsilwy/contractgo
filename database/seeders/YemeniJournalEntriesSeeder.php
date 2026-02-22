<?php

namespace Database\Seeders;

use App\Models\JournalEntry;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Database\Seeder;

class YemeniJournalEntriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get related models
        $contracts = Contract::all();
        $users = User::all();

        // Define diverse Yemeni journal entries data
        $journalEntries = [
            [
                'entry_number' => 'JE-2026-001',
                'entry_type' => 'project_revenue',
                'account_code' => 'REV-001',
                'account_name' => 'الذمم المدينة',
                'reference_number' => 'REC-001',
                'credit_amount' => 735000.00,
                'debit_amount' => 0.00,
                'description' => 'قيد أولي لمستخلص مشروع توسعة طريق صنعاء - تعز',
                'status' => 'posted',
            ],
            [
                'entry_number' => 'JE-2026-002',
                'entry_type' => 'project_revenue',
                'account_code' => 'REV-002',
                'account_name' => 'الإيرادات المؤجلة',
                'reference_number' => 'REC-002',
                'credit_amount' => 500000.00,
                'debit_amount' => 0.00,
                'description' => 'قيد لمستخلص مشروع صيانة جسور محافظة حضرموت',
                'status' => 'posted',
            ],
            [
                'entry_number' => 'JE-2026-003',
                'entry_type' => 'project_revenue',
                'account_code' => 'REV-003',
                'account_name' => 'الذمم المدينة',
                'reference_number' => 'REC-003',
                'credit_amount' => 350000.00,
                'debit_amount' => 0.00,
                'description' => 'قيد لمستخلص مشروع صيانة شبكة الكهرباء - عدن',
                'status' => 'pending',
            ],
            [
                'entry_number' => 'JE-2026-004',
                'entry_type' => 'project_revenue',
                'account_code' => 'REV-004',
                'account_name' => 'الإيرادات المؤجلة',
                'reference_number' => 'REC-004',
                'credit_amount' => 1200000.00,
                'debit_amount' => 0.00,
                'description' => 'قيد لمستخلص مشروع تطوير ميناء الحديدة',
                'status' => 'posted',
            ],
            [
                'entry_number' => 'JE-2026-005',
                'entry_type' => 'project_revenue',
                'account_code' => 'REV-005',
                'account_name' => 'الذمم المدينة',
                'reference_number' => 'REC-005',
                'credit_amount' => 850000.00,
                'debit_amount' => 0.00,
                'description' => 'قيد لمستخلص مشروع توسعة مطار صنعاء',
                'status' => 'pending',
            ],
            [
                'entry_number' => 'JE-2026-006',
                'entry_type' => 'project_revenue',
                'account_code' => 'REV-006',
                'account_name' => 'الإيرادات المؤجلة',
                'reference_number' => 'REC-006',
                'credit_amount' => 950000.00,
                'debit_amount' => 0.00,
                'description' => 'قيد لمستخلص مشروع تطوير ميناء عدن',
                'status' => 'posted',
            ],
            [
                'entry_number' => 'JE-2026-007',
                'entry_type' => 'project_revenue',
                'account_code' => 'REV-007',
                'account_name' => 'الذمم المدينة',
                'reference_number' => 'REC-007',
                'credit_amount' => 450000.00,
                'debit_amount' => 0.00,
                'description' => 'قيد لمستخلص مشروع توسعة مستشفى الجمهورية - صنعاء',
                'status' => 'pending',
            ],
            [
                'entry_number' => 'JE-2026-008',
                'entry_type' => 'project_revenue',
                'account_code' => 'REV-008',
                'account_name' => 'الإيرادات المؤجلة',
                'reference_number' => 'REC-008',
                'credit_amount' => 600000.00,
                'debit_amount' => 0.00,
                'description' => 'قيد لمستخلص مشروع تطوير مجمع تعليمي - تعز',
                'status' => 'posted',
            ],
            [
                'entry_number' => 'JE-2026-009',
                'entry_type' => 'project_revenue',
                'account_code' => 'REV-009',
                'account_name' => 'الذمم المدينة',
                'reference_number' => 'REC-009',
                'credit_amount' => 400000.00,
                'debit_amount' => 0.00,
                'description' => 'قيد لمستخلص مشروع تطوير مطار المكلا',
                'status' => 'pending',
            ],
            [
                'entry_number' => 'JE-2026-010',
                'entry_type' => 'project_revenue',
                'account_code' => 'REV-010',
                'account_name' => 'الإيرادات المؤجلة',
                'reference_number' => 'REC-010',
                'credit_amount' => 300000.00,
                'debit_amount' => 0.00,
                'description' => 'قيد لمستخلص مشروع تطوير ميناء المخا',
                'status' => 'posted',
            ],
        ];

        foreach ($journalEntries as $entryData) {
            JournalEntry::create([
                'contract_id' => $contracts->random()->id,
                'entry_number' => $entryData['entry_number'],
                'entry_type' => $entryData['entry_type'],
                'account_code' => $entryData['account_code'],
                'account_name' => $entryData['account_name'],
                'reference_number' => $entryData['reference_number'],
                'credit_amount' => $entryData['credit_amount'],
                'debit_amount' => $entryData['debit_amount'],
                'description' => $entryData['description'],
                'entry_date' => now()->subDays(rand(1, 30)),
                'status' => $entryData['status'],
                'created_by' => $users->random()->id,
            ]);
        }
    }
}