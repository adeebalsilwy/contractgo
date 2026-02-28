<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Workspace;
use App\Models\User;
use App\Models\Contract;
use App\Models\Project;
use App\Models\Client;
use App\Models\Task;
use App\Models\Lead;
use App\Models\ContractQuantity;
use App\Models\JournalEntry;
use App\Models\ContractAmendment;
use App\Models\ContractApproval;

class AutoWorkspaceAssignment
{
    /**
     * Handle an incoming request and automatically assign users to workspaces
     * and link orphaned data to the first workspace.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only run this middleware for authenticated requests
        if (auth()->check()) {
            $this->assignUserToWorkspace();
            $this->linkOrphanedDataToWorkspace();
        }

        return $next($request);
    }

    /**
     * Automatically assign the authenticated user to the first available workspace
     * if they are not already assigned to any workspace.
     */
    private function assignUserToWorkspace()
    {
        try {
            $user = auth()->user();
            
            // Check if user is already assigned to any workspace
            if ($user->workspaces()->count() > 0) {
                // User already has workspaces, check if they have a default workspace
                if (!$user->default_workspace_id) {
                    $firstWorkspace = $user->workspaces()->first();
                    if ($firstWorkspace) {
                        $user->default_workspace_id = $firstWorkspace->id;
                        $user->save();
                        Log::info("Set default workspace for user {$user->id} to workspace {$firstWorkspace->id}");
                    }
                }
                return;
            }

            // Get the first workspace
            $workspace = Workspace::orderBy('id')->first();

            if ($workspace) {
                // Assign user to the workspace
                $user->workspaces()->attach($workspace->id);
                
                // Set as default workspace
                $user->default_workspace_id = $workspace->id;
                $user->save();
                
                Log::info("Auto-assigned user {$user->id} to workspace {$workspace->id}");
            } else {
                Log::warning("No workspace found for auto-assignment of user {$user->id}");
            }
        } catch (\Exception $e) {
            Log::error("Error in auto workspace assignment: " . $e->getMessage());
        }
    }

    /**
     * Link orphaned data (records without workspace_id) to the first workspace.
     * This handles various models that require workspace association.
     */
    private function linkOrphanedDataToWorkspace()
    {
        try {
            // Get the target workspace for orphaned data
            $targetWorkspace = Workspace::orderBy('id')->first();

            if (!$targetWorkspace) {
                Log::warning("No workspace available to link orphaned data");
                return;
            }

            // Only process models that have workspace_id column
            if (\Schema::hasColumn('contracts', 'workspace_id')) {
                $this->linkOrphanedContracts($targetWorkspace);
            }
            if (\Schema::hasColumn('projects', 'workspace_id')) {
                $this->linkOrphanedProjects($targetWorkspace);
            }
            if (\Schema::hasColumn('tasks', 'workspace_id')) {
                $this->linkOrphanedTasks($targetWorkspace);
            }
            if (\Schema::hasColumn('leads', 'workspace_id')) {
                $this->linkOrphanedLeads($targetWorkspace);
            }
            if (\Schema::hasColumn('contract_quantities', 'workspace_id')) {
                $this->linkOrphanedContractQuantities($targetWorkspace);
            }
            if (\Schema::hasColumn('journal_entries', 'workspace_id')) {
                $this->linkOrphanedJournalEntries($targetWorkspace);
            }
            // Skip contract_amendments and contract_approvals as they don't have workspace_id

            Log::info("Completed linking orphaned data to workspace {$targetWorkspace->id}");

        } catch (\Exception $e) {
            Log::error("Error in linking orphaned data: " . $e->getMessage());
        }
    }

    /**
     * Link contracts without workspace_id to the target workspace.
     */
    private function linkOrphanedContracts($workspace)
    {
        $orphanedContracts = Contract::whereNull('workspace_id')->get();
        $count = $orphanedContracts->count();

        if ($count > 0) {
            foreach ($orphanedContracts as $contract) {
                $contract->workspace_id = $workspace->id;
                $contract->save();
            }
            Log::info("Linked {$count} orphaned contracts to workspace {$workspace->id}");
        }
    }

    /**
     * Link projects without workspace_id to the target workspace.
     */
    private function linkOrphanedProjects($workspace)
    {
        $orphanedProjects = Project::whereNull('workspace_id')->get();
        $count = $orphanedProjects->count();

        if ($count > 0) {
            foreach ($orphanedProjects as $project) {
                $project->workspace_id = $workspace->id;
                $project->save();
            }
            Log::info("Linked {$count} orphaned projects to workspace {$workspace->id}");
        }
    }

    /**
     * Link tasks without workspace_id to the target workspace.
     */
    private function linkOrphanedTasks($workspace)
    {
        $orphanedTasks = Task::whereNull('workspace_id')->get();
        $count = $orphanedTasks->count();

        if ($count > 0) {
            foreach ($orphanedTasks as $task) {
                $task->workspace_id = $workspace->id;
                $task->save();
            }
            Log::info("Linked {$count} orphaned tasks to workspace {$workspace->id}");
        }
    }

    /**
     * Link leads without workspace_id to the target workspace.
     */
    private function linkOrphanedLeads($workspace)
    {
        $orphanedLeads = Lead::whereNull('workspace_id')->get();
        $count = $orphanedLeads->count();

        if ($count > 0) {
            foreach ($orphanedLeads as $lead) {
                $lead->workspace_id = $workspace->id;
                $lead->save();
            }
            Log::info("Linked {$count} orphaned leads to workspace {$workspace->id}");
        }
    }

    /**
     * Link contract quantities without workspace_id to the target workspace.
     */
    private function linkOrphanedContractQuantities($workspace)
    {
        $orphanedQuantities = ContractQuantity::whereNull('workspace_id')->get();
        $count = $orphanedQuantities->count();

        if ($count > 0) {
            foreach ($orphanedQuantities as $quantity) {
                $quantity->workspace_id = $workspace->id;
                $quantity->save();
            }
            Log::info("Linked {$count} orphaned contract quantities to workspace {$workspace->id}");
        }
    }

    /**
     * Link journal entries without workspace_id to the target workspace.
     */
    private function linkOrphanedJournalEntries($workspace)
    {
        $orphanedEntries = JournalEntry::whereNull('workspace_id')->get();
        $count = $orphanedEntries->count();

        if ($count > 0) {
            foreach ($orphanedEntries as $entry) {
                $entry->workspace_id = $workspace->id;
                $entry->save();
            }
            Log::info("Linked {$count} orphaned journal entries to workspace {$workspace->id}");
        }
    }

    /**
     * Link contract amendments without workspace_id to the target workspace.
     */
    private function linkOrphanedContractAmendments($workspace)
    {
        $orphanedAmendments = ContractAmendment::whereNull('workspace_id')->get();
        $count = $orphanedAmendments->count();

        if ($count > 0) {
            foreach ($orphanedAmendments as $amendment) {
                $amendment->workspace_id = $workspace->id;
                $amendment->save();
            }
            Log::info("Linked {$count} orphaned contract amendments to workspace {$workspace->id}");
        }
    }

    /**
     * Link contract approvals without workspace_id to the target workspace.
     */
    private function linkOrphanedContractApprovals($workspace)
    {
        $orphanedApprovals = ContractApproval::whereNull('workspace_id')->get();
        $count = $orphanedApprovals->count();

        if ($count > 0) {
            foreach ($orphanedApprovals as $approval) {
                $approval->workspace_id = $workspace->id;
                $approval->save();
            }
            Log::info("Linked {$count} orphaned contract approvals to workspace {$workspace->id}");
        }
    }
}