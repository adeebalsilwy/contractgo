<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SystemCacheManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:cache-manager 
                            {action? : The action to perform (clear-all|optimize|reset|status)} 
                            {--type= : Specific cache type to clear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Professional system cache management tool';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $action = $this->argument('action') ?? $this->choice(
            'What action would you like to perform?',
            ['clear-all', 'optimize', 'reset', 'status', 'clear-type'],
            'status'
        );

        $this->info("🚀 Starting System Cache Management: {$action}");
        $this->line('');

        try {
            switch ($action) {
                case 'clear-all':
                    $this->clearAllCache();
                    break;
                case 'optimize':
                    $this->optimizeSystem();
                    break;
                case 'reset':
                    $this->completeReset();
                    break;
                case 'status':
                    $this->showStatus();
                    break;
                case 'clear-type':
                    $this->clearSpecificType();
                    break;
                default:
                    $this->error('Invalid action specified');
                    return 1;
            }

            $this->line('');
            $this->info('✅ Operation completed successfully!');
            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Operation failed: " . $e->getMessage());
            Log::error('System cache manager error: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Clear all cache types
     */
    private function clearAllCache()
    {
        $this->info('🧹 Clearing all cache types...');
        
        $cacheTypes = [
            'cache:clear' => 'Application Cache',
            'route:clear' => 'Route Cache',
            'config:clear' => 'Config Cache',
            'view:clear' => 'View Cache',
            'event:clear' => 'Event Cache',
            'clear-compiled' => 'Compiled Files',
        ];

        foreach ($cacheTypes as $command => $description) {
            $this->line("  Clearing {$description}...");
            try {
                Artisan::call($command);
                $this->line("  ✅ {$description} cleared");
            } catch (\Exception $e) {
                $this->line("  ❌ Failed to clear {$description}: " . $e->getMessage());
            }
        }

        // Clear storage directories
        $this->line('  Clearing storage directories...');
        $storageDirs = [
            'framework/cache',
            'framework/views',
            'framework/sessions'
        ];

        foreach ($storageDirs as $dir) {
            $path = storage_path($dir);
            if (File::exists($path)) {
                File::cleanDirectory($path);
                $this->line(" ✅ Cleared {$dir}");
            }
        }
    }

    /**
     * Optimize the system
     */
    private function optimizeSystem()
    {
        $this->info('⚡ Optimizing system...');

        // Clear all cache first
        $this->clearAllCache();

        // Optimize for production
        if (config('app.env') === 'production') {
            $this->line('  Optimizing for production environment...');
            
            $optimizations = [
                'optimize' => 'Application Optimization',
                'route:cache' => 'Route Caching',
                'config:cache' => 'Config Caching',
                'event:cache' => 'Event Caching',
            ];

            foreach ($optimizations as $command => $description) {
                $this->line("  Applying {$description}...");
                try {
                    Artisan::call($command);
                    $this->line(" ✅ {$description} applied");
                } catch (\Exception $e) {
                    $this->line("  ❌ Failed to apply {$description}: " . $e->getMessage());
                }
            }
        } else {
            $this->line('  Development environment - skipping production optimizations');
        }
    }

    /**
     * Complete system reset
     */
    private function completeReset()
    {
        $this->info('💣 Performing complete system reset...');
        $this->warn('⚠️  This will clear ALL cache, compiled files, and temporary data!');
        
        if (!$this->confirm('Are you absolutely sure you want to proceed?')) {
            $this->info('Operation cancelled.');
            return;
        }

        // Clear all cache types
        $this->clearAllCache();

        // Additional cleanup
        $this->line('  Performing additional cleanup...');
        
        // Clear logs (optional)
        if ($this->confirm('Clear application logs as well?')) {
            $logPath = storage_path('logs');
            if (File::exists($logPath)) {
                File::cleanDirectory($logPath);
                $this->line('  ✅ Application logs cleared');
            }
        }

        // Clear bootstrap cache
        $bootstrapCache = base_path('bootstrap/cache');
        if (File::exists($bootstrapCache)) {
            File::cleanDirectory($bootstrapCache);
            $this->line('  ✅ Bootstrap cache cleared');
        }

        $this->info('🔄 System reset completed!');
        $this->line('Please restart your web server for full effect.');
    }

    /**
     * Show system status
     */
    private function showStatus()
    {
        $this->info('📊 System Cache Status');
        $this->line(str_repeat('=', 30));

        $status = [
            'Environment' => config('app.env'),
            'Cache Driver' => config('cache.default'),
            'Debug Mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'Routes Cached' => app()->routesAreCached() ? 'Yes' : 'No',
            'Config Cached' => app()->configurationIsCached() ? 'Yes' : 'No',
            'Events Cached' => app()->eventsAreCached() ? 'Yes' : 'No',
        ];

        foreach ($status as $key => $value) {
            $this->line(sprintf('%-20s: %s', $key, $value));
        }

        $this->line('');
        $this->info('📁 Storage Paths');
        $this->line(str_repeat('-', 30));
        
        $paths = [
            'Storage' => storage_path(),
            'Cache' => storage_path('framework/cache'),
            'Views' => storage_path('framework/views'),
            'Sessions' => storage_path('framework/sessions'),
            'Logs' => storage_path('logs'),
        ];

        foreach ($paths as $key => $path) {
            $exists = File::exists($path) ? '✓' : '✗';
            $this->line(sprintf('%-12s: %s %s', $key, $exists, $path));
        }
    }

    /**
     * Clear specific cache type
     */
    private function clearSpecificType()
    {
        $type = $this->option('type') ?? $this->choice(
            'Which cache type would you like to clear?',
            ['cache', 'route', 'config', 'view', 'event', 'compiled'],
            'cache'
        );

        $this->info("🧹 Clearing {$type} cache...");

        $commands = [
            'cache' => 'cache:clear',
            'route' => 'route:clear',
            'config' => 'config:clear',
            'view' => 'view:clear',
            'event' => 'event:clear',
            'compiled' => 'clear-compiled',
        ];

        if (isset($commands[$type])) {
            try {
                Artisan::call($commands[$type]);
                $this->info("✅ {$type} cache cleared successfully!");
            } catch (\Exception $e) {
                $this->error("❌ Failed to clear {$type} cache: " . $e->getMessage());
            }
        } else {
            $this->error("Invalid cache type: {$type}");
        }
    }
}