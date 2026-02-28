<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Workspace;
use App\Models\User;
use App\Models\Contract;
use App\Models\Project;
use App\Models\Task;
use App\Models\Lead;
use App\Models\ContractQuantity;
use App\Models\JournalEntry;
use App\Models\ContractAmendment;
use App\Models\ContractApproval;

class ProcessOrphanedWorkspaceData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workspace:assign-orphaned-data {--workspace-id=} {--detailed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign orphaned data to the specified workspace or first available workspace';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $detailed = $this->option('detailed');
        $workspaceId = $this->option('workspace-id');
        
        $this->info('Starting workspace assignment for orphaned data...');
        
        if ($workspaceId) {
            $workspace = Workspace::find($workspaceId);
            if (!$workspace) {
                $this->error("Workspace with ID {$workspaceId} not found.");
                return Command::FAILURE;
            }
            $this->info("Using specified workspace: {$workspace->title} (ID: {$workspace->id})");
        } else {
            $workspace = Workspace::orderBy('id')->first();
            
            if (!$workspace) {
                $this->error('No workspace found in the system.');
                return Command::FAILURE;
            }
            $this->info("Using first workspace: {$workspace->title} (ID: {$workspace->id})");
        }

        // Process all orphaned data (only models with workspace_id column)
        $this->processOrphanedUsers($workspace, $detailed);
        
        if (\Schema::hasColumn('contracts', 'workspace_id')) {
            $this->processOrphanedContracts($workspace, $detailed);
        }
        if (\Schema::hasColumn('projects', 'workspace_id')) {
            $this->processOrphanedProjects($workspace, $detailed);
        }
        if (\Schema::hasColumn('tasks', 'workspace_id')) {
            $this->processOrphanedTasks($workspace, $detailed);
        }
        if (\Schema::hasColumn('leads', 'workspace_id')) {
            $this->processOrphanedLeads($workspace, $detailed);
        }
        if (\Schema::hasColumn('contract_quantities', 'workspace_id')) {
            $this->processOrphanedContractQuantities($workspace, $detailed);
        }
        if (\Schema::hasColumn('journal_entries', 'workspace_id')) {
            $this->processOrphanedJournalEntries($workspace, $detailed);
        }
        // Skip contract_amendments and contract_approvals as they don't have workspace_id

        $this->info('Workspace assignment completed successfully!');
        return Command::SUCCESS;
    }

    private function processOrphanedUsers($workspace, $detailed)
    {
        // Get users who are not assigned to any workspace
        $users = User::whereDoesntHave('workspaces')->get();
        $count = $users->count();

        if ($count > 0) {
            $bar = $this->output->createProgressBar($count);
            $bar->start();

            foreach ($users as $user) {
                $user->workspaces()->attach($workspace->id);
                $user->default_workspace_id = $workspace->id;
                $user->save();
                
                if ($detailed) {
                    $this->line("Assigned user {$user->id} ({$user->email}) to workspace {$workspace->id}");
                }
                $bar->advance();
            }
            
            $bar->finish();
            $this->line("\nAssigned {$count} users to workspace {$workspace->id}");
            Log::info("Assigned {$count} users to workspace {$workspace->id}");
        } else {
            if ($detailed) {
                $this->line("No orphaned users found");
            }
        }
    }

    private function processOrphanedContracts($workspace, $detailed)
    {
        $orphanedCount = Contract::whereNull('workspace_id')->count();
        if ($orphanedCount > 0) {
            $updated = Contract::whereNull('workspace_id')->update(['workspace_id' => $workspace->id]);
            $this->info("Assigned {$updated} contracts to workspace {$workspace->id}");
            Log::info("Assigned {$updated} contracts to workspace {$workspace->id}");
            
            if ($detailed) {
                $contracts = Contract::where('workspace_id', $workspace->id)
                                    ->where('updated_at', '>=', now()->subMinutes(5))
                                    ->get();
                foreach ($contracts as $contract) {
                    $this->line("- Contract: {$contract->title} (ID: {$contract->id})");
                }
            }
        }
    }

    private function processOrphanedProjects($workspace, $detailed)
    {
        $orphanedCount = Project::whereNull('workspace_id')->count();
        if ($orphanedCount > 0) {
            $updated = Project::whereNull('workspace_id')->update(['workspace_id' => $workspace->id]);
            $this->info("Assigned {$updated} projects to workspace {$workspace->id}");
            Log::info("Assigned {$updated} projects to workspace {$workspace->id}");
        }
    }

    private function processOrphanedTasks($workspace, $detailed)
    {
        $orphanedCount = Task::whereNull('workspace_id')->count();
        if ($orphanedCount > 0) {
            $updated = Task::whereNull('workspace_id')->update(['workspace_id' => $workspace->id]);
            $this->info("Assigned {$updated} tasks to workspace {$workspace->id}");
            Log::info("Assigned {$updated} tasks to workspace {$workspace->id}");
        }
    }

    private function processOrphanedLeads($workspace, $detailed)
    {
        $orphanedCount = Lead::whereNull('workspace_id')->count();
        if ($orphanedCount > 0) {
            $updated = Lead::whereNull('workspace_id')->update(['workspace_id' => $workspace->id]);
            $this->info("Assigned {$updated} leads to workspace {$workspace->id}");
            Log::info("Assigned {$updated} leads to workspace {$workspace->id}");
        }
    }

    private function processOrphanedContractQuantities($workspace, $detailed)
    {
        $orphanedCount = ContractQuantity::whereNull('workspace_id')->count();
        if ($orphanedCount > 0) {
            $updated = ContractQuantity::whereNull('workspace_id')->update(['workspace_id' => $workspace->id]);
            $this->info("Assigned {$updated} contract quantities to workspace {$workspace->id}");
            Log::info("Assigned {$updated} contract quantities to workspace {$workspace->id}");
        }
    }

    private function processOrphanedJournalEntries($workspace, $detailed)
    {
        $orphanedCount = JournalEntry::whereNull('workspace_id')->count();
        if ($orphanedCount > 0) {
            $updated = JournalEntry::whereNull('workspace_id')->update(['workspace_id' => $workspace->id]);
            $this->info("Assigned {$updated} journal entries to workspace {$workspace->id}");
            Log::info("Assigned {$updated} journal entries to workspace {$workspace->id}");
        }
    }

    private function processOrphanedContractAmendments($workspace, $detailed)
    {
        $orphanedCount = ContractAmendment::whereNull('workspace_id')->count();
        if ($orphanedCount > 0) {
            $updated = ContractAmendment::whereNull('workspace_id')->update(['workspace_id' => $workspace->id]);
            $this->info("Assigned {$updated} contract amendments to workspace {$workspace->id}");
            Log::info("Assigned {$updated} contract amendments to workspace {$workspace->id}");
        }
    }

    private function processOrphanedContractApprovals($workspace, $detailed)
    {
        $orphanedCount = ContractApproval::whereNull('workspace_id')->count();
        if ($orphanedCount > 0) {
            $updated = ContractApproval::whereNull('workspace_id')->update(['workspace_id' => $workspace->id]);
            $this->info("Assigned {$updated} contract approvals to workspace {$workspace->id}");
            Log::info("Assigned {$updated} contract approvals to workspace {$workspace->id}");
        }
    }
}