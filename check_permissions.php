<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Spatie\Permission\Models\Permission;

echo "Available permissions:\n";
$permissions = Permission::pluck('name')->toArray();
foreach ($permissions as $permission) {
    echo "- " . $permission . "\n";
}

echo "\nAvailable roles:\n";
$roles = Spatie\Permission\Models\Role::pluck('name')->toArray();
foreach ($roles as $role) {
    echo "- " . $role . "\n";
}

// Check if the user has the required permissions
$user = App\Models\User::first();
if ($user) {
    echo "\nUser: " . $user->email . " (ID: " . $user->id . ")\n";
    echo "User roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
    
    $requiredPermissions = ['edit_users', 'delete_users', 'manage_projects', 'manage_tasks'];
    foreach ($requiredPermissions as $permission) {
        $hasPermission = $user->can($permission);
        echo "Can " . $permission . ": " . ($hasPermission ? 'YES' : 'NO') . "\n";
    }
}
?>