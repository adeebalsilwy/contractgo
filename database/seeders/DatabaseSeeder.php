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
            // Languages first
            LanguageSeeder::class,
            RolesAndPermissionsSeeder::class,
            // Basic data seeders first
            StatusSeeder::class,
            PrioritySeeder::class,
            TagSeeder::class,
            UnitsSeeder::class,
            // Create single workspace first
            YemeniSingleWorkspaceSeeder::class,
            // Contract types after workspace
            YemeniContractTypesSeeder::class,
            // Payment methods after workspace
            PaymentMethodsSeeder::class,
            // Yemeni general settings
            YemeniGeneralSettingsSeeder::class,
            // Create the main 5 projects and 10 contracts with workflow (includes professions)
            ModernRealEstateCompanySeeder::class,
            // Yemeni seeders (now that professions exist)
            YemeniUsersSeeder::class,
            YemeniClientsSeeder::class,
            // Verify projects exist
            YemeniProjectsSeeder::class,
            // Verify contracts exist
            YemeniContractsSeeder::class,
            // Other seeders
            SuperAdminSeeder::class,
            // Add our new seeders
            ItemPricingSeeder::class,
            ContractQuantitySeeder::class,
            ComprehensiveItemPricingSeeder::class,
            ContractTypePermissionSeeder::class,
            YemeniExtractsSeeder::class,
            ContractObligationsSeeder::class,
            // Skip problematic workflow seeders for now
            // CompleteWorkflowSeeder::class,
            // WorkflowTestDataSeeder::class,
        ]);
    }
}