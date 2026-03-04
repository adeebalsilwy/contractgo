# Auto Workspace Assignment Middleware

## Overview

This professional middleware automatically assigns users to workspaces and links orphaned data to the first available workspace in your Laravel application.

## Features

1. **Automatic User Assignment**: Automatically assigns authenticated users to the first workspace if they're not already assigned
2. **Orphaned Data Linking**: Links any data records without workspace associations to the primary workspace
3. **Event-Driven Processing**: Works on user login/registration events and HTTP requests
4. **Comprehensive Coverage**: Handles multiple models including contracts, projects, tasks, leads, and more

## Components

### 1. Middleware (`AutoWorkspaceAssignment`)
- Located: `app/Http/Middleware/AutoWorkspaceAssignment.php`
- Automatically runs on all web requests for authenticated users
- Handles both user assignment and orphaned data linking

### 2. Console Command (`ProcessOrphanedWorkspaceData`)
- Located: `app/Console/Commands/ProcessOrphanedWorkspaceData.php`
- Command: `php artisan workspace:assign-orphaned-data`
- Options:
  - `--workspace-id=ID`: Specify target workspace ID
  - `--verbose`: Show detailed output

### 3. Service Provider (`WorkspaceServiceProvider`)
- Located: `app/Providers/WorkspaceServiceProvider.php`
- Handles event-based assignment on user login/registration
- Automatically registered in the application

## Usage

### Automatic Usage
The middleware is automatically registered in the web middleware group and will run on all authenticated web requests.

### Manual Command Usage
```bash
# Process all orphaned data with first workspace
php artisan workspace:assign-orphaned-data

# Process with specific workspace
php artisan workspace:assign-orphaned-data --workspace-id=1

# Verbose output
php artisan workspace:assign-orphaned-data --verbose
```

### Manual Middleware Usage
You can also apply the middleware to specific routes:

```php
// In routes/web.php
Route::middleware(['auto.workspace.assignment'])->group(function () {
    // Your routes here
});
```

## Models Supported

The middleware automatically handles the following models:
- Users
- Contracts
- Projects
- Tasks
- Leads
- Contract Quantities
- Journal Entries
- Contract Amendments
- Contract Approvals

## How It Works

1. **User Assignment Process**:
   - Checks if authenticated user belongs to any workspace
   - If not, assigns to the first workspace (primary or first available)
   - Sets the workspace as user's default workspace

2. **Orphaned Data Process**:
   - Identifies records with `NULL` workspace_id
   - Links them to the primary workspace or first available workspace
   - Logs all operations for monitoring

3. **Event Handling**:
   - Listens for user login and registration events
   - Automatically triggers workspace assignment
   - Provides backup assignment mechanism

## Configuration

The middleware uses the following priority for workspace selection:
1. Workspace marked as `is_primary = 1`
2. First workspace ordered by ID

## Logging

All operations are logged using Laravel's logging system:
- User assignments: `info` level
- Data linking: `info` level
- Errors: `error` level
- Warnings: `warning` level

## Testing

Run the included feature tests:
```bash
php artisan test tests/Feature/AutoWorkspaceAssignmentTest.php
```

## Customization

To add support for additional models:
1. Add the linking logic in `AutoWorkspaceAssignment::linkOrphanedDataToWorkspace()`
2. Add the processing method for your model
3. Update the console command to handle the new model

## Security

- Only runs for authenticated users
- Respects existing workspace assignments
- Uses proper database transactions
- Includes comprehensive error handling