<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EstimatesInvoicePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions if they don't exist
        $permissions = [
            'manage_estimates_invoices',
            'create_estimates_invoices',
            'edit_estimates_invoices',
            'delete_estimates_invoices'
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission, 'guard_name' => 'web']);
                $this->command->info("Permission '{$permission}' created successfully!");
            } else {
                $this->command->info("Permission '{$permission}' already exists.");
            }
        }

        // Assign permissions to admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            foreach ($permissions as $permission) {
                if (!$adminRole->hasPermissionTo($permission)) {
                    $adminRole->givePermissionTo($permission);
                    $this->command->info("Permission '{$permission}' assigned to admin role.");
                }
            }
        }

        $this->command->info('Estimates/Invoices permissions setup completed!');
    }
}