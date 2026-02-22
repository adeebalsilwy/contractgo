<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class YemeniProfessionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            YemeniUsersSeeder::class,
            YemeniClientsSeeder::class,
            YemeniProjectsSeeder::class,
            YemeniContractTypesSeeder::class,
            YemeniContractsSeeder::class,
            YemeniContractQuantitiesSeeder::class,
            YemeniContractApprovalsSeeder::class,
            YemeniJournalEntriesSeeder::class,
            YemeniContractAmendmentsSeeder::class,
        ]);
    }
}