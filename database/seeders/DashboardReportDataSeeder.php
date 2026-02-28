<?php

namespace Database\Seeders;

use App\Models\EstimatesInvoice;
use App\Models\Expense;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DashboardReportDataSeeder extends Seeder
{
    /**
     * Run the database seeds to populate sample data for dashboard reports
     */
    public function run(): void
    {
        $this->command->info('Seeding dashboard report data...');
        
        // Get the default workspace
        $workspace = \App\Models\Workspace::first();
        if (!$workspace) {
            $this->command->error('No workspace found!');
            return;
        }
        
        // Get a client for the invoices
        $client = \App\Models\Client::first();
        if (!$client) {
            $this->command->error('No client found!');
            return;
        }
        
        // Get a user for the expenses
        $user = \App\Models\User::first();
        if (!$user) {
            $this->command->error('No user found!');
            return;
        }
        
        // Get expense type
        $expenseType = DB::table('expense_types')->where('title', 'General')->first();
        if (!$expenseType) {
            $this->command->error('No expense type found!');
            return;
        }
        
        // Create sample invoices
        $invoices = [
            [
                'workspace_id' => $workspace->id,
                'client_id' => $client->id,
                'name' => 'Web Development Project',
                'address' => $client->address ?? 'Sample Address',
                'city' => $client->city ?? 'Sample City',
                'state' => $client->state ?? 'Sample State',
                'country' => $client->country ?? 'Sample Country',
                'zip_code' => $client->zip ?? '12345',
                'phone' => $client->phone ?? '+1234567890',
                'final_total' => 5000.00,
                'status' => 'fully_paid',
                'type' => 'invoice',
                'from_date' => '2026-01-15',
                'to_date' => '2026-02-15',
                'total' => 5000.00,
                'tax_amount' => 0.00,
                'created_by' => 'u_1',
            ],
            [
                'workspace_id' => $workspace->id,
                'client_id' => $client->id,
                'name' => 'Mobile App Development',
                'address' => $client->address ?? 'Sample Address',
                'city' => $client->city ?? 'Sample City',
                'state' => $client->state ?? 'Sample State',
                'country' => $client->country ?? 'Sample Country',
                'zip_code' => $client->zip ?? '12345',
                'phone' => $client->phone ?? '+1234567890',
                'final_total' => 7500.00,
                'status' => 'partially_paid',
                'type' => 'invoice',
                'from_date' => '2026-01-20',
                'to_date' => '2026-02-20',
                'total' => 7500.00,
                'tax_amount' => 0.00,
                'created_by' => 'u_1',
            ],
            [
                'workspace_id' => $workspace->id,
                'client_id' => $client->id,
                'name' => 'Website Maintenance',
                'address' => $client->address ?? 'Sample Address',
                'city' => $client->city ?? 'Sample City',
                'state' => $client->state ?? 'Sample State',
                'country' => $client->country ?? 'Sample Country',
                'zip_code' => $client->zip ?? '12345',
                'phone' => $client->phone ?? '+1234567890',
                'final_total' => 1200.00,
                'status' => 'fully_paid',
                'type' => 'invoice',
                'from_date' => '2026-02-01',
                'to_date' => '2026-02-28',
                'total' => 1200.00,
                'tax_amount' => 0.00,
                'created_by' => 'u_1',
            ]
        ];
        
        foreach ($invoices as $invoiceData) {
            if (!EstimatesInvoice::where('name', $invoiceData['name'])->where('workspace_id', $invoiceData['workspace_id'])->exists()) {
                EstimatesInvoice::create($invoiceData);
            }
        }
        
        // Create sample expenses
        $expenses = [
            [
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
                'expense_type_id' => $expenseType->id,
                'title' => 'Office Rent',
                'amount' => 2000.00,
                'expense_date' => '2026-02-01',
                'created_by' => 'u_' . $user->id,
            ],
            [
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
                'expense_type_id' => $expenseType->id,
                'title' => 'Software Licenses',
                'amount' => 500.00,
                'expense_date' => '2026-02-10',
                'created_by' => 'u_' . $user->id,
            ],
            [
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
                'expense_type_id' => $expenseType->id,
                'title' => 'Internet Bill',
                'amount' => 150.00,
                'expense_date' => '2026-02-15',
                'created_by' => 'u_' . $user->id,
            ],
            [
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
                'expense_type_id' => $expenseType->id,
                'title' => 'Team Lunch',
                'amount' => 300.00,
                'expense_date' => '2026-02-20',
                'created_by' => 'u_' . $user->id,
            ]
        ];
        
        foreach ($expenses as $expenseData) {
            if (!Expense::where('title', $expenseData['title'])->where('workspace_id', $expenseData['workspace_id'])->exists()) {
                Expense::create($expenseData);
            }
        }
        
        $this->command->info('Dashboard report data seeded successfully!');
        $this->command->info('Total income: $13,700.00');
        $this->command->info('Total expenses: $2,950.00');
        $this->command->info('Profit: $10,750.00');
    }
}