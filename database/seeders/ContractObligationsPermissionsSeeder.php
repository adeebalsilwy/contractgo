<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ContractObligationsPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create contract obligations permissions
        $permissions = [
            'manage_contract_obligations',
            'view_contract_obligations', 
            'create_contract_obligations',
            'edit_contract_obligations',
            'delete_contract_obligations',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Get the admin role and assign the new permissions
        $adminRole = Role::where('name', 'admin')->where('guard_name', 'web')->first();
        if ($adminRole) {
            foreach ($permissions as $permission) {
                $permissionObj = Permission::where('name', $permission)->where('guard_name', 'web')->first();
                if ($permissionObj) {
                    $adminRole->givePermissionTo($permissionObj);
                }
            }
        }

        $this->command->info('Contract obligations permissions created successfully.');
    }
}