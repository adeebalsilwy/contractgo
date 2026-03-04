<?php
/**
 * Test the users list endpoint
 */

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Users List Endpoint\n";
echo "==========================\n\n";

try {
    // Login as first user
    $user = App\Models\User::first();
    if ($user) {
        Auth::login($user);
        echo "Logged in as: " . $user->email . "\n";
        
        // Test the UserController list method
        $controller = new \App\Http\Controllers\UserController();
        $request = Request::create('/users/list', 'GET', [
            'page' => 1,
            'limit' => 10,
            'sort' => 'id',
            'order' => 'desc'
        ]);
        
        echo "Calling UserController@list...\n";
        $response = $controller->list($request);
        
        if ($response) {
            echo "✅ UserController@list: SUCCESS\n";
            echo "Response type: " . get_class($response) . "\n";
            
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);
                echo "Response data keys: " . implode(', ', array_keys($data)) . "\n";
                if (isset($data['total'])) {
                    echo "Total records: " . $data['total'] . "\n";
                }
                if (isset($data['rows'])) {
                    echo "Rows count: " . count($data['rows']) . "\n";
                }
            }
        } else {
            echo "❌ UserController@list: FAILED - No response\n";
        }
    } else {
        echo "❌ No users found in database\n";
    }
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}