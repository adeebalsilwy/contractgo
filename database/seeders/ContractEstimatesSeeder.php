<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contract;
use App\Models\EstimatesInvoice;
use App\Models\ContractQuantity;
use App\Models\ContractApproval;
use App\Models\ContractAmendment;
use App\Models\JournalEntry;
use App\Models\Item;
use App\Models\Unit;
use App\Models\Client;
use App\Models\User;
use App\Models\Project;
use App\Models\ContractType;
use Faker\Factory as Faker;

class ContractEstimatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create sample units if they don't exist
        $units = [
            ['title' => 'Square Meter', 'description' => 'M2'],
            ['title' => 'Linear Meter', 'description' => 'LM'],
            ['title' => 'Piece', 'description' => 'PC'],
            ['title' => 'Set', 'description' => 'SET'],
            ['title' => 'Hour', 'description' => 'HR']
        ];

        foreach ($units as $unitData) {
            Unit::firstOrCreate([
                'title' => $unitData['title']
            ], [
                'description' => $unitData['description'],
                'workspace_id' => 1
            ]);
        }

        // Create sample items if they don't exist
        $items = [
            ['title' => 'Gypsum Board Work', 'description' => 'Installation of gypsum boards including materials'],
            ['title' => 'Interior Painting', 'description' => 'Interior wall painting with premium paint'],
            ['title' => 'Floor Tiling', 'description' => 'Ceramic floor tiling with adhesive and grout'],
            ['title' => 'Electrical Installation', 'description' => 'Complete electrical installation including wiring'],
            ['title' => 'Plumbing Work', 'description' => 'Plumbing installation for water supply and drainage']
        ];

        foreach ($items as $itemData) {
            Item::firstOrCreate([
                'title' => $itemData['title']
            ], [
                'description' => $itemData['description'],
                'workspace_id' => 1,
                'unit_id' => Unit::first()->id,
                'price' => $faker->randomFloat(2, 10, 200)
            ]);
        }

        // Get existing records
        $clients = Client::limit(5)->get();
        $users = User::limit(5)->get();
        $projects = Project::limit(5)->get();
        $contractTypes = ContractType::limit(5)->get();
        
        if ($clients->isEmpty() || $users->isEmpty() || $projects->isEmpty() || $contractTypes->isEmpty()) {
            $this->command->info('Please make sure you have clients, users, projects, and contract types in the database before running this seeder.');
            return;
        }

        // Create sample contracts
        $contracts = [];
        for ($i = 0; $i < 10; $i++) {
            $startDate = $faker->dateTimeBetween('-1 year', 'now');
            $endDate = $faker->dateTimeBetween($startDate, '+1 year');
            
            $contracts[] = Contract::create([
                'workspace_id' => 1,
                'title' => $faker->sentence(6),
                'value' => $faker->randomFloat(2, 5000, 100000),
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'client_id' => $clients->random()->id,
                'project_id' => $projects->random()->id,
                'contract_type_id' => $contractTypes->random()->id,
                'description' => $faker->paragraph,
                'created_by' => 'u_' . $users->random()->id,
                'workflow_status' => $faker->randomElement([
                    'draft', 
                    'site_supervisor_upload', 
                    'quantity_approval', 
                    'management_review', 
                    'accounting_processing', 
                    'final_review', 
                    'approved', 
                    'amendment_pending', 
                    'amendment_approved', 
                    'archived'
                ]),
                'site_supervisor_id' => $users->random()->id,
                'quantity_approver_id' => $users->random()->id,
                'accountant_id' => $users->random()->id,
            ]);
        }

        // Create contract quantities for each contract
        foreach ($contracts as $contract) {
            for ($j = 0; $j < rand(3, 8); $j++) {
                $item = Item::inRandomOrder()->first();
                $unit = Unit::inRandomOrder()->first();
                
                $quantity = $faker->randomFloat(2, 100, 1000);
                $rate = $faker->randomFloat(2, 10, 200);
                
                ContractQuantity::create([
                    'contract_id' => $contract->id,
                    'item_id' => $item->id,
                    'unit_id' => $unit->id,
                    'quantity' => $quantity,
                    'rate' => $rate,
                    'amount' => $quantity * $rate,
                    'description' => $item->description,
                ]);
            }
        }

        // Create contract approvals
        foreach ($contracts as $contract) {
            for ($k = 0; $k < rand(1, 3); $k++) {
                ContractApproval::create([
                    'contract_id' => $contract->id,
                    'user_id' => $users->random()->id,
                    'role' => $faker->jobTitle,
                    'status' => $faker->randomElement(['pending', 'approved', 'rejected']),
                    'comments' => $faker->sentence,
                    'approved_at' => $faker->boolean ? $faker->dateTime : null,
                ]);
            }
        }

        // Create contract amendments
        foreach ($contracts as $contract) {
            if ($faker->boolean(30)) { // 30% chance to have amendments
                for ($l = 0; $l < rand(1, 3); $l++) {
                    ContractAmendment::create([
                        'contract_id' => $contract->id,
                        'title' => $faker->sentence(4),
                        'description' => $faker->paragraph,
                        'amendment_date' => $faker->dateTimeBetween($contract->start_date, $contract->end_date),
                        'change_order_number' => 'CO-' . rand(1000, 9999),
                        'cost_impact' => $faker->randomFloat(2, -5000, 15000), // Can be negative (reduction) or positive (increase)
                        'time_impact' => rand(-30, 60), // Days impact
                    ]);
                }
            }
        }

        // Create journal entries
        foreach ($contracts as $contract) {
            for ($m = 0; $m < rand(2, 5); $m++) {
                JournalEntry::create([
                    'contract_id' => $contract->id,
                    'entry_date' => $faker->dateTimeBetween($contract->start_date, $contract->end_date),
                    'description' => $faker->sentence(6),
                    'debit_amount' => $faker->randomFloat(2, 1000, 10000),
                    'credit_amount' => $faker->randomFloat(2, 1000, 10000),
                    'account' => $faker->word,
                    'reference' => 'REF-' . rand(1000, 9999),
                ]);
            }
        }

        // Create estimates and invoices linked to contracts
        foreach ($contracts as $contract) {
            // Create 1-3 estimates per contract
            for ($n = 0; $n < rand(1, 3); $n++) {
                $client = Client::find($contract->client_id);
                
                $estimate = EstimatesInvoice::create([
                    'workspace_id' => 1,
                    'client_id' => $contract->client_id,
                    'contract_id' => $contract->id, // Link directly to contract
                    'name' => 'Estimate for ' . $contract->title,
                    'address' => $client->address ?? $faker->address,
                    'city' => $client->city ?? $faker->city,
                    'state' => $client->state ?? $faker->state,
                    'country' => $client->country ?? $faker->country,
                    'zip_code' => $client->zip_code ?? $faker->postcode,
                    'phone' => $client->phone ?? $faker->phoneNumber,
                    'type' => 'estimate',
                    'status' => $faker->randomElement(['draft', 'sent', 'accepted', 'declined', 'expired']),
                    'from_date' => $faker->dateTimeBetween($contract->start_date, $contract->end_date)->format('Y-m-d'),
                    'to_date' => $faker->dateTimeBetween($contract->start_date, $contract->end_date)->format('Y-m-d'),
                    'total' => $faker->randomFloat(2, 5000, 50000),
                    'tax_amount' => $faker->randomFloat(2, 0, 5000),
                    'final_total' => $faker->randomFloat(2, 5000, 55000),
                    'created_by' => 'u_' . $users->random()->id,
                    'note' => 'Estimate for contract: ' . $contract->title,
                ]);

                // Attach items to the estimate
                $items = Item::inRandomOrder()->take(rand(2, 5))->get();
                foreach ($items as $item) {
                    $unit = Unit::inRandomOrder()->first();
                    $qty = $faker->randomFloat(2, 10, 200);
                    $rate = $faker->randomFloat(2, 20, 300);
                    
                    $estimate->items()->attach($item->id, [
                        'qty' => $qty,
                        'unit_id' => $unit->id,
                        'rate' => $rate,
                        'tax_id' => null,
                        'amount' => $qty * $rate,
                    ]);
                }
            }

            // Create 0-2 invoices per contract
            for ($o = 0; $o < rand(0, 2); $o++) {
                $client = Client::find($contract->client_id);
                
                $invoice = EstimatesInvoice::create([
                    'workspace_id' => 1,
                    'client_id' => $contract->client_id,
                    'contract_id' => $contract->id, // Link directly to contract
                    'name' => 'Invoice for ' . $contract->title,
                    'address' => $client->address ?? $faker->address,
                    'city' => $client->city ?? $faker->city,
                    'state' => $client->state ?? $faker->state,
                    'country' => $client->country ?? $faker->country,
                    'zip_code' => $client->zip_code ?? $faker->postcode,
                    'phone' => $client->phone ?? $faker->phoneNumber,
                    'type' => 'invoice',
                    'status' => $faker->randomElement(['draft', 'partially_paid', 'fully_paid', 'cancelled', 'due']),
                    'from_date' => $faker->dateTimeBetween($contract->start_date, $contract->end_date)->format('Y-m-d'),
                    'to_date' => $faker->dateTimeBetween($contract->start_date, $contract->end_date)->format('Y-m-d'),
                    'total' => $faker->randomFloat(2, 5000, 50000),
                    'tax_amount' => $faker->randomFloat(2, 0, 5000),
                    'final_total' => $faker->randomFloat(2, 5000, 55000),
                    'created_by' => 'u_' . $users->random()->id,
                    'note' => 'Invoice for contract: ' . $contract->title,
                ]);

                // Attach items to the invoice
                $items = Item::inRandomOrder()->take(rand(2, 5))->get();
                foreach ($items as $item) {
                    $unit = Unit::inRandomOrder()->first();
                    $qty = $faker->randomFloat(2, 10, 200);
                    $rate = $faker->randomFloat(2, 20, 300);
                    
                    $invoice->items()->attach($item->id, [
                        'qty' => $qty,
                        'unit_id' => $unit->id,
                        'rate' => $rate,
                        'tax_id' => null,
                        'amount' => $qty * $rate,
                    ]);
                }
            }
        }

        $this->command->info('Contract and related data seeded successfully!');
    }
}