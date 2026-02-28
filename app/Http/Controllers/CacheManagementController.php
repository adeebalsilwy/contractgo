<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CacheManagementController extends Controller
{
    /**
     * Display the cache management dashboard
     */
    public function index()
    {
        $this->authorize('manage_system');
        
        return view('admin.cache-management');
    }

    /**
     * Get system cache status
     */
    public function getStatus()
    {
        $this->authorize('manage_system');
        
        try {
            $status = [
                'cache_driver' => config('cache.default'),
                'cache_enabled' => config('cache.default') !== 'array',
                'route_cached' => app()->routesAreCached(),
                'config_cached' => app()->configurationIsCached(),
                'events_cached' => app()->eventsAreCached(),
                'environment' => config('app.env'),
                'debug_mode' => config('app.debug'),
                'storage_path' => storage_path(),
                'cache_path' => storage_path('framework/cache'),
                'view_path' => storage_path('framework/views'),
                'session_path' => storage_path('framework/sessions'),
                'log_path' => storage_path('logs'),
            ];

            return response()->json([
                'status' => 'success',
                'data' => $status,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Cache status check failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve cache status',
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Clear all cache types
     */
    public function clearAll()
    {
        $this->authorize('manage_system');
        
        try {
            Log::info('Full cache clear initiated by admin: ' . auth()->user()->name);

            // Clear all cache types
            $commands = [
                'cache:clear' => 'Application Cache',
                'route:clear' => 'Route Cache',
                'config:clear' => 'Config Cache',
                'view:clear' => 'View Cache',
                'event:clear' => 'Event Cache',
                'clear-compiled' => 'Compiled Files',
                'optimize:clear' => 'Optimization Cache'
            ];

            $results = [];
            foreach ($commands as $command => $description) {
                try {
                    Artisan::call($command);
                    $results[$command] = 'success';
                    Log::info("{$description} cleared successfully");
                } catch (\Exception $e) {
                    $results[$command] = 'failed: ' . $e->getMessage();
                    Log::error("Failed to clear {$description}: " . $e->getMessage());
                }
            }

            // Clear storage directories
            $storageDirs = [
                'framework/cache',
                'framework/views',
                'framework/sessions'
            ];

            foreach ($storageDirs as $dir) {
                $path = storage_path($dir);
                if (File::exists($path)) {
                    File::cleanDirectory($path);
                    Log::info("Storage directory {$dir} cleared");
                }
            }

            Log::info('Full cache clear completed successfully');

            return response()->json([
                'status' => 'success',
                'message' => 'All cache cleared successfully',
                'commands_executed' => $results,
                'storage_directories_cleared' => $storageDirs,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Full cache clear failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to clear all cache',
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Clear specific cache type
     */
    public function clearType($type)
    {
        $this->authorize('manage_system');
        
        $validTypes = ['cache', 'route', 'config', 'view', 'event', 'compiled'];
        
        if (!in_array($type, $validTypes)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid cache type',
                'valid_types' => $validTypes,
                'timestamp' => now()->toISOString()
            ], 400);
        }

        try {
            Log::info("Cache type '{$type}' clear initiated by admin: " . auth()->user()->name);

            $commands = [
                'cache' => 'cache:clear',
                'route' => 'route:clear',
                'config' => 'config:clear',
                'view' => 'view:clear',
                'event' => 'event:clear',
                'compiled' => 'clear-compiled'
            ];

            $command = $commands[$type];
            Artisan::call($command);

            Log::info("Cache type '{$type}' cleared successfully");

            return response()->json([
                'status' => 'success',
                'message' => "Cache type '{$type}' cleared successfully",
                'cache_type' => $type,
                'command_executed' => $command,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error("Cache type '{$type}' clear failed: " . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => "Failed to clear cache type '{$type}'",
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Optimize system
     */
    public function optimize()
    {
        $this->authorize('manage_system');
        
        try {
            Log::info('System optimization initiated by admin: ' . auth()->user()->name);

            // Clear all cache first
            $this->clearAll();

            // Apply optimizations based on environment
            if (config('app.env') === 'production') {
                $optimizations = [
                    'optimize' => 'Application Optimization',
                    'route:cache' => 'Route Caching',
                    'config:cache' => 'Config Caching',
                    'event:cache' => 'Event Caching'
                ];

                $results = [];
                foreach ($optimizations as $command => $description) {
                    try {
                        Artisan::call($command);
                        $results[$command] = 'success';
                        Log::info("{$description} applied successfully");
                    } catch (\Exception $e) {
                        $results[$command] = 'failed: ' . $e->getMessage();
                        Log::error("Failed to apply {$description}: " . $e->getMessage());
                    }
                }

                Log::info('Production optimization completed');

                return response()->json([
                    'status' => 'success',
                    'message' => 'System optimized for production',
                    'environment' => 'production',
                    'optimizations_applied' => $results,
                    'timestamp' => now()->toISOString()
                ]);
            } else {
                Log::info('Development environment - optimization completed (cache cleared only)');

                return response()->json([
                    'status' => 'success',
                    'message' => 'System optimized for development',
                    'environment' => 'development',
                    'actions_performed' => ['cache_cleared', 'development_mode'],
                    'timestamp' => now()->toISOString()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('System optimization failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'System optimization failed',
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Complete system reset
     */
    public function completeReset()
    {
        $this->authorize('manage_system');
        
        try {
            Log::info('Complete system reset initiated by admin: ' . auth()->user()->name);

            // Clear all cache and files
            $this->clearAll();

            // Additional cleanup
            $additionalCleanup = [
                'bootstrap/cache' => base_path('bootstrap/cache'),
                'storage/logs' => storage_path('logs')
            ];

            $cleanupResults = [];
            foreach ($additionalCleanup as $name => $path) {
                if (File::exists($path)) {
                    try {
                        File::cleanDirectory($path);
                        $cleanupResults[$name] = 'success';
                        Log::info("{$name} directory cleared");
                    } catch (\Exception $e) {
                        $cleanupResults[$name] = 'failed: ' . $e->getMessage();
                        Log::error("Failed to clear {$name}: " . $e->getMessage());
                    }
                }
            }

            Log::info('Complete system reset completed');

            return response()->json([
                'status' => 'success',
                'message' => 'Complete system reset completed successfully',
                'additional_cleanup' => $cleanupResults,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Complete system reset failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Complete system reset failed',
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }
}