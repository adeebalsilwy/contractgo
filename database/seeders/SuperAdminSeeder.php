<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the super admin user already exists
        $superAdmin = User::where('email', 'adeebalsilwy19@gmail.com')->first();
        
        if (!$superAdmin) {
            // Create the super admin user
            $superAdmin = User::create([
                'first_name' => 'Adeeb',
                'last_name' => 'Alsilwy',
                'email' => 'adeebalsilwy19@gmail.com',
                'password' => Hash::make('12345678'),
                'status' => 1, // Active
                'email_verified_at' => now(),
            ]);

            // Assign the admin role to the super admin user
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $superAdmin->assignRole($adminRole);
            } else {
                // If admin role doesn't exist, assign it by ID (assuming ID 1 is admin)
                $superAdmin->assignRole(1);
            }

            echo "Super Admin user created successfully!\n";
            echo "Email: adeebalsilwy19@gmail.com\n";
            echo "Password: 12345678\n";
        } else {
            echo "Super Admin user already exists!\n";
        }
    }
}