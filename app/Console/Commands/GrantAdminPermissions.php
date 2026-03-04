<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

class GrantAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:grant-all-permissions 
                            {--email= : Email of the admin user (optional)} 
                            {--force : Force execution without confirmation}
                            {--dry-run : Show what would be done without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant all system permissions to admin user(s) - Professional permission management tool';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔐 Professional Admin Permission Management Tool');
        $this->line('=========================================');
        
        // Check if it's a dry run
        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->warn('📋 DRY RUN MODE - No changes will be made');
            $this->line('');
        }

        try {
            // Get admin users
            $adminUsers = $this->getAdminUsers();
            
            if ($adminUsers->isEmpty()) {
                $this->error('❌ No admin users found in the system!');
                $this->line('💡 Please create an admin user first or specify email with --email option');
                return Command::FAILURE;
            }

            // Display found admin users
            $this->info('📋 Found Admin Users:');
            $adminUsers->each(function ($user) {
                $this->line("   👤 {$user->first_name} {$user->last_name} ({$user->email}) - ID: {$user->id}");
            });
            $this->line('');

            // Get all permissions
            $permissions = Permission::all();
            $this->info("📊 System Permissions Analysis:");
            $this->line("   Total Permissions: {$permissions->count()}");
            $this->line("   Permission Groups: " . $this->getPermissionGroups($permissions));
            $this->line('');

            // Confirmation
            if (!$this->option('force') && !$dryRun) {
                if (!$this->confirm('⚠️  Do you want to proceed with granting all permissions to admin users?')) {
                    $this->line('❌ Operation cancelled by user.');
                    return Command::SUCCESS;
                }
            }

            // Process each admin user
            $results = [];
            foreach ($adminUsers as $user) {
                $result = $this->grantPermissionsToUser($user, $permissions, $dryRun);
                $results[] = $result;
            }

            // Display results
            $this->displayResults($results, $dryRun);

            // Clear cache
            if (!$dryRun) {
                $this->line('🔄 Clearing permission cache...');
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                $this->info('✅ Cache cleared successfully');
            }

            $this->line('');
            $this->info($dryRun ? '📋 DRY RUN COMPLETED' : '✅ PERMISSION GRANTING COMPLETED');
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("🔍 Stack trace: " . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    /**
     * Get admin users based on command options
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAdminUsers()
    {
        $email = $this->option('email');
        
        if ($email) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $this->error("❌ User with email '{$email}' not found!");
                return collect();
            }
            
            if (!$user->hasRole('admin')) {
                $this->warn("⚠️  User '{$email}' does not have admin role. Adding admin role...");
                $user->assignRole('admin');
            }
            
            return collect([$user]);
        }

        // Get all users with admin role
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();
    }

    /**
     * Grant all permissions to a user
     *
     * @param User $user
     * @param \Illuminate\Database\Eloquent\Collection $permissions
     * @param bool $dryRun
     * @return array
     */
    private function grantPermissionsToUser($user, $permissions, $dryRun)
    {
        $this->line("🔐 Processing user: {$user->first_name} {$user->last_name} ({$user->email})");
        
        $beforeCount = $user->getAllPermissions()->count();
        $this->line("   Before: {$beforeCount} permissions");
        
        $grantedCount = 0;
        
        if (!$dryRun) {
            // Group permissions by guard
            $permissionsByGuard = $permissions->groupBy('guard_name');
            
            foreach ($permissionsByGuard as $guard => $guardPermissions) {
                $this->line("   Processing {$guardPermissions->count()} permissions for guard '{$guard}'");
                
                // Filter user permissions to match guard
                if ($guard === 'client') {
                    // For client guard, we need to be more careful
                    $this->warn("   ⚠️ Skipping client guard permissions for web user");
                    continue;
                }
                
                try {
                    // Grant permissions for this guard
                    $user->givePermissionTo($guardPermissions);
                    $grantedCount += $guardPermissions->count();
                    $this->line("   ✅ Granted {$guardPermissions->count()} {$guard} permissions");
                } catch (\Exception $e) {
                    $this->error("   ❌ Failed to grant {$guard} permissions: " . $e->getMessage());
                    // Continue with other guards
                }
            }
            
            // Ensure admin role is assigned
            if (!$user->hasRole('admin')) {
                $user->assignRole('admin');
                $this->line("   ✅ Admin role assigned");
            }
        } else {
            // Dry run calculation
            $webApiPermissions = $permissions->whereNotIn('guard_name', ['client']);
            $grantedCount = $webApiPermissions->count();
        }
        
        $afterCount = $dryRun ? ($beforeCount + $grantedCount) : $user->getAllPermissions()->count();
        
        $this->line("   After: {$afterCount} permissions");
        $this->line("   Granted: {$grantedCount} new permissions");
        
        if ($grantedCount > 0 && !$dryRun) {
            $this->info("   ✅ Permissions granted successfully!");
        } elseif ($dryRun) {
            $this->warn("   📋 Would grant {$grantedCount} permissions (dry run)");
        }
        
        return [
            'user' => $user,
            'before_count' => $beforeCount,
            'after_count' => $afterCount,
            'granted_count' => $grantedCount,
            'success' => true
        ];
    }

    /**
     * Get permission groups for display
     *
     * @param \Illuminate\Database\Eloquent\Collection $permissions
     * @return string
     */
    private function getPermissionGroups($permissions)
    {
        $groups = $permissions->pluck('name')->map(function ($name) {
            return explode('_', $name)[0] ?? 'other';
        })->unique()->sort()->values();
        
        return $groups->implode(', ');
    }

    /**
     * Display operation results
     *
     * @param array $results
     * @param bool $dryRun
     * @return void
     */
    private function displayResults($results, $dryRun)
    {
        $this->line('');
        $this->info($dryRun ? '📋 DRY RUN RESULTS:' : '📊 OPERATION RESULTS:');
        $this->line('====================');
        
        $totalGranted = 0;
        foreach ($results as $result) {
            $user = $result['user'];
            $this->line("👤 {$user->first_name} {$user->last_name} ({$user->email}):");
            $this->line("   Permissions: {$result['before_count']} → {$result['after_count']} " . 
                       ($dryRun ? '(would be)' : '(actual)'));
            $this->line("   Granted: {$result['granted_count']} permissions");
            $totalGranted += $result['granted_count'];
        }
        
        $this->line('');
        $status = $dryRun ? 'Would grant' : 'Granted';
        $this->info("📈 Total {$status}: {$totalGranted} permissions across " . count($results) . " user(s)");
    }
}