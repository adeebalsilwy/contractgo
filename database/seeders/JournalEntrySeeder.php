<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JournalEntry;
use App\Models\Contract;
use App\Models\User;
use App\Models\EstimatesInvoice;
use App\Models\Workspace;
use Illuminate\Support\Carbon;

class JournalEntrySeeder extends Seeder
{
    public function run()
    {
        // Ensure related data exists
        $this->call(ContractSeeder::class);
        
        $contracts = Contract::all();
        $users = User::all();
        $invoices = EstimatesInvoice::all();
        $workspaces = Workspace::all();
        
        if ($contracts->isEmpty() || $users->isEmpty() || $workspaces->isEmpty()) {
            $this->command->error('Missing required data. Please ensure contracts, users, and workspaces exist.');
            return;
        }
        
        // Sample journal entries data in Arabic Yemenي style
        $journalEntries = [];
        
        foreach ($contracts as $contract) {
            // Create 2-3 journal entries per contract
            $entriesCount = rand(2, 3);
            
            for ($i = 0; $i < $entriesCount; $i++) {
                $journalEntries[] = [
                    'contract_id' => $contract->id,
                    'invoice_id' => $invoices->isNotEmpty() ? $invoices->random()->id : null,
                    'entry_number' => 'JE-' . $contract->id . '-' . ($i + 1),
                    'entry_type' => ['revenue', 'expense', 'adjustment'][rand(0, 2)],
                    'entry_date' => Carbon::parse($contract->start_date)->addDays(rand(0, 180)), // Fixed: parse string date
                    'reference_number' => 'REF-' . rand(1000, 9999),
                    'description' => [
                        'إدخال دفتر يومية لعقد بناء فندق السلام',
                        'إدخال دفتر مصاريف مشروع تطوير مجمع تجاري',
                        'إدخال تعديل حسابي لعقد صيانة',
                        'إدخال دفتر إيرادات مشاريع البناء',
                        'إدخال دفتر حسابي لتوظيف المعدات'
                    ][rand(0, 4)],
                    'debit_amount' => rand(100000, 500000),
                    'credit_amount' => rand(100000, 500000),
                    'account_code' => 'ACC-' . rand(1000, 9999),
                    'account_name' => [
                        'حسابات العملاء',
                        'حسابات الموردين',
                        'النقد والمصاريف',
                        'الأصول الثابتة',
                        'الإيرادات'
                    ][rand(0, 4)],
                    'created_by' => $users->random()->id,
                    'status' => ['pending', 'posted', 'reversed', 'cancelled'][rand(0, 3)], // Fixed: use valid status values
                    'posted_at' => rand(0, 1) ? Carbon::now()->subDays(rand(0, 30)) : null,
                    'posted_by' => rand(0, 1) ? $users->random()->id : null,
                    'posting_notes' => [
                        'تم تسجيل الإدخال اليومي',
                        'تمت المراجعة والموافقة',
                        'الإدخال النهائي للحسابات',
                        'تعديل حسابي مطلوب',
                        'إدخال مؤقت'
                    ][rand(0, 4)],
                    'workspace_id' => $workspaces->random()->id
                ];
            }
        }
        
        foreach ($journalEntries as $journalEntryData) {
            JournalEntry::firstOrCreate(
                [
                    'entry_number' => $journalEntryData['entry_number'],
                    'contract_id' => $journalEntryData['contract_id']
                ],
                $journalEntryData
            );
        }
    }
}