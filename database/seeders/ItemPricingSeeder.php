<?php

namespace Database\Seeders;

use App\Models\ItemPricing;
use App\Models\Item;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemPricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, make sure we have items and units to associate with pricing
        if (Item::count() == 0) {
            $this->command->info('No items found. Creating sample items...');
            $this->createSampleItems();
        }
        
        if (Unit::count() == 0) {
            $this->command->info('No units found. Creating sample units...');
            $this->createSampleUnits();
        }
        
        if (User::count() == 0) {
            $this->command->info('No users found. Creating sample users...');
            $this->createSampleUsers();
        }
        
        // Clear existing item pricing data to avoid duplicates
        ItemPricing::truncate();
        
        // Get sample data
        $items = Item::all();
        $units = Unit::all();
        $users = User::all();
        
        // Sample item pricing data
        $itemPricingData = [
            [
                'item_id' => $items->first()->id,
                'unit_id' => $units->first()->id,
                'price' => 100.00,
                'cost_price' => 80.00,
                'min_selling_price' => 90.00,
                'max_selling_price' => 150.00,
                'pricing_tiers' => [
                    ['min_quantity' => 1, 'price' => 100.00],
                    ['min_quantity' => 10, 'price' => 95.00],
                    ['min_quantity' => 50, 'price' => 90.00],
                ],
                'discounts' => [
                    ['type' => 'percentage', 'value' => 5, 'min_quantity' => 10],
                    ['type' => 'fixed', 'value' => 10, 'min_quantity' => 100],
                ],
                'taxes' => [
                    ['name' => 'VAT', 'rate' => 15, 'min_quantity' => 1],
                ],
                'is_active' => true,
                'valid_from' => now()->subDays(7),
                'valid_until' => now()->addMonths(6),
                'created_by' => $users->first()->id,
            ],
            [
                'item_id' => $items->skip(1)->first()?->id ?? $items->first()->id,
                'unit_id' => $units->skip(1)->first()?->id ?? $units->first()->id,
                'price' => 75.00,
                'cost_price' => 60.00,
                'min_selling_price' => 70.00,
                'max_selling_price' => 120.00,
                'pricing_tiers' => [
                    ['min_quantity' => 1, 'price' => 75.00],
                    ['min_quantity' => 20, 'price' => 70.00],
                    ['min_quantity' => 100, 'price' => 65.00],
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
                'created_by' => $users->first()->id,
            ],
            [
                'item_id' => $items->skip(2)->first()?->id ?? $items->first()->id,
                'unit_id' => $units->first()->id,
                'price' => 200.00,
                'cost_price' => 150.00,
                'min_selling_price' => 180.00,
                'max_selling_price' => 250.00,
                'pricing_tiers' => [
                    ['min_quantity' => 1, 'price' => 200.00],
                    ['min_quantity' => 5, 'price' => 190.00],
                    ['min_quantity' => 25, 'price' => 180.00],
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
            [
                'item_id' => $items->skip(3)->first()?->id ?? $items->first()->id,
                'unit_id' => $units->skip(1)->first()?->id ?? $units->first()->id,
                'price' => 50.00,
                'cost_price' => 40.00,
                'min_selling_price' => 45.00,
                'max_selling_price' => 75.00,
                'pricing_tiers' => [
                    ['min_quantity' => 1, 'price' => 50.00],
                    ['min_quantity' => 50, 'price' => 48.00],
                    ['min_quantity' => 200, 'price' => 45.00],
                ],
                'discounts' => [
                    ['type' => 'percentage', 'value' => 10, 'min_quantity' => 100],
                ],
                'taxes' => [
                    ['name' => 'Service Tax', 'rate' => 12, 'min_quantity' => 1],
                ],
                'is_active' => false, // Inactive pricing for testing
                'valid_from' => now()->subDays(30),
                'valid_until' => now()->subDays(1),
                'created_by' => $users->first()->id,
            ],
            [
                'item_id' => $items->skip(4)->first()?->id ?? $items->first()->id,
                'unit_id' => $units->first()->id,
                'price' => 150.00,
                'cost_price' => 120.00,
                'min_selling_price' => 135.00,
                'max_selling_price' => 180.00,
                'pricing_tiers' => [
                    ['min_quantity' => 1, 'price' => 150.00],
                    ['min_quantity' => 15, 'price' => 145.00],
                    ['min_quantity' => 75, 'price' => 140.00],
                    ['min_quantity' => 150, 'price' => 135.00],
                ],
                'discounts' => [
                    ['type' => 'percentage', 'value' => 7, 'min_quantity' => 100],
                    ['type' => 'fixed', 'value' => 15, 'min_quantity' => 200],
                ],
                'taxes' => [
                    ['name' => 'VAT', 'rate' => 15, 'min_quantity' => 1],
                ],
                'is_active' => true,
                'valid_from' => now()->addDays(7), // Future pricing
                'valid_until' => now()->addMonths(6),
                'created_by' => $users->skip(1)->first()?->id ?? $users->first()->id,
            ],
        ];
        
        // Insert the item pricing records
        foreach ($itemPricingData as $data) {
            ItemPricing::create($data);
        }
        
        $this->command->info('Item pricing data seeded successfully!');
    }
    
    /**
     * Create sample items if none exist
     */
    private function createSampleItems(): void
    {
        $items = [
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'Premium Steel Pipes',
                'price' => 120.00,
                'description' => 'High-quality steel pipes suitable for construction projects'
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'Concrete Blocks',
                'price' => 85.00,
                'description' => 'Standard concrete blocks for building construction'
            ],
            [
                'workspace_id' => 1,
                'unit_id' => 1,
                'title' => 'Insulated Electrical Wire',
                'price' => 250.00,
                'description' => 'High-grade electrical wire with insulation'
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
                'title' => 'Wooden Planks',
                'price' => 45.00,
                'description' => 'Quality wooden planks for carpentry work'
            ]
        ];
        
        foreach ($items as $item) {
            \App\Models\Item::create($item);
        }
    }
    
    /**
     * Create sample units if none exist
     */
    private function createSampleUnits(): void
    {
        $units = [
            ['workspace_id' => 1, 'title' => 'Piece', 'short_form' => 'pc'],
            ['workspace_id' => 1, 'title' => 'Box', 'short_form' => 'bx'],
            ['workspace_id' => 1, 'title' => 'Kilogram', 'short_form' => 'kg'],
            ['workspace_id' => 1, 'title' => 'Meter', 'short_form' => 'm'],
            ['workspace_id' => 1, 'title' => 'Liter', 'short_form' => 'L']
        ];
        
        foreach ($units as $unit) {
            \App\Models\Unit::create($unit);
        }
    }
    
    /**
     * Create sample users if none exist
     */
    private function createSampleUsers(): void
    {
        $users = [
            [
                'workspace_id' => 1,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'password' => bcrypt('password'),
                'phone' => '+1234567890',
                'country' => 'USA',
                'email_verified_at' => now(),
                'default_workspace_id' => 1
            ],
            [
                'workspace_id' => 1,
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@example.com',
                'password' => bcrypt('password'),
                'phone' => '+0987654321',
                'country' => 'USA',
                'email_verified_at' => now(),
                'default_workspace_id' => 1
            ]
        ];
        
        foreach ($users as $user) {
            $newUser = \App\Models\User::create($user);
            
            // Assign admin role if it exists
            $role = \Spatie\Permission\Models\Role::where('name', 'admin')->first();
            if ($role) {
                $newUser->assignRole($role);
            }
        }
    }
}