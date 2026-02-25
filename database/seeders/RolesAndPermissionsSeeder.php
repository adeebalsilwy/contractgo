<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
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

        // Create permissions (you can customize these based on your app's needs)
        $permissions = [
            // User permissions
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Project permissions
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            
            // Task permissions
            'view tasks',
            'create tasks',
            'edit tasks',
            'delete tasks',
            
            // Client permissions
            'view clients',
            'create clients',
            'edit clients',
            'delete clients',
            
            // Contract permissions
            'view contracts',
            'create contracts',
            'edit contracts',
            'delete contracts',
            
            // Other permissions as needed
            'view dashboard',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create default roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);

        // Assign permissions to admin role (admin gets all permissions)
        $adminRole->givePermissionTo(Permission::all());

        // Assign some permissions to manager role
        $managerRole->givePermissionTo([
            'view dashboard',
            'view projects',
            'create projects',
            'edit projects',
            'view tasks',
            'create tasks',
            'edit tasks',
            'view clients',
            'view contracts',
        ]);

        // Assign basic permissions to employee role
        $employeeRole->givePermissionTo([
            'view dashboard',
            'view tasks',
            'view projects',
        ]);

        echo "Roles and Permissions created successfully!\n";
    }
}