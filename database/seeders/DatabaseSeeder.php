<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents ;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RolesAndPermissionsSeeder::class,
            // Yemeni seeders first to establish relationships
            YemeniUsersSeeder::class,
            YemeniClientsSeeder::class,
            YemeniProjectsSeeder::class,
            // Now other seeders that might depend on basic data
            SuperAdminSeeder::class,
            StatusSeeder::class,
            PrioritySeeder::class,
            TagSeeder::class,
            UnitsSeeder::class,
            PaymentMethodsSeeder::class,
            YemeniContractTypesSeeder::class,
            YemeniContractsSeeder::class,
            YemeniContractQuantitiesSeeder::class,
            YemeniContractApprovalsSeeder::class,
            YemeniContractAmendmentsSeeder::class,
            YemeniJournalEntriesSeeder::class,
            // Add our new seeders
            ItemPricingSeeder::class,
            ContractQuantitySeeder::class,
            ComprehensiveItemPricingSeeder::class,
            // Comprehensive Yemeni seeder
            YemeniComprehensiveSeeder::class,
        ]);
    }
}