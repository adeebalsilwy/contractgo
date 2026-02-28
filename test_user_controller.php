<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

// Login as first user
$user = App\Models\User::first();
if ($user) {
    Auth::login($user);
    echo "Logged in as: " . $user->email . "\n";
    
    // Test the UserController list method
    $controller = new UserController();
    $request = Request::create('/users/list', 'GET', [
        'page' => 1,
        'limit' => 10,
        'sort' => 'id',
        'order' => 'desc'
    ]);
    
    try {
        $response = $controller->list($request);
        echo "UserController@list test successful!\n";
        echo "Response type: " . get_class($response) . "\n";
        
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $data = $response->getData(true);
            echo "Response data keys: " . implode(', ', array_keys($data)) . "\n";
            if (isset($data['total'])) {
                echo "Total records: " . $data['total'] . "\n";
            }
            if (isset($data['rows'])) {
                echo "Rows count: " . count($data['rows']) . "\n";
                if (count($data['rows']) > 0) {
                    echo "First row keys: " . implode(', ', array_keys($data['rows'][0])) . "\n";
                }
            }
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Trace: " . $e->getTraceAsString() . "\n";
    }
} else {
    echo "No users found in database\n";
}
?>