<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AddMissingPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create the missing access_all_data permission for web guard
        $permission = Permission::where('name', 'access_all_data')->where('guard_name', 'web')->first();
        if (!$permission) {
            Permission::create([
                'name' => 'access_all_data',
                'guard_name' => 'web'
            ]);
            echo "Created access_all_data permission for web guard\n";
        } else {
            echo "access_all_data permission already exists for web guard\n";
        }

        // Create the missing access_all_data permission for client guard
        $clientPermission = Permission::where('name', 'access_all_data')->where('guard_name', 'client')->first();
        if (!$clientPermission) {
            Permission::create([
                'name' => 'access_all_data',
                'guard_name' => 'client'
            ]);
            echo "Created access_all_data permission for client guard\n";
        } else {
            echo "access_all_data permission already exists for client guard\n";
        }
    }
}