<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContractQuantity;
use App\Models\Contract;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Carbon;

class ContractQuantitySeeder extends Seeder
{
    public function run()
    {
        // Ensure related data exists
        $this->call(JournalEntrySeeder::class);
        
        $contracts = Contract::all();
        $users = User::all();
        $workspaces = Workspace::all();
        
        if ($contracts->isEmpty() || $users->isEmpty() || $workspaces->isEmpty()) {
            $this->command->error('Missing required data. Please ensure contracts, users, and workspaces exist.');
            return;
        }
        
        // Sample contract quantities data in Arabic Yemenي style
        $quantities = [];
        
        foreach ($contracts as $contract) {
            // Create 3-5 quantities per contract
            $quantitiesCount = rand(3, 5);
            
            for ($i = 0; $i < $quantitiesCount; $i++) {
                $quantities[] = [
                    'contract_id' => $contract->id,
                    'user_id' => $users->random()->id,
                    'workspace_id' => $workspaces->random()->id,
                    'item_description' => [
                        'أعمال الخرسانة المسلحة',
                        'أعمال السباكة والصرف الصحي',
                        'أعمال الكهرباء العامة',
                        'أعمال التشطيبات الداخلية',
                        'أعمال السقف المعلق',
                        'أعمال الواجهات الزجاجية',
                        'أعمال الأرضيات والبلاط',
                        'أعمال الدهانات العامة',
                        'أعمال النجارة والخشب',
                        'أعمال التركيبات الصحية'
                    ][rand(0, 9)],
                    'requested_quantity' => rand(100, 1000) / 10, // Decimal quantity
                    'approved_quantity' => rand(90, 100) / 100 * (rand(100, 1000) / 10), // Approve 90-100% of requested
                    'unit' => ['متر مكعب', 'متر مربع', 'قطعة', 'طن', 'وحدة'][rand(0, 4)],
                    'unit_price' => rand(5000, 50000),
                    'total_amount' => rand(500000, 5000000),
                    'notes' => [
                        'الكمية تشمل جميع الأعمال المذكورة',
                        'يجب الالتزام بالمواصفات الفنية',
                        'الكمية قابلة للتعديل حسب الموقع',
                        'يشترط موافقة المهندس المشرف',
                        'الكمية نهائية بعد القياس الميداني'
                    ][rand(0, 4)],
                    'supporting_documents' => json_encode([
                        'document_' . rand(1, 5) . '.pdf',
                        'drawing_' . rand(1, 3) . '.dwg'
                    ]),
                    'status' => ['pending', 'approved', 'rejected', 'modified'][rand(0, 3)], // Fixed: use valid status values
                    'submitted_at' => Carbon::parse($contract->start_date)->addDays(rand(0, 60)), // Fixed: parse string date
                    'approved_rejected_at' => rand(0, 1) ? Carbon::now()->subDays(rand(0, 30)) : null,
                    'approved_rejected_by' => rand(0, 1) ? $users->random()->id : null,
                    'approval_rejection_notes' => [
                        'الكمية مقبولة وفق المواصفات',
                        'الكمية مرفوضة بسبب عدم الالتزام بالمعايير',
                        'الكمية مقبولة جزئياً',
                        'الكمية تحتاج لمراجعة فنية',
                        'الكمية مقبولة مع بعض التعديلات'
                    ][rand(0, 4)],
                    'quantity_approval_signature' => rand(0, 1) ? 'signature_' . rand(1000, 9999) . '.png' : null
                ];
            }
        }
        
        foreach ($quantities as $quantityData) {
            ContractQuantity::firstOrCreate(
                [
                    'contract_id' => $quantityData['contract_id'],
                    'item_description' => $quantityData['item_description']
                ],
                $quantityData
            );
        }
    }
}