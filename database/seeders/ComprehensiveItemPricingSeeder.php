<?php

namespace Database\Seeders;

use App\Models\ItemPricing;
use App\Models\ContractQuantity;
use App\Models\Item;
use App\Models\Unit;
use App\Models\User;
use App\Models\Contract;
use App\Models\EstimatesInvoice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComprehensiveItemPricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting comprehensive item pricing seeding...');
        
        // Ensure prerequisites exist
        $this->ensurePrerequisites();
        
        // Clear existing data to avoid conflicts
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ItemPricing::truncate();
        ContractQuantity::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $items = Item::all();
        $units = Unit::all();
        $users = User::all();
        $contracts = Contract::all();
        
        if ($items->isEmpty() || $units->isEmpty() || $users->isEmpty() || $contracts->isEmpty()) {
            $this->command->error('Prerequisites not met. Please ensure items, units, users, and contracts exist. Running ensurePrerequisites again...');
            $this->ensurePrerequisites();
            
            // Refresh collections after ensuring prerequisites
            $items = Item::all();
            $units = Unit::all();
            $users = User::all();
            $contracts = Contract::all();
            
            // Check again after ensuring prerequisites
            if ($items->isEmpty() || $units->isEmpty() || $users->isEmpty() || $contracts->isEmpty()) {
                $this->command->error('Failed to create prerequisites. Cannot continue seeding.');
                return;
            }
        }
        
        $this->seedItemPricing($items, $units, $users);
        $this->seedContractQuantities($contracts, $users, $items);
        
        $this->command->info('Comprehensive item pricing seeding completed successfully!');
    }
    
    /**
     * Ensure all prerequisite data exists
     */
    private function ensurePrerequisites(): void
    {
        if (Item::count() == 0) {
            $this->createSampleItems();
        }
        
        if (Unit::count() == 0) {
            $this->createSampleUnits();
        }
        
        if (User::count() == 0) {
            $this->createSampleUsers();
        }
        
        if (Contract::count() == 0) {
            $this->createSampleContracts();
        }
    }
    
    /**
     * Create sample items
     */
    private function createSampleItems(): void
    {
        $this->command->info('Creating sample items...');
        
        $items = [
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'Premium Concrete Mix',
                'price' => 120.00,
                'description' => 'High-strength concrete mix for construction projects'
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'Steel Rebar',
                'price' => 85.00,
                'description' => 'Reinforcing steel bars for concrete reinforcement'
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'Electrical Wiring',
                'price' => 250.00,
                'description' => 'Insulated copper wiring for electrical installations'
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'Cement Bags',
                'price' => 60.00,
                'description' => 'Portland cement in 50kg bags'
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'Timber Planks',
                'price' => 45.00,
                'description' => 'Quality timber planks for construction'
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'Paint Gallons',
                'price' => 75.00,
                'description' => 'Premium interior/exterior paint'
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'Insulation Material',
                'price' => 35.00,
                'description' => 'Thermal insulation for buildings'
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'PVC Pipes',
                'price' => 25.00,
                'description' => 'Plumbing pipes for water systems'
            ],
        ];
        
        foreach ($items as $item) {
            Item::create($item);
        }
    }
    
    /**
     * Create sample units
     */
    private function createSampleUnits(): void
    {
        $this->command->info('Creating sample units...');
        
        $units = [
            ['workspace_id' => 1, 'title' => 'Piece', 'description' => 'Individual piece'],
            ['workspace_id' => 1, 'title' => 'Box', 'description' => 'Packaged box'],
            ['workspace_id' => 1, 'title' => 'Kilogram', 'description' => 'Weight measurement'],
            ['workspace_id' => 1, 'title' => 'Meter', 'description' => 'Length measurement'],
            ['workspace_id' => 1, 'title' => 'Liter', 'description' => 'Volume measurement'],
            ['workspace_id' => 1, 'title' => 'Ton', 'description' => 'Weight measurement'],
            ['workspace_id' => 1, 'title' => 'Gallon', 'description' => 'Liquid volume'],
            ['workspace_id' => 1, 'title' => 'Bag', 'description' => 'Packaging unit'],
        ];
        
        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
    
    /**
     * Create sample users
     */
    private function createSampleUsers(): void
    {
        $this->command->info('Creating sample users...');
        
        $users = [
            [
                'workspace_id' => 1,
                'first_name' => 'Ahmed',
                'last_name' => 'Ali',
                'email' => 'ahmed.ali@example.com',
                'password' => bcrypt('password'),
                'phone' => '+967771234567',
                'country' => 'Yemen',
                'email_verified_at' => now(),
                'default_workspace_id' => 1
            ],
            [
                'workspace_id' => 1,
                'first_name' => 'Fatima',
                'last_name' => 'Al-Saqqaf',
                'email' => 'fatima@example.com',
                'password' => bcrypt('password'),
                'phone' => '+967777654321',
                'country' => 'Yemen',
                'email_verified_at' => now(),
                'default_workspace_id' => 1
            ],
            [
                'workspace_id' => 1,
                'first_name' => 'Mohammed',
                'last_name' => 'Al-Hamdani',
                'email' => 'mohammed@example.com',
                'password' => bcrypt('password'),
                'phone' => '+967771112223',
                'country' => 'Yemen',
                'email_verified_at' => now(),
                'default_workspace_id' => 1
            ]
        ];
        
        foreach ($users as $user) {
            $newUser = User::create($user);
            
            // Assign admin role if it exists
            $role = \Spatie\Permission\Models\Role::where('name', 'admin')->first();
            if ($role) {
                $newUser->assignRole($role);
            }
        }
    }
    
    /**
     * Create sample contracts
     */
    private function createSampleContracts(): void
    {
        $this->command->info('Creating sample contracts...');
        
        $contracts = [
            [
                'workspace_id' => 1,
                'title' => 'Residential Building Construction',
                'value' => 5000000.00,
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(10),
                'client_id' => 1,
                'project_id' => 1,
                'contract_type_id' => 1,
                'description' => 'Construction of residential building with 5 floors',
                'created_by' => 'u_1'
            ],
            [
                'workspace_id' => 1,
                'title' => 'Office Complex Renovation',
                'value' => 2500000.00,
                'start_date' => now()->subMonth(),
                'end_date' => now()->addMonths(6),
                'client_id' => 2,
                'project_id' => 2,
                'contract_type_id' => 2,
                'description' => 'Renovation of office complex interior spaces',
                'created_by' => 'u_1'
            ],
            [
                'workspace_id' => 1,
                'title' => 'Road Infrastructure Project',
                'value' => 10000000.00,
                'start_date' => now()->subWeeks(2),
                'end_date' => now()->addYear(),
                'client_id' => 3,
                'project_id' => 3,
                'contract_type_id' => 3,
                'description' => 'Construction of main road infrastructure',
                'created_by' => 'u_1'
            ]
        ];
        
        foreach ($contracts as $contract) {
            Contract::create($contract);
        }
    }
    
    /**
     * Seed item pricing records
     */
    private function seedItemPricing($items, $units, $users): void
    {
        $this->command->info('Seeding item pricing records...');
        
        $pricingRecords = [
            // Premium Concrete Mix pricing
            [
                'item_id' => $items->first()?->id ?? $items->first()->id,
                'unit_id' => $units->first()?->id ?? $units->first()->id,
                'price' => 120.00,
                'cost_price' => 95.00,
                'min_selling_price' => 110.00,
                'max_selling_price' => 140.00,
                'pricing_tiers' => [
                    ['min_quantity' => 1, 'price' => 120.00],
                    ['min_quantity' => 10, 'price' => 115.00],
                    ['min_quantity' => 50, 'price' => 110.00],
                    ['min_quantity' => 100, 'price' => 105.00],
                ],
                'discounts' => [
                    ['type' => 'percentage', 'value' => 5, 'min_quantity' => 25],
                    ['type' => 'fixed', 'value' => 10, 'min_quantity' => 75],
                ],
                'taxes' => [
                    ['name' => 'VAT', 'rate' => 15, 'min_quantity' => 1],
                ],
                'is_active' => true,
                'valid_from' => now()->subDays(7),
                'valid_until' => now()->addMonths(6),
                'created_by' => $users->first()?->id ?? $users->first()->id,
            ],
            // Steel Rebar pricing
            [
                'item_id' => $items->skip(1)->first()?->id ?? $items->first()->id,
                'unit_id' => $units->first()?->id ?? $units->first()->id,
                'price' => 85.00,
                'cost_price' => 65.00,
                'min_selling_price' => 75.00,
                'max_selling_price' => 100.00,
                'pricing_tiers' => [
                    ['min_quantity' => 1, 'price' => 85.00],
                    ['min_quantity' => 20, 'price' => 82.00],
                    ['min_quantity' => 100, 'price' => 78.00],
                    ['min_quantity' => 200, 'price' => 75.00],
                ],
                'discounts' => [
                    ['type' => 'percentage', 'value' => 3, 'min_quantity' => 50],
                ],
                'taxes' => [
                    ['name' => 'Sales Tax', 'rate' => 8, 'min_quantity' => 1],
                ],
                'is_active' => true,
                'valid_from' => now()->subDays(3),
                'valid_until' => now()->addMonths(12),
                'created_by' => $users->first()?->id ?? $users->first()->id,
            ],
            // Electrical Wiring pricing
            [
                'item_id' => $items->skip(2)->first()?->id ?? $items->first()->id,
                'unit_id' => $units->first()?->id ?? $units->first()->id,
                'price' => 250.00,
                'cost_price' => 200.00,
                'min_selling_price' => 230.00,
                'max_selling_price' => 280.00,
                'pricing_tiers' => [
                    ['min_quantity' => 1, 'price' => 250.00],
                    ['min_quantity' => 5, 'price' => 240.00],
                    ['min_quantity' => 25, 'price' => 230.00],
                ],
                'discounts' => [],
                'taxes' => [
                    ['name' => 'VAT', 'rate' => 15, 'min_quantity' => 1],
                    ['name' => 'Luxury Tax', 'rate' => 5, 'min_quantity' => 10],
                ],
                'is_active' => true,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(3),
                'created_by' => $users->skip(1)->first()?->id ?? $users->first()->id,
            ],
            // Cement Bags pricing
            [
                'item_id' => $items->skip(3)->first()?->id ?? $items->first()->id,
                'unit_id' => $units->first()?->id ?? $units->first()->id,
                'price' => 60.00,
                'cost_price' => 45.00,
                'min_selling_price' => 55.00,
                'max_selling_price' => 70.00,
                'pricing_tiers' => [
                    ['min_quantity' => 1, 'price' => 60.00],
                    ['min_quantity' => 50, 'price' => 58.00],
                    ['min_quantity' => 200, 'price' => 55.00],
                    ['min_quantity' => 500, 'price' => 52.00],
                ],
                'discounts' => [
                    ['type' => 'percentage', 'value' => 10, 'min_quantity' => 100],
                ],
                'taxes' => [
                    ['name' => 'Excise Tax', 'rate' => 12, 'min_quantity' => 1],
                ],
                'is_active' => true,
                'valid_from' => now()->subDays(15),
                'valid_until' => now()->addMonths(6),
                'created_by' => $users->first()?->id ?? $users->first()->id,
            ],
            // Timber Planks pricing
            [
                'item_id' => $items->skip(4)->first()?->id ?? $items->first()->id,
                'unit_id' => $units->first()?->id ?? $units->first()->id,
                'price' => 45.00,
                'cost_price' => 35.00,
                'min_selling_price' => 40.00,
                'max_selling_price' => 55.00,
                'pricing_tiers' => [
                    ['min_quantity' => 1, 'price' => 45.00],
                    ['min_quantity' => 30, 'price' => 43.00],
                    ['min_quantity' => 100, 'price' => 40.00],
                    ['min_quantity' => 250, 'price' => 38.00],
                ],
                'discounts' => [
                    ['type' => 'percentage', 'value' => 7, 'min_quantity' => 150],
                    ['type' => 'fixed', 'value' => 5, 'min_quantity' => 300],
                ],
                'taxes' => [
                    ['name' => 'VAT', 'rate' => 15, 'min_quantity' => 1],
                ],
                'is_active' => true,
                'valid_from' => now()->subDays(5),
                'valid_until' => now()->addMonths(4),
                'created_by' => $users->skip(2)->first()?->id ?? $users->first()->id,
            ],
            // Inactive pricing for testing
            [
                'item_id' => $items->first()?->id ?? $items->first()->id,
                'unit_id' => $units->first()?->id ?? $units->first()->id,
                'price' => 110.00,
                'cost_price' => 90.00,
                'min_selling_price' => 100.00,
                'max_selling_price' => 130.00,
                'pricing_tiers' => [
                    ['min_quantity' => 1, 'price' => 110.00],
                    ['min_quantity' => 10, 'price' => 105.00],
                ],
                'discounts' => [],
                'taxes' => [
                    ['name' => 'VAT', 'rate' => 15, 'min_quantity' => 1],
                ],
                'is_active' => false, // Inactive for testing
                'valid_from' => now()->subDays(60),
                'valid_until' => now()->subDays(10),
                'created_by' => $users->first()?->id ?? $users->first()->id,
            ]
        ];
        
        foreach ($pricingRecords as $record) {
            ItemPricing::create($record);
        }
    }
    
    /**
     * Seed contract quantity records that connect with item pricing
     */
    private function seedContractQuantities($contracts, $users, $items): void
    {
        $this->command->info('Seeding contract quantity records...');
        
        $quantityRecords = [
            // Residential Building - Concrete
            [
                'contract_id' => $contracts->first()->id,
                'user_id' => $users->first()->id,
                'item_description' => $items->first()?->title ?? 'Default Item', // Premium Concrete Mix
                'requested_quantity' => 500.00,
                'approved_quantity' => 480.00,
                'unit' => 'cubic meter',
                'unit_price' => 120.00,
                'total_amount' => 57600.00,
                'notes' => 'High-grade concrete for foundation and structural elements',
                'supporting_documents' => json_encode(['specifications.pdf', 'quality_certificate.pdf']),
                'status' => 'approved',
                'submitted_at' => now()->subDays(10),
                'approved_rejected_at' => now()->subDays(5),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'Approved with 4% tolerance',
                'quantity_approval_signature' => 'signature_1.png'
            ],
            // Residential Building - Steel Rebar
            [
                'contract_id' => $contracts->first()->id,
                'user_id' => $users->first()->id,
                'item_description' => $items->skip(1)->first()?->title ?? $items->first()?->title ?? 'Default Item', // Steel Rebar
                'requested_quantity' => 250.00,
                'approved_quantity' => 245.00,
                'unit' => 'ton',
                'unit_price' => 85.00,
                'total_amount' => 20825.00,
                'notes' => 'Grade 40 steel rebar for structural reinforcement',
                'supporting_documents' => json_encode(['grade_certificate.pdf']),
                'status' => 'approved',
                'submitted_at' => now()->subDays(8),
                'approved_rejected_at' => now()->subDays(3),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'Approved with minor adjustment',
                'quantity_approval_signature' => 'signature_2.png'
            ],
            // Office Renovation - Paint
            [
                'contract_id' => $contracts->skip(1)->first()->id,
                'user_id' => $users->skip(1)->first()->id,
                'item_description' => $items->skip(5)->first()?->title ?? $items->first()?->title ?? 'Default Item', // Paint Gallons
                'requested_quantity' => 200.00,
                'approved_quantity' => 190.00,
                'unit' => 'gallon',
                'unit_price' => 75.00,
                'total_amount' => 14250.00,
                'notes' => 'Premium interior/exterior paint for office renovation',
                'supporting_documents' => json_encode(['color_samples.pdf']),
                'status' => 'approved',
                'submitted_at' => now()->subDays(7),
                'approved_rejected_at' => now()->subDays(2),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'Approved with 5% reduction for quality check',
                'quantity_approval_signature' => 'signature_3.png'
            ],
            // Road Project - Insulation
            [
                'contract_id' => $contracts->skip(2)->first()->id,
                'user_id' => $users->skip(2)->first()->id,
                'item_description' => $items->skip(6)->first()?->title ?? $items->first()?->title ?? 'Default Item', // Insulation Material
                'requested_quantity' => 1000.00,
                'approved_quantity' => null, // Pending approval
                'unit' => 'square meter',
                'unit_price' => 35.00,
                'total_amount' => null,
                'notes' => 'Thermal insulation for road infrastructure project',
                'supporting_documents' => json_encode(['technical_specs.pdf']),
                'status' => 'pending',
                'submitted_at' => now()->subDay(),
                'approved_rejected_at' => null,
                'approved_rejected_by' => null,
                'approval_rejection_notes' => null,
                'quantity_approval_signature' => null
            ],
            // Road Project - PVC Pipes
            [
                'contract_id' => $contracts->skip(2)->first()->id,
                'user_id' => $users->first()->id,
                'item_description' => $items->skip(7)->first()?->title ?? $items->first()?->title ?? 'Default Item', // PVC Pipes
                'requested_quantity' => 5000.00,
                'approved_quantity' => 4800.00,
                'unit' => 'meter',
                'unit_price' => 25.00,
                'total_amount' => 120000.00,
                'notes' => 'Water drainage pipes for road infrastructure',
                'supporting_documents' => json_encode(['pipe_specs.pdf', 'installation_guide.pdf']),
                'status' => 'approved',
                'submitted_at' => now()->subDays(12),
                'approved_rejected_at' => now()->subDays(8),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'Fully approved',
                'quantity_approval_signature' => 'signature_4.png'
            ],
            // Previous pricing example - rejected
            [
                'contract_id' => $contracts->skip(1)->first()->id,
                'user_id' => $users->skip(1)->first()->id,
                'item_description' => $items->skip(2)->first()?->title ?? $items->first()?->title ?? 'Default Item', // Electrical Wiring
                'requested_quantity' => 100.00,
                'approved_quantity' => 0.00, // Rejected
                'unit' => 'roll',
                'unit_price' => 250.00,
                'total_amount' => 0.00,
                'notes' => 'Electrical wiring did not meet safety standards',
                'supporting_documents' => json_encode(['inspection_report.pdf']),
                'status' => 'rejected',
                'submitted_at' => now()->subDays(15),
                'approved_rejected_at' => now()->subDays(10),
                'approved_rejected_by' => $users->first()->id,
                'approval_rejection_notes' => 'Rejected due to safety compliance issues',
                'quantity_approval_signature' => 'signature_5.png'
            ]
        ];
        
        foreach ($quantityRecords as $record) {
            ContractQuantity::create($record);
        }
    }
}