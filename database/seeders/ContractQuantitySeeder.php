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
        
        $contracts = Contract::where('workspace_id', 1)->get();
        $users = User::all();
        $workspaces = collect([Workspace::find(1)]); // Use only workspace 1
        
        if ($contracts->isEmpty() || $users->isEmpty() || $workspaces->isEmpty()) {
            $this->command->error('Missing required data. Please ensure contracts, users, and workspaces exist.');
            return;
        }
        
        // Clear existing contract quantities
        ContractQuantity::truncate();
        
        // Sample contract quantities data in Arabic Yemenي style following the workflow scenario
        $quantities = [];
        
        foreach ($contracts as $contract) {
            // Create 3-5 quantities per contract
            $quantitiesCount = rand(3, 5);
            
            for ($i = 0; $i < $quantitiesCount; $i++) {
                // Determine status based on workflow scenario
                $status = ['pending', 'approved', 'rejected', 'modified'][rand(0, 3)];
                
                // Determine who submitted based on site supervisor assignment
                $submitter = $contract->site_supervisor_id ? 
                    User::find($contract->site_supervisor_id) : 
                    $users->random();
                
                $approver = $contract->quantity_approver_id ?
                    User::find($contract->quantity_approver_id) :
                    $users->random();
                
                $quantities[] = [
                    'contract_id' => $contract->id,
                    'user_id' => $submitter->id,
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
                    'approved_quantity' => $status !== 'rejected' ? rand(90, 100) / 100 * (rand(100, 1000) / 10) : null,
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
                        'contract_quantity_' . $contract->id . '_' . rand(1, 5) . '.pdf',
                        'drawing_' . $contract->id . '_' . rand(1, 3) . '.dwg'
                    ]),
                    'status' => $status,
                    'submitted_at' => Carbon::parse($contract->start_date)->addDays(rand(0, 60)),
                    'approved_rejected_at' => in_array($status, ['approved', 'rejected', 'modified']) ? 
                        Carbon::now()->subDays(rand(0, 30)) : null,
                    'approved_rejected_by' => in_array($status, ['approved', 'rejected', 'modified']) ? 
                        $approver->id : null,
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
        
        // Insert the contract quantities in chunks
        $chunks = array_chunk($quantities, 50);
        foreach ($chunks as $chunk) {
            ContractQuantity::insert($chunk);
        }
        
        $this->command->info(count($quantities) . ' Contract Quantities seeded successfully!');
    }
}