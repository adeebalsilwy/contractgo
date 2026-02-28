<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ContractCompleteSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ContractRelatedSeeder::class,
            ContractSeeder::class,
            JournalEntrySeeder::class,
            ContractQuantitySeeder::class,
            ContractApprovalSeeder::class,
            ContractAmendmentSeeder::class,
            EstimatesInvoiceSeeder::class,
        ]);
    }
}