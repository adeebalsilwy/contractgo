<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use App\Http\Middleware\AutoWorkspaceAssignment;

class WorkspaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register the middleware
        $this->app['router']->aliasMiddleware('auto.workspace.assignment', AutoWorkspaceAssignment::class);
        
        // Listen for user login events to trigger workspace assignment
        Event::listen(Login::class, function ($event) {
            $this->handleUserLogin($event->user);
        });

        // Listen for user registration events to trigger workspace assignment
        Event::listen(Registered::class, function ($event) {
            $this->handleUserRegistration($event->user);
        });
    }

    /**
     * Handle user login event for automatic workspace assignment
     */
    private function handleUserLogin($user)
    {
        try {
            $this->assignUserToWorkspace($user);
            $this->linkOrphanedData();
        } catch (\Exception $e) {
            \Log::error('Error in workspace assignment on login: ' . $e->getMessage());
        }
    }

    /**
     * Handle user registration event for automatic workspace assignment
     */
    private function handleUserRegistration($user)
    {
        try {
            $this->assignUserToWorkspace($user);
        } catch (\Exception $e) {
            \Log::error('Error in workspace assignment on registration: ' . $e->getMessage());
        }
    }

    /**
     * Assign user to workspace if not already assigned
     */
    private function assignUserToWorkspace($user)
    {
        // Check if user is already assigned to any workspace
        if ($user->workspaces()->count() > 0) {
            // Ensure user has a default workspace
            if (!$user->default_workspace_id) {
                $firstWorkspace = $user->workspaces()->first();
                if ($firstWorkspace) {
                    $user->default_workspace_id = $firstWorkspace->id;
                    $user->save();
                }
            }
            return;
        }

        // Get the first workspace
        $workspace = \App\Models\Workspace::orderBy('id')->first();

        if ($workspace) {
            // Assign user to workspace
            $user->workspaces()->attach($workspace->id);
            $user->default_workspace_id = $workspace->id;
            $user->save();
            
            \Log::info("Auto-assigned user {$user->id} to workspace {$workspace->id} on event");
        }
    }

    /**
     * Link orphaned data to workspace (simplified version for events)
     */
    private function linkOrphanedData()
    {
        // This is a simplified version that runs periodically
        // The full version runs in the middleware for every request
        // This event-based version focuses on critical data
        $workspace = \App\Models\Workspace::orderBy('id')->first();

        if (!$workspace) return;

        // Link critical orphaned data
        $models = [
            'contracts' => \App\Models\Contract::class,
            'projects' => \App\Models\Project::class,
            'leads' => \App\Models\Lead::class,
        ];

        foreach ($models as $name => $modelClass) {
            $count = $modelClass::whereNull('workspace_id')->count();
            if ($count > 0) {
                $modelClass::whereNull('workspace_id')->update(['workspace_id' => $workspace->id]);
                \Log::info("Linked {$count} orphaned {$name} to workspace {$workspace->id}");
            }
        }
    }
}