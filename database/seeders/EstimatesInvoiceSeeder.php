<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstimatesInvoice;
use App\Models\Contract;
use App\Models\Client;
use App\Models\Workspace;
use App\Models\User;
use Illuminate\Support\Carbon;

class EstimatesInvoiceSeeder extends Seeder
{
    public function run()
    {
        // Ensure related data exists
        $this->call(ContractAmendmentSeeder::class);
        
        $contracts = Contract::all();
        $clients = Client::all();
        $workspaces = Workspace::all();
        $users = User::all();
        
        if ($contracts->isEmpty() || $clients->isEmpty() || $workspaces->isEmpty() || $users->isEmpty()) {
            $this->command->error('Missing required data. Please ensure contracts, clients, workspaces, and users exist.');
            return;
        }
        
        // Sample estimates and invoices data in Arabic Yemenي style
        $estimatesInvoices = [];
        
        foreach ($contracts as $contract) {
            // According to requirement: one extract (invoice) per contract
            $client = $clients->random();
            
            $estimatesInvoices[] = [
                'workspace_id' => $workspaces->random()->id,
                'client_id' => $client->id,
                'name' => $client->first_name . ' ' . $client->last_name,
                'address' => $client->address,
                'city' => $client->city,
                'state' => $client->state ?? 'صنعاء',
                'country' => $client->country ?? 'اليمن',
                'zip_code' => $client->zip ?? '0111',
                'phone' => $client->phone,
                'type' => 'invoice', // This represents the extract (المسـتـخـلـصـات)
                'status' => ['draft', 'sent', 'paid', 'partial', 'overdue'][rand(0, 4)],
                'note' => 'فاتورة مستخلص لمشروع ' . $contract->title,
                'personal_note' => 'مستخلص العمل المنجز حتى تاريخه',
                'from_date' => Carbon::parse($contract->start_date), // Fixed: parse string date
                'to_date' => Carbon::parse($contract->start_date)->addMonths(rand(1, 6)), // Fixed: parse string date
                'total' => $contract->value * (rand(10, 80) / 100), // 10-80% of contract value
                'tax_amount' => $contract->value * (rand(1, 5) / 100), // 1-5% tax
                'final_total' => $contract->value * (rand(10, 80) / 100) + $contract->value * (rand(1, 5) / 100),
                'created_by' => $users->random()->id,
                'contract_id' => $contract->id
            ];
        }
        
        foreach ($estimatesInvoices as $eiData) {
            EstimatesInvoice::firstOrCreate(
                [
                    'contract_id' => $eiData['contract_id'],
                    'type' => 'invoice' // This ensures we have one extract per contract
                ],
                $eiData
            );
        }
    }
}