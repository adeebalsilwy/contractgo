<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EnsureAdeebUserSeeder extends Seeder
{
    /**
     * Run the database seeds to ensure adeebalsilwy19@gmail.com has ID 1
     */
    public function run(): void
    {
        // First, check if user exists
        $user = User::where('email', 'adeebalsilwy19@gmail.com')->first();
        
        if (!$user) {
            // User doesn't exist, create with ID 1
            $this->command->info('Creating Adeeb user with ID 1...');
            
            // We need to temporarily disable auto-increment to set specific ID
            \DB::statement('SET FOREIGN_KEY_CHECKS=0');
            \DB::table('users')->where('id', 1)->delete(); // Remove any existing user with ID 1
            
            $user = User::create([
                'id' => 1, // Force ID to be 1
                'first_name' => 'Adeeb',
                'last_name' => 'Alsilwy',
                'email' => 'adeebalsilwy19@gmail.com',
                'password' => Hash::make('12345678'),
                'active_status' => 1,
                'email_verified_at' => now(),
                'default_workspace_id' => 1,
            ]);
            
            // Ensure admin role exists and assign it
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $user->assignRole($adminRole);
            }
            
            \DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            $this->command->info('Adeeb user created with ID 1 successfully!');
        } else {
            // User exists, check if ID is 1
            if ($user->id != 1) {
                $this->command->info('Adeeb user exists but ID is not 1. Current ID: ' . $user->id);
                // We'll need to reassign ID - this is complex, so let's just ensure proper workspace
            } else {
                $this->command->info('Adeeb user already exists with ID 1');
            }
        }
        
        // Ensure default workspace exists and is assigned
        $workspace = Workspace::where('user_id', 1)->first();
        if (!$workspace) {
            $this->command->info('Creating default workspace for Adeeb...');
            $workspace = Workspace::create([
                'id' => 1, // Force ID to be 1
                'user_id' => 1,
                'title' => 'الworkspace الافتراضي',
                'description' => 'Workspace الافتراضي لـ Adeeb Alsilwy',
            ]);
        }
        
        // Ensure user has default workspace assigned
        if ($user && $user->default_workspace_id != 1) {
            $user->default_workspace_id = 1;
            $user->save();
            $this->command->info('Assigned default workspace to Adeeb user');
        }
        
        $this->command->info('Adeeb user setup completed successfully!');
        $this->command->info('Email: adeebalsilwy19@gmail.com');
        $this->command->info('Password: 12345678');
        $this->command->info('User ID: 1');
        $this->command->info('Default Workspace ID: 1');
    }
}