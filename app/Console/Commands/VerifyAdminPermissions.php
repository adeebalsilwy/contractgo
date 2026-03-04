<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class VerifyAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:verify-permissions 
                            {--email= : Email of specific user to verify (optional)}
                            {--detailed : Show detailed permission list}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify admin permissions for user(s)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Admin Permission Verification Tool');
        $this->line('=================================');
        
        $email = $this->option('email');
        $detailed = $this->option('detailed');
        
        if ($email) {
            $users = User::where('email', $email)->get();
            if ($users->isEmpty()) {
                $this->error("❌ User with email '{$email}' not found!");
                return Command::FAILURE;
            }
        } else {
            $users = User::whereHas('roles', function($q) { 
                $q->where('name', 'admin'); 
            })->get();
            
            if ($users->isEmpty()) {
                $this->error('❌ No admin users found!');
                return Command::FAILURE;
            }
        }
        
        $totalPermissions = Permission::count();
        $this->info("📊 Total system permissions: {$totalPermissions}");
        $this->line('');
        
        foreach ($users as $user) {
            $this->line("👤 User: {$user->first_name} {$user->last_name} ({$user->email})");
            $this->line("🆔 ID: {$user->id}");
            $this->line("🔐 Has admin role: " . ($user->hasRole('admin') ? '✅ Yes' : '❌ No'));
            
            $userPermissions = $user->getAllPermissions()->count();
            $this->line("📊 User permissions: {$userPermissions}/{$totalPermissions}");
            
            $percentage = round(($userPermissions / $totalPermissions) * 100, 1);
            $this->line("📊 Coverage: {$percentage}%");
            
            if ($userPermissions >= $totalPermissions * 0.9) {
                $this->info("✅ Excellent permission coverage!");
            } elseif ($userPermissions >= $totalPermissions * 0.7) {
                $this->warn("⚠️ Good permission coverage");
            } else {
                $this->error("❌ Limited permission coverage");
            }
            
            if ($detailed) {
                $this->line("\n📋 Detailed permissions:");
                $user->getAllPermissions()->sortBy('name')->each(function($permission) {
                    $this->line("  - {$permission->name} ({$permission->guard_name})");
                });
            }
            
            $this->line('');
        }
        
        $this->info('✅ Verification completed');
        return Command::SUCCESS;
    }
}