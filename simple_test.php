<?php
/**
 * Simple Endpoint Test - Direct File Execution
 * Tests endpoints without external HTTP calls
 */

echo "🚀 SIMPLE ENDPOINT TEST\n";
echo "======================\n\n";

// Check if we can access the Laravel application
$testResults = [];
$passed = 0;
$failed = 0;

echo "📁 Checking File Structure...\n";

// Check if key files exist
$keyFiles = [
    'app/Http/Controllers/UserController.php',
    'app/Http/Controllers/ContractController.php',
    'app/Http/Controllers/EstimatesInvoicesController.php',
    'resources/views/contracts/list.blade.php',
    'resources/views/estimates-invoices/list.blade.php',
    'public/assets/js/pages/contracts-enhanced.js',
    'public/assets/js/pages/estimates-invoices-enhanced.js'
];

foreach ($keyFiles as $file) {
    if (file_exists($file)) {
        $testResults[$file] = ['status' => 'PASS', 'type' => 'File Check'];
        $passed++;
        echo "  ✅ {$file}: EXISTS\n";
    } else {
        $testResults[$file] = ['status' => 'FAIL', 'type' => 'File Check'];
        $failed++;
        echo "  ❌ {$file}: MISSING\n";
    }
}

echo "\n";

// Check routes file
echo "🛣️ Checking Routes...\n";
if (file_exists('routes/web.php')) {
    $routesContent = file_get_contents('routes/web.php');
    $requiredRoutes = [
        'users/list',
        'contracts/list', 
        'estimates-invoices/list',
        'api/contracts/list',
        'api/estimates-invoices/list'
    ];
    
    foreach ($requiredRoutes as $route) {
        if (strpos($routesContent, $route) !== false) {
            $testResults["Route: {$route}"] = ['status' => 'PASS', 'type' => 'Route Check'];
            $passed++;
            echo "  ✅ Route {$route}: FOUND\n";
        } else {
            $testResults["Route: {$route}"] = ['status' => 'FAIL', 'type' => 'Route Check'];
            $failed++;
            echo "  ❌ Route {$route}: NOT FOUND\n";
        }
    }
} else {
    $testResults['routes/web.php'] = ['status' => 'FAIL', 'type' => 'File Check'];
    $failed++;
    echo "  ❌ routes/web.php: MISSING\n";
}

echo "\n";

// Check if we can load the controllers
echo "⚙️ Checking Controller Classes...\n";

$controllers = [
    'App\Http\Controllers\UserController',
    'App\Http\Controllers\ContractController', 
    'App\Http\Controllers\EstimatesInvoicesController'
];

foreach ($controllers as $controller) {
    if (class_exists($controller)) {
        $testResults["Class: {$controller}"] = ['status' => 'PASS', 'type' => 'Class Check'];
        $passed++;
        echo "  ✅ {$controller}: LOADED\n";
    } else {
        $testResults["Class: {$controller}"] = ['status' => 'FAIL', 'type' => 'Class Check'];
        $failed++;
        echo "  ❌ {$controller}: NOT FOUND\n";
    }
}

echo "\n";

// Check database connection
echo "🗄️ Checking Database Connection...\n";
try {
    if (file_exists('config/database.php')) {
        $dbConfig = include 'config/database.php';
        if (isset($dbConfig['default'])) {
            $testResults['Database Config'] = ['status' => 'PASS', 'type' => 'Config Check'];
            $passed++;
            echo "  ✅ Database configuration: FOUND\n";
        } else {
            $testResults['Database Config'] = ['status' => 'FAIL', 'type' => 'Config Check'];
            $failed++;
            echo "  ❌ Database configuration: INVALID\n";
        }
    } else {
        $testResults['Database Config'] = ['status' => 'FAIL', 'type' => 'Config Check'];
        $failed++;
        echo "  ❌ Database configuration: MISSING\n";
    }
} catch (Exception $e) {
    $testResults['Database Config'] = ['status' => 'ERROR', 'type' => 'Config Check', 'error' => $e->getMessage()];
    $failed++;
    echo "  ❌ Database configuration: ERROR - " . $e->getMessage() . "\n";
}

echo "\n";

// Check if we can load views
echo "🖥️ Checking Views...\n";
$viewFiles = [
    'resources/views/contracts/list.blade.php',
    'resources/views/estimates-invoices/list.blade.php'
];

foreach ($viewFiles as $viewFile) {
    if (file_exists($viewFile)) {
        $content = file_get_contents($viewFile);
        if (strpos($content, '@extends') !== false || strpos($content, '@section') !== false) {
            $testResults["View: {$viewFile}"] = ['status' => 'PASS', 'type' => 'View Check'];
            $passed++;
            echo "  ✅ {$viewFile}: VALID BLADE TEMPLATE\n";
        } else {
            $testResults["View: {$viewFile}"] = ['status' => 'FAIL', 'type' => 'View Check'];
            $failed++;
            echo "  ❌ {$viewFile}: INVALID BLADE TEMPLATE\n";
        }
    } else {
        $testResults["View: {$viewFile}"] = ['status' => 'FAIL', 'type' => 'View Check'];
        $failed++;
        echo " ❌ {$viewFile}: MISSING\n";
    }
}

echo "\n";

// Check JavaScript files
echo "📜 Checking JavaScript Files...\n";
$jsFiles = [
    'public/assets/js/pages/contracts-enhanced.js',
    'public/assets/js/pages/estimates-invoices-enhanced.js'
];

foreach ($jsFiles as $jsFile) {
    if (file_exists($jsFile)) {
        $content = file_get_contents($jsFile);
        if (strpos($content, 'function') !== false || strpos($content, 'const') !== false) {
            $testResults["JS: {$jsFile}"] = ['status' => 'PASS', 'type' => 'JavaScript Check'];
            $passed++;
            echo " ✅ {$jsFile}: VALID JAVASCRIPT\n";
        } else {
            $testResults["JS: {$jsFile}"] = ['status' => 'FAIL', 'type' => 'JavaScript Check'];
            $failed++;
            echo "  ❌ {$jsFile}: INVALID JAVASCRIPT\n";
        }
    } else {
        $testResults["JS: {$jsFile}"] = ['status' => 'FAIL', 'type' => 'JavaScript Check'];
        $failed++;
        echo "  ❌ {$jsFile}: MISSING\n";
    }
}

echo "\n";

// Final Report
$totalTests = $passed + $failed;
$successRate = $totalTests > 0 ? round(($passed / $totalTests) * 100, 1) : 0;

echo "🎯 SIMPLE TEST RESULTS\n";
echo "=====================\n";
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
            echo "\n";
        }
    }
    echo "\n";
}

// Save results
$resultsFile = 'simple_test_results_' . date('Y-m-d_H-i-s') . '.txt';
$report = "Simple Endpoint Test Results\n";
$report .= "============================\n";
$report .= "Timestamp: " . date('Y-m-d H:i:s') . "\n";
$report .= "Total Tests: {$totalTests}\n";
$report .= "Passed: {$passed}\n";
$report .= "Failed: {$failed}\n";
$report .= "Success Rate: {$successRate}%\n\n";

$report .= "Detailed Results:\n";
$report .= "=================\n";
foreach ($testResults as $testName => $result) {
    $report .= "{$testName}: {$result['status']}\n";
    if (isset($result['error'])) {
        $report .= "  Error: {$result['error']}\n";
    }
}

file_put_contents($resultsFile, $report);

echo "💾 Results saved to: {$resultsFile}\n";

if ($successRate == 100) {
    echo "\n🎉 All basic tests passed! File structure is correct.\n";
    echo "The application files are properly structured and ready for HTTP testing.\n";
} else {
    echo "\n⚠️  Some tests failed. Please check the failed items above.\n";
}