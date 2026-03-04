<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContractObligation;
use App\Models\Contract;
use App\Models\User;

class ContractObligationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get contracts from workspace 1 and users
        $contracts = Contract::where('workspace_id', 1)->get();
        $users = User::all();
        
        if ($contracts->isNotEmpty() && $users->isNotEmpty()) {
            // Create obligations for each contract
            foreach ($contracts as $contract) {
                // Create 1-2 obligations per contract
                $numObligations = rand(1, 2);
                
                for ($i = 0; $i < $numObligations; $i++) {
                    $user = $users->random();
                    
                    ContractObligation::create([
                        'contract_id' => $contract->id,
                        'party_id' => $user->id,
                        'party_type' => 'client',
                        'title' => 'الالتزام ' . ($i + 1) . ' - ' . $contract->title,
                        'description' => 'وصف التزام ' . ($i + 1) . ' لعقد ' . $contract->title . '. يتضمن متطلبات التزام محددة حسب نوع الالتزام.',
                        'obligation_type' => ['payment', 'delivery', 'performance', 'compliance', 'reporting'][rand(0, 4)],
                        'priority' => ['low', 'medium', 'high', 'critical'][rand(0, 3)],
                        'status' => ['pending', 'in_progress', 'completed', 'overdue', 'cancelled'][rand(0, 4)],
                        'due_date' => now()->addDays(rand(30, 180)),
                        'assigned_to' => $user->id,
                        'compliance_status' => ['compliant', 'non_compliant', 'partially_compliant'][rand(0, 2)],
                        'notes' => 'ملاحظات حول تنفيذ الالتزام',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            
            $this->command->info($contracts->count() . ' contracts processed with obligations.');
        } else {
            $this->command->info('No contracts or users found to create obligations.');
        }
    }
}