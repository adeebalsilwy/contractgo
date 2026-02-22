<?php

namespace Database\Seeders;

use App\Models\ContractType;
use Illuminate\Database\Seeder;

class YemeniContractTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define diverse Yemeni contract types
        $contractTypes = [
            [
                'type' => ' contruction_contract',
                'workspace_id' => 1,
            ],
            [
                'type' => ' maintenance_contract',
                'workspace_id' => 1,
            ],
            [
                'type' => ' service_contract',
                'workspace_id' => 1,
            ],
            [
                'type' => ' supply_contract',
                'workspace_id' => 1,
            ],
            [
                'type' => ' engineering_contract',
                'workspace_id' => 1,
            ],
            [
                'type' => ' consulting_contract',
                'workspace_id' => 1,
            ],
            [
                'type' => ' repair_contract',
                'workspace_id' => 1,
            ],
            [
                'type' => ' renovation_contract',
                'workspace_id' => 1,
            ],
            [
                'type' => ' infrastructure_contract',
                'workspace_id' => 1,
            ],
            [
                'type' => ' development_contract',
                'workspace_id' => 1,
            ],
        ];

        foreach ($contractTypes as $contractTypeData) {
            ContractType::create([
                'type' => $contractTypeData['type'],
                'workspace_id' => $contractTypeData['workspace_id'],
            ]);
        }
    }
}