<?php

namespace Database\Seeders;

use App\Models\EstimatesInvoice;
use App\Models\Contract;
use App\Models\Client;
use App\Models\Workspace;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class YemeniExtractsSeeder extends Seeder
{
    public function run()
    {
        // Get related models
        $contracts = Contract::where('workspace_id', 1)->get();
        $clients = Client::all();
        $workspaces = collect([Workspace::find(1)]); // Use only workspace 1
        $users = User::all();

        if ($contracts->isEmpty() || $clients->isEmpty() || $workspaces->isEmpty() || $users->isEmpty()) {
            $this->command->error('Missing required data. Please ensure contracts, clients, workspaces, and users exist.');
            return;
        }

        // Create extracts (estimates_invoices) for each contract
        foreach ($contracts as $contract) {
            // Check if extracts already exist for this contract
            if ($contract->estimatesInvoices()->count() > 0) {
                continue; // Skip if extracts already exist
            }

            // Create an extract for the contract
            $client = $contract->client ?: $clients->random();
            $workspace = $workspaces->first();
            $user = $users->random();

            // Create extract (invoice) for the contract
            $extract = EstimatesInvoice::create([
                'workspace_id' => $workspace->id,
                'client_id' => $client->id,
                'name' => $client->first_name . ' ' . $client->last_name,
                'address' => $client->address ?: 'N/A',
                'city' => $client->city ?: 'صنعاء',
                'state' => $client->state ?: 'صنعاء',
                'country' => $client->country ?: 'اليمن',
                'zip_code' => $client->zip ?: '0111',
                'phone' => $client->phone ?: '123456789',
                'type' => 'invoice', // This represents the extract (المسـتـخـلـصـات)
                'status' => 'paid', // Set status to paid as per extract nature
                'note' => 'فاتورة مستخلص لمشروع ' . $contract->title,
                'personal_note' => 'مستخلص العمل المنجز حتى تاريخه',
                'from_date' => Carbon::parse($contract->start_date),
                'to_date' => Carbon::parse($contract->start_date)->addMonths(1),
                'total' => $contract->value * 0.1, // 10% of contract value for first extract
                'tax_amount' => $contract->value * 0.01, // 1% tax
                'final_total' => ($contract->value * 0.1) + ($contract->value * 0.01),
                'created_by' => $user->id,
                'contract_id' => $contract->id
            ]);

            // Create additional extracts to reach 100% of contract value
            $remainingValue = $contract->value - $extract->final_total;
            $extractCount = 1;
            $maxExtracts = 5; // Limit to 5 extracts max
            
            while ($remainingValue > 0 && $extractCount < $maxExtracts) {
                $extractValue = min($remainingValue, $contract->value * 0.2); // Max 20% per extract
                $extractValue = max($extractValue, $contract->value * 0.05); // Min 5% per extract
                
                EstimatesInvoice::create([
                    'workspace_id' => $workspace->id,
                    'client_id' => $client->id,
                    'name' => $client->first_name . ' ' . $client->last_name . ' - مستخلص ' . ($extractCount + 1),
                    'address' => $client->address ?: 'N/A',
                    'city' => $client->city ?: 'صنعاء',
                    'state' => $client->state ?: 'صنعاء',
                    'country' => $client->country ?: 'اليمن',
                    'zip_code' => $client->zip ?: '0111',
                    'phone' => $client->phone ?: '123456789',
                    'type' => 'invoice',
                    'status' => 'paid',
                    'note' => 'فاتورة مستخلص ' . ($extractCount + 1) . ' لمشروع ' . $contract->title,
                    'personal_note' => 'مستخلص العمل المنجز حتى تاريخه - ' . ($extractCount + 1),
                    'from_date' => Carbon::parse($contract->start_date)->addMonths($extractCount),
                    'to_date' => Carbon::parse($contract->start_date)->addMonths($extractCount + 1),
                    'total' => $extractValue,
                    'tax_amount' => $extractValue * 0.1, // 10% tax
                    'final_total' => $extractValue + ($extractValue * 0.1),
                    'created_by' => $user->id,
                    'contract_id' => $contract->id
                ]);
                
                $remainingValue -= ($extractValue + ($extractValue * 0.1));
                $extractCount++;
            }
        }
        
        $this->command->info('Yemeni Extracts Seeder completed successfully!');
    }
}