<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class YemeniWorkspaceAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the existing workspace
        $workspace = Workspace::first();
        
        if (!$workspace) {
            $this->command->error('No workspace found in database!');
            return;
        }
        
        // Get all users
        $users = User::all();
        
        foreach ($users as $user) {
            // Attach user to workspace if not already attached
            if (!$user->workspaces->contains($workspace->id)) {
                $user->workspaces()->attach($workspace->id);
                $this->command->info("Assigned user {$user->first_name} {$user->last_name} to workspace {$workspace->title}");
            }
            
            // Set default workspace if not set
            if (is_null($user->default_workspace_id)) {
                $user->default_workspace_id = $workspace->id;
                $user->save();
                $this->command->info("Set default workspace for user {$user->first_name} {$user->last_name}");
            }
        }
        
        $this->command->info('Workspace assignments completed successfully!');
    }
}