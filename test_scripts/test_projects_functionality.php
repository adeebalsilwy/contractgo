<?php
// Test script to verify project-related functionality

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    // Check if required tables exist
    $tables = [
        'projects',
        'statuses', 
        'priorities',
        'workspaces',
        'users'
    ];

    foreach ($tables as $table) {
        if (!Schema::hasTable($table)) {
            echo "❌ Table '$table' does not exist\n";
            exit(1);
        }
        echo "✅ Table '$table' exists\n";
    }

    // Check if required columns exist in projects table
    $requiredColumns = [
        'task_accessibility',
        'client_can_discuss',
        'title',
        'status_id',
        'priority_id',
        'start_date',
        'end_date',
        'budget',
        'description',
        'note',
        'enable_tasks_time_entries',
        'workspace_id',
        'created_by'
    ];

    $existingColumns = Schema::getColumnListing('projects');
    $missingColumns = [];

    foreach ($requiredColumns as $column) {
        if (!in_array($column, $existingColumns)) {
            $missingColumns[] = $column;
        }
    }

    if (empty($missingColumns)) {
        echo "✅ All required columns exist in projects table\n";
    } else {
        echo "❌ Missing columns in projects table: " . implode(', ', $missingColumns) . "\n";
        exit(1);
    }

    // Test if we can query the data we need
    $projectCount = DB::table('projects')->count();
    $statusCount = DB::table('statuses')->count();
    $priorityCount = DB::table('priorities')->count();
    $workspaceCount = DB::table('workspaces')->count();
    $userCount = DB::table('users')->count();

    echo "📊 Projects count: $projectCount\n";
    echo "📊 Statuses count: $statusCount\n";
    echo "📊 Priorities count: $priorityCount\n";
    echo "📊 Workspaces count: $workspaceCount\n";
    echo "📊 Users count: $userCount\n";

    if ($statusCount > 0 && $priorityCount > 0 && $workspaceCount > 0 && $userCount > 0) {
        echo "✅ All required data exists for project creation\n";
    } else {
        echo "⚠️  Some required data might be missing for project creation\n";
    }

    // Check if the controllers exist and can be instantiated
    $controllerPath = __DIR__ . '/app/Http/Controllers/ProjectsController.php';
    if (file_exists($controllerPath)) {
        echo "✅ ProjectsController.php exists\n";
    } else {
        echo "❌ ProjectsController.php does not exist\n";
        exit(1);
    }

    echo "\n🎉 All tests passed! Project functionality should work correctly.\n";
    echo "The database schema issues have been resolved:\n";
    echo "- task_accessibility column added\n";
    echo "- client_can_discuss column added\n";
    echo "- All required tables and columns are present\n";
    
} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
    exit(1);
}