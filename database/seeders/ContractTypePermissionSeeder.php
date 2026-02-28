<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ContractTypePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create contract type permissions if they don't exist for web guard
        $permissions = [
            'manage_contract_types',
            'create_contract_types', 
            'edit_contract_types',
            'delete_contract_types'
        ];

        foreach ($permissions as $permission) {
            // Create for web guard
            if (!Permission::where('name', $permission)->where('guard_name', 'web')->exists()) {
                Permission::create(['name' => $permission, 'guard_name' => 'web']);
                $this->command->info("Permission '{$permission}' created successfully for web guard!");
            } else {
                $this->command->info("Permission '{$permission}' already exists for web guard.");
            }
            
            // Create for client guard as well
            if (!Permission::where('name', $permission)->where('guard_name', 'client')->exists()) {
                Permission::create(['name' => $permission, 'guard_name' => 'client']);
                $this->command->info("Permission '{$permission}' created successfully for client guard!");
            } else {
                $this->command->info("Permission '{$permission}' already exists for client guard.");
            }
        }

        // Assign permissions to admin role (web)
        $adminRole = Role::where('name', 'admin')->where('guard_name', 'web')->first();
        if ($adminRole) {
            foreach ($permissions as $permission) {
                if (!$adminRole->hasPermissionTo($permission)) {
                    $adminRole->givePermissionTo($permission);
                    $this->command->info("Permission '{$permission}' assigned to admin role (web).");
                }
            }
        }

        // Assign permissions to admin role (client)
        $adminClientRole = Role::where('name', 'admin')->where('guard_name', 'client')->first();
        if ($adminClientRole) {
            foreach ($permissions as $permission) {
                if (!$adminClientRole->hasPermissionTo($permission)) {
                    $adminClientRole->givePermissionTo($permission);
                    $this->command->info("Permission '{$permission}' assigned to admin role (client).");
                }
            }
        }

        // Assign basic permissions to manager role (web)
        $managerRole = Role::where('name', 'manager')->where('guard_name', 'web')->first();
        if ($managerRole) {
            foreach ($permissions as $permission) {
                if (!$managerRole->hasPermissionTo($permission)) {
                    $managerRole->givePermissionTo($permission);
                    $this->command->info("Permission '{$permission}' assigned to manager role (web).");
                }
            }
        }
        
        // Assign basic permissions to manager role (client)
        $managerClientRole = Role::where('name', 'manager')->where('guard_name', 'client')->first();
        if ($managerClientRole) {
            foreach ($permissions as $permission) {
                if (!$managerClientRole->hasPermissionTo($permission)) {
                    $managerClientRole->givePermissionTo($permission);
                    $this->command->info("Permission '{$permission}' assigned to manager role (client).");
                }
            }
        }

        $this->command->info('Contract Types permissions setup completed!');
    }
}