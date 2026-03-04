<?php
/**
 * Targeted Endpoint Testing for Contract Management System
 * Focuses on the specific endpoints we've been working on
 */

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🎯 TARGETED ENDPOINT TESTING\n";
echo "============================\n\n";

$testResults = [];
$passed = 0;
$failed = 0;

/**
 * Test UserController endpoints
 */
echo "👥 Testing UserController Endpoints...\n";

try {
    $userController = new \App\Http\Controllers\UserController();
    
    // Test list method
    $request = Request::create('/users/list', 'GET');
    $response = $userController->list($request);
    
    if ($response) {
        $testResults['UserController@list'] = ['status' => 'PASS', 'type' => 'Controller Method'];
        $passed++;
        echo "  ✅ UserController@list: PASS\n";
    } else {
        $testResults['UserController@list'] = ['status' => 'FAIL', 'type' => 'Controller Method'];
        $failed++;
        echo "  ❌ UserController@list: FAIL\n";
    }
} catch (Exception $e) {
    $testResults['UserController@list'] = ['status' => 'ERROR', 'type' => 'Controller Method', 'error' => $e->getMessage()];
    $failed++;
    echo "  ❌ UserController@list: ERROR - " . $e->getMessage() . "\n";
}

echo "\n";

/**
 * Test ContractController endpoints
 */
echo "📝 Testing ContractController Endpoints...\n";

try {
    $contractController = new \App\Http\Controllers\ContractController();
    
    // Test list method
    $request = Request::create('/contracts/list', 'GET');
    $response = $contractController->list($request);
    
    if ($response) {
        $testResults['ContractController@list'] = ['status' => 'PASS', 'type' => 'Controller Method'];
        $passed++;
        echo "  ✅ ContractController@list: PASS\n";
    } else {
        $testResults['ContractController@list'] = ['status' => 'FAIL', 'type' => 'Controller Method'];
        $failed++;
        echo "  ❌ ContractController@list: FAIL\n";
    }
    
    // Test create method
    $request = Request::create('/contracts/create', 'GET');
    $response = $contractController->create($request);
    
    if ($response) {
        $testResults['ContractController@create'] = ['status' => 'PASS', 'type' => 'Controller Method'];
        $passed++;
        echo " ✅ ContractController@create: PASS\n";
    } else {
        $testResults['ContractController@create'] = ['status' => 'FAIL', 'type' => 'Controller Method'];
        $failed++;
        echo " ❌ ContractController@create: FAIL\n";
    }
    
} catch (Exception $e) {
    $testResults['ContractController'] = ['status' => 'ERROR', 'type' => 'Controller', 'error' => $e->getMessage()];
    $failed++;
    echo "  ❌ ContractController: ERROR - " . $e->getMessage() . "\n";
}

echo "\n";

/**
 * Test EstimatesInvoicesController endpoints
 */
echo "🧾 EstimatesInvoicesController Endpoints...\n";

try {
    $estimatesController = new \App\Http\Controllers\EstimatesInvoicesController();
    
    // Test list method
    $request = Request::create('/estimates-invoices/list', 'GET');
    $response = $estimatesController->list($request);
    
    if ($response) {
        $testResults['EstimatesInvoicesController@list'] = ['status' => 'PASS', 'type' => 'Controller Method'];
        $passed++;
        echo " ✅ EstimatesInvoicesController@list: PASS\n";
    } else {
        $testResults['EstimatesInvoicesController@list'] = ['status' => 'FAIL', 'type' => 'Controller Method'];
        $failed++;
        echo " ❌ EstimatesInvoicesController@list: FAIL\n";
    }
    
} catch (Exception $e) {
    $testResults['EstimatesInvoicesController@list'] = ['status' => 'ERROR', 'type' => 'Controller Method', 'error' => $e->getMessage()];
    $failed++;
    echo " ❌ EstimatesInvoicesController@list: ERROR - " . $e->getMessage() . "\n";
}

echo "\n";

/**
 * Test API endpoints
 */
echo "🔌 Testing API Endpoints...\n";

// Test contracts API
try {
    $request = Request::create('/api/contracts/list', 'GET');
    $response = app()->handle($request);
    
    if ($response->getStatusCode() < 400) {
        $testResults['API /api/contracts/list'] = ['status' => 'PASS', 'type' => 'API Endpoint', 'status_code' => $response->getStatusCode()];
        $passed++;
        echo "  ✅ /api/contracts/list: PASS (Status: {$response->getStatusCode()})\n";
    } else {
        $testResults['API /api/contracts/list'] = ['status' => 'FAIL', 'type' => 'API Endpoint', 'status_code' => $response->getStatusCode()];
        $failed++;
        echo "  ❌ /api/contracts/list: FAIL (Status: {$response->getStatusCode()})\n";
    }
} catch (Exception $e) {
    $testResults['API /api/contracts/list'] = ['status' => 'ERROR', 'type' => 'API Endpoint', 'error' => $e->getMessage()];
    $failed++;
    echo " ❌ /api/contracts/list: ERROR - " . $e->getMessage() . "\n";
}

// Test estimates-invoices API
try {
    $request = Request::create('/api/estimates-invoices/list', 'GET');
    $response = app()->handle($request);
    
    if ($response->getStatusCode() < 400) {
        $testResults['API /api/estimates-invoices/list'] = ['status' => 'PASS', 'type' => 'API Endpoint', 'status_code' => $response->getStatusCode()];
        $passed++;
        echo " ✅ /api/estimates-invoices/list: PASS (Status: {$response->getStatusCode()})\n";
    } else {
        $testResults['API /api/estimates-invoices/list'] = ['status' => 'FAIL', 'type' => 'API Endpoint', 'status_code' => $response->getStatusCode()];
        $failed++;
        echo " ❌ /api/estimates-invoices/list: FAIL (Status: {$response->getStatusCode()})\n";
    }
} catch (Exception $e) {
    $testResults['API /api/estimates-invoices/list'] = ['status' => 'ERROR', 'type' => 'API Endpoint', 'error' => $e->getMessage()];
    $failed++;
    echo " ❌ /api/estimates-invoices/list: ERROR - " . $e->getMessage() . "\n";
}

// Test users API
try {
    $request = Request::create('/api/users/list', 'GET');
    $response = app()->handle($request);
    
    if ($response->getStatusCode() < 400) {
        $testResults['API /api/users/list'] = ['status' => 'PASS', 'type' => 'API Endpoint', 'status_code' => $response->getStatusCode()];
        $passed++;
        echo " ✅ /api/users/list: PASS (Status: {$response->getStatusCode()})\n";
    } else {
        $testResults['API /api/users/list'] = ['status' => 'FAIL', 'type' => 'API Endpoint', 'status_code' => $response->getStatusCode()];
        $failed++;
        echo " ❌ /api/users/list: FAIL (Status: {$response->getStatusCode()})\n";
    }
} catch (Exception $e) {
    $testResults['API /api/users/list'] = ['status' => 'ERROR', 'type' => 'API Endpoint', 'error' => $e->getMessage()];
    $failed++;
    echo " ❌ /api/users/list: ERROR - " . $e->getMessage() . "\n";
}

echo "\n";

/**
 * Test AJAX endpoints
 */
echo "⚡ Testing AJAX Endpoints...\n";

// Test AJAX contracts
try {
    $request = Request::create('/ajax/contracts/list', 'GET');
    $response = app()->handle($request);
    
    if ($response->getStatusCode() < 400) {
        $testResults['AJAX /ajax/contracts/list'] = ['status' => 'PASS', 'type' => 'AJAX Endpoint', 'status_code' => $response->getStatusCode()];
        $passed++;
        echo " ✅ /ajax/contracts/list: PASS (Status: {$response->getStatusCode()})\n";
    } else {
        $testResults['AJAX /ajax/contracts/list'] = ['status' => 'FAIL', 'type' => 'AJAX Endpoint', 'status_code' => $response->getStatusCode()];
        $failed++;
        echo "  ❌ /ajax/contracts/list: FAIL (Status: {$response->getStatusCode()})\n";
    }
} catch (Exception $e) {
    $testResults['AJAX /ajax/contracts/list'] = ['status' => 'ERROR', 'type' => 'AJAX Endpoint', 'error' => $e->getMessage()];
    $failed++;
    echo " ❌ /ajax/contracts/list: ERROR - " . $e->getMessage() . "\n";
}

// Test AJAX estimates-invoices
try {
    $request = Request::create('/ajax/estimates-invoices/list', 'GET');
    $response = app()->handle($request);
    
    if ($response->getStatusCode() < 400) {
        $testResults['AJAX /ajax/estimates-invoices/list'] = ['status' => 'PASS', 'type' => 'AJAX Endpoint', 'status_code' => $response->getStatusCode()];
        $passed++;
        echo " ✅ /ajax/estimates-invoices/list: PASS (Status: {$response->getStatusCode()})\n";
    } else {
        $testResults['AJAX /ajax/estimates-invoices/list'] = ['status' => 'FAIL', 'type' => 'AJAX Endpoint', 'status_code' => $response->getStatusCode()];
        $failed++;
        echo " ❌ /ajax/estimates-invoices/list: FAIL (Status: {$response->getStatusCode()})\n";
    }
} catch (Exception $e) {
    $testResults['AJAX /ajax/estimates-invoices/list'] = ['status' => 'ERROR', 'type' => 'AJAX Endpoint', 'error' => $e->getMessage()];
    $failed++;
    echo " ❌ /ajax/estimates-invoices/list: ERROR - " . $e->getMessage() . "\n";
}

echo "\n";

/**
 * Test View Rendering
 */
echo "🖥️ Testing View Rendering...\n";

// Test contracts list view
try {
    $view = view('contracts.list');
    if ($view) {
        $testResults['View contracts.list'] = ['status' => 'PASS', 'type' => 'View'];
        $passed++;
        echo "  ✅ contracts.list view: PASS\n";
    } else {
        $testResults['View contracts.list'] = ['status' => 'FAIL', 'type' => 'View'];
        $failed++;
        echo "  ❌ contracts.list view: FAIL\n";
    }
} catch (Exception $e) {
    $testResults['View contracts.list'] = ['status' => 'ERROR', 'type' => 'View', 'error' => $e->getMessage()];
    $failed++;
    echo " ❌ contracts.list view: ERROR - " . $e->getMessage() . "\n";
}

// Test estimates-invoices list view
try {
    $view = view('estimates-invoices.list');
    if ($view) {
        $testResults['View estimates-invoices.list'] = ['status' => 'PASS', 'type' => 'View'];
        $passed++;
        echo " ✅ estimates-invoices.list view: PASS\n";
    } else {
        $testResults['View estimates-invoices.list'] = ['status' => 'FAIL', 'type' => 'View'];
        $failed++;
        echo " ❌ estimates-invoices.list view: FAIL\n";
    }
} catch (Exception $e) {
    $testResults['View estimates-invoices.list'] = ['status' => 'ERROR', 'type' => 'View', 'error' => $e->getMessage()];
    $failed++;
    echo "  ❌ estimates-invoices.list view: ERROR - " . $e->getMessage() . "\n";
}

echo "\n";

/**
 * Test JavaScript Files
 */
echo "📜 Testing JavaScript Files...\n";

$jsFiles = [
    'public/assets/js/pages/contracts-enhanced.js',
    'public/assets/js/pages/estimates-invoices-enhanced.js'
];

foreach ($jsFiles as $jsFile) {
    if (file_exists($jsFile)) {
        $content = file_get_contents($jsFile);
        if (!empty($content)) {
            $testResults["JS {$jsFile}"] = ['status' => 'PASS', 'type' => 'JavaScript File'];
            $passed++;
            echo "  ✅ {$jsFile}: PASS\n";
        } else {
            $testResults["JS {$jsFile}"] = ['status' => 'FAIL', 'type' => 'JavaScript File'];
            $failed++;
            echo "  ❌ {$jsFile}: FAIL (Empty file)\n";
        }
    } else {
        $testResults["JS {$jsFile}"] = ['status' => 'FAIL', 'type' => 'JavaScript File'];
        $failed++;
        echo "  ❌ {$jsFile}: FAIL (File not found)\n";
    }
}

echo "\n";

/**
 * Final Report
 */
$totalTests = $passed + $failed;
$successRate = $totalTests > 0 ? round(($passed / $totalTests) * 100, 1) : 0;

echo "🎯 TARGETED TESTING RESULTS\n";
echo "===========================\n";
echo "Total Tests: {$totalTests}\n";
echo "Passed: {$passed}✅\n";
echo "Failed: {$failed}❌\n";
echo "Success Rate: {$successRate}%\n\n";

if ($failed > 0) {
    echo "📋 FAILED TESTS DETAILS\n";
    echo "=======================\n";
    foreach ($testResults as $testName => $result) {
        if ($result['status'] !== 'PASS') {
            echo "❌ {$testName}: {$result['status']}";
            if (isset($result['error'])) {
                echo " - {$result['error']}";
            }
            if (isset($result['status_code'])) {
                echo " (HTTP {$result['status_code']})";
            }
            echo "\n";
        }
    }
    echo "\n";
}

// Save detailed results
$resultsFile = 'targeted_test_results_' . date('Y-m-d_H-i-s') . '.json';
file_put_contents($resultsFile, json_encode([
    'timestamp' => date('Y-m-d H:i:s'),
    'total_tests' => $totalTests,
    'passed_tests' => $passed,
    'failed_tests' => $failed,
    'success_rate' => $successRate,
    'detailed_results' => $testResults
], JSON_PRETTY_PRINT));

echo "💾 Detailed results saved to: {$resultsFile}\n";

if ($successRate == 100) {
    echo "\n🎉 All targeted tests passed! System is functioning correctly.\n";
} else {
    echo "\n⚠️  Some tests failed. Please review the failed endpoints above.\n";
}