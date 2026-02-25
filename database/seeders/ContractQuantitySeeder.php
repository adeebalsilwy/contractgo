<?php

namespace Database\Seeders;

use App\Models\ContractQuantity;
use App\Models\Contract;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContractQuantitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure we have contracts and users
        if (Contract::count() == 0) {
            $this->command->info('No contracts found. Please seed contracts first.');
            return;
        }
        
        if (User::count() == 0) {
            $this->command->info('No users found. Please seed users first.');
            return;
        }
        
        if (Item::count() == 0) {
            $this->command->info('No items found. Please seed items first.');
            return;
        }
        
        // Clear existing contract quantities to avoid duplicates
        ContractQuantity::truncate();
        
        // Get sample data
        $contracts = Contract::all();
        $users = User::all();
        $items = Item::all();
        
        // Sample contract quantity data
        $contractQuantityData = [
            [
                'contract_id' => $contracts->first()->id,
                'user_id' => $users->first()->id,
                'item_description' => $items->first()->title,
                'requested_quantity' => 100.00,
                'approved_quantity' => 95.00,
                'unit' => 'piece',
                'unit_price' => 100.00,
                'total_amount' => 9500.00,
                'notes' => 'Standard quality materials as per specifications',
                'supporting_documents' => json_encode(['doc1.pdf', 'specifications.pdf']),
                'status' => 'approved',
                'submitted_at' => now()->subDays(5),
                'approved_rejected_at' => now()->subDays(2),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'Approved with minor adjustments',
                'quantity_approval_signature' => 'signature_file.png'
            ],
            [
                'contract_id' => $contracts->first()->id,
                'user_id' => $users->skip(1)->first()?->id ?? $users->first()->id,
                'item_description' => $items->skip(1)->first()?->title ?? $items->first()->title,
                'requested_quantity' => 50.00,
                'approved_quantity' => 50.00,
                'unit' => 'box',
                'unit_price' => 75.00,
                'total_amount' => 3750.00,
                'notes' => 'High-grade materials required',
                'supporting_documents' => json_encode(['material_specs.pdf']),
                'status' => 'approved',
                'submitted_at' => now()->subDays(3),
                'approved_rejected_at' => now()->subDays(1),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'Fully approved',
                'quantity_approval_signature' => 'signature_file2.png'
            ],
            [
                'contract_id' => $contracts->skip(1)->first()?->id ?? $contracts->first()->id,
                'user_id' => $users->first()->id,
                'item_description' => $items->skip(2)->first()?->title ?? $items->first()->title,
                'requested_quantity' => 25.00,
                'approved_quantity' => 20.00,
                'unit' => 'ton',
                'unit_price' => 200.00,
                'total_amount' => 4000.00,
                'notes' => 'Reduced quantity due to budget constraints',
                'supporting_documents' => json_encode([]),
                'status' => 'approved',
                'submitted_at' => now()->subDays(7),
                'approved_rejected_at' => now()->subDays(3),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'Quantity reduced from 25 to 20 tons',
                'quantity_approval_signature' => 'signature_file3.png'
            ],
            [
                'contract_id' => $contracts->skip(2)->first()?->id ?? $contracts->first()->id,
                'user_id' => $users->first()->id,
                'item_description' => $items->skip(3)->first()?->title ?? $items->first()->title,
                'requested_quantity' => 200.00,
                'approved_quantity' => null, // Pending approval
                'unit' => 'bag',
                'unit_price' => 50.00,
                'total_amount' => null, // Will be calculated when approved
                'notes' => 'Additional materials for extension work',
                'supporting_documents' => json_encode(['extension_plan.pdf']),
                'status' => 'pending',
                'submitted_at' => now()->subDays(1),
                'approved_rejected_at' => null,
                'approved_rejected_by' => null,
                'approval_rejection_notes' => null,
                'quantity_approval_signature' => null
            ],
            [
                'contract_id' => $contracts->skip(1)->first()?->id ?? $contracts->first()->id,
                'user_id' => $users->skip(1)->first()?->id ?? $users->first()->id,
                'item_description' => $items->skip(4)->first()?->title ?? $items->first()->title,
                'requested_quantity' => 150.00,
                'approved_quantity' => 140.00,
                'unit' => 'piece',
                'unit_price' => 45.00,
                'total_amount' => 6300.00,
                'notes' => 'Quality wood as per architectural plans',
                'supporting_documents' => json_encode(['wood_quality_cert.pdf']),
                'status' => 'approved',
                'submitted_at' => now()->subDays(10),
                'approved_rejected_at' => now()->subDays(5),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'Approved with 10 pieces reduction',
                'quantity_approval_signature' => 'signature_file4.png'
            ],
            [
                'contract_id' => $contracts->skip(3)->first()?->id ?? $contracts->first()->id,
                'user_id' => $users->first()->id,
                'item_description' => $items->first()->title,
                'requested_quantity' => 75.00,
                'approved_quantity' => 0.00, // Rejected
                'unit' => 'piece',
                'unit_price' => 100.00,
                'total_amount' => 0.00,
                'notes' => 'Quality does not meet project standards',
                'supporting_documents' => json_encode(['inspection_report.pdf']),
                'status' => 'rejected',
                'submitted_at' => now()->subDays(8),
                'approved_rejected_at' => now()->subDays(4),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'Rejected due to quality concerns',
                'quantity_approval_signature' => 'signature_file5.png'
            ]
        ];
        
        // Insert the contract quantity records
        foreach ($contractQuantityData as $data) {
            ContractQuantity::create($data);
        }
        
        $this->command->info('Contract quantity data seeded successfully!');
    }
}