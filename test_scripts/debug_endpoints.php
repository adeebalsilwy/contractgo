<?php
/**
 * Debug the 500 errors for chat and users endpoints
 */

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 DEBUGGING 500 ERRORS\n";
echo "======================\n\n";

// Test 1: Check if we can access the application
echo "1. Testing Laravel Application Access...\n";
try {
    $user = App\Models\User::first();
    if ($user) {
        echo "✅ Database connection: SUCCESS\n";
        echo "✅ First user found: {$user->email}\n";
        
        // Login the user
        Auth::login($user);
        echo "✅ User logged in successfully\n\n";
    } else {
        echo "❌ No users found in database\n\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 2: Check UserController list method
echo "2. Testing UserController@list method...\n";
try {
    $controller = new \App\Http\Controllers\UserController();
    $request = Request::create('/users/list', 'GET', [
        'page' => 1,
        'limit' => 10,
        'sort' => 'id',
        'order' => 'desc'
    ]);
    
    $response = $controller->list($request);
    
    if ($response instanceof \Illuminate\Http\JsonResponse) {
        $data = $response->getData(true);
        echo "✅ UserController@list: SUCCESS\n";
        echo "   Status Code: " . $response->getStatusCode() . "\n";
        echo "   Response Keys: " . implode(', ', array_keys($data)) . "\n";
        if (isset($data['total'])) {
            echo "   Total Records: " . $data['total'] . "\n";
        }
        if (isset($data['rows'])) {
            echo "   Rows Count: " . count($data['rows']) . "\n";
        }
    } else {
        echo "❌ UserController@list: Unexpected response type\n";
        echo "   Response type: " . get_class($response) . "\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ UserController@list: FAILED\n";
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n\n";
}

// Test 3: Check ChatController methods
echo "3. Testing ChatController methods...\n";
try {
    $controller = new \App\Http\Controllers\ChatController();
    
    // Test getContacts method
    $request = Request::create('/chat/getContacts', 'GET');
    $response = $controller->getContacts($request);
    
    if ($response instanceof \Illuminate\Http\JsonResponse) {
        echo "✅ ChatController@getContacts: SUCCESS\n";
        echo "   Status Code: " . $response->getStatusCode() . "\n";
        $data = $response->getData(true);
        echo "   Response Keys: " . (isset($data) ? implode(', ', array_keys($data)) : 'No data') . "\n";
    } else {
        echo "❌ ChatController@getContacts: Unexpected response type\n";
        echo "   Response type: " . get_class($response) . "\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ ChatController@getContacts: FAILED\n";
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n\n";
}

// Test 4: Check route definitions
echo "4. Checking Route Definitions...\n";
try {
    $routes = [
        '/users/list',
        '/chat/getContacts',
        '/chat/fetchMessages'
    ];
    
    foreach ($routes as $route) {
        $routeExists = \Illuminate\Support\Facades\Route::has(str_replace('/', '.', trim($route, '/')));
        echo ($routeExists ? "✅" : "❌") . " Route {$route}: " . ($routeExists ? "FOUND" : "NOT FOUND") . "\n";
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ Route checking failed: " . $e->getMessage() . "\n\n";
}

// Test 5: Check permissions
echo "5. Checking User Permissions...\n";
try {
    $authUser = Auth::user();
    if ($authUser) {
        $permissions = [
            'edit users',
            'delete users', 
            'manage projects',
            'manage tasks'
        ];
        
        foreach ($permissions as $permission) {
            $hasPermission = $authUser->can($permission);
            echo ($hasPermission ? "✅" : "❌") . " Permission '{$permission}': " . ($hasPermission ? "GRANTED" : "DENIED") . "\n";
        }
    }
    echo "\n";
} catch (Exception $e) {
    echo "❌ Permission checking failed: " . $e->getMessage() . "\n\n";
}

echo "🔍 DEBUGGING COMPLETE\n";
echo "====================\n";