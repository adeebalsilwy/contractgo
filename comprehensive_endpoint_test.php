<?php
/**
 * Comprehensive Endpoint Testing Script
 * Tests all major endpoints in the Laravel application
 * Professional testing with detailed reporting
 */

// Configuration
$baseUrl = 'http://localhost'; // Adjust based on your local setup
$testResults = [];
$startTime = microtime(true);

echo "🚀 Starting Comprehensive Endpoint Testing\n";
echo "==========================================\n\n";

/**
 * Test a single endpoint
 */
function testEndpoint($url, $method = 'GET', $data = [], $headers = []) {
    global $baseUrl;
    
    $fullUrl = $baseUrl . $url;
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    // Set method
    if ($method !== 'GET') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
    }
    
    // Set headers
    $defaultHeaders = [
        'Content-Type: application/x-www-form-urlencoded',
        'User-Agent: ComprehensiveEndpointTester/1.0'
    ];
    
    $allHeaders = array_merge($defaultHeaders, $headers);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    // Parse response
    $headerSize = strpos($response, "\r\n\r\n");
    if ($headerSize === false) {
        $headerSize = strpos($response, "\n\n");
    }
    
    $headersRaw = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize + 4);
    
    return [
        'url' => $fullUrl,
        'method' => $method,
        'http_code' => $httpCode,
        'response_body' => $body,
        'response_headers' => $headersRaw,
        'error' => $error,
        'success' => ($httpCode >= 200 && $httpCode < 400) && empty($error)
    ];
}

/**
 * Test authentication endpoints
 */
echo "🔐 Testing Authentication Endpoints...\n";

// Test login page
$loginTest = testEndpoint('/login');
$testResults['auth_login_page'] = $loginTest;
echo "  Login Page: " . ($loginTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$loginTest['http_code']})\n";

// Test register page
$registerTest = testEndpoint('/register');
$testResults['auth_register_page'] = $registerTest;
echo "  Register Page: " . ($registerTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$registerTest['http_code']})\n";

// Test password reset page
$resetTest = testEndpoint('/password/reset');
$testResults['auth_password_reset'] = $resetTest;
echo "  Password Reset: " . ($resetTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$resetTest['http_code']})\n";

echo "\n";

/**
 * Test dashboard and main pages
 */
echo "📊 Testing Dashboard and Main Pages...\n";

$dashboardTest = testEndpoint('/dashboard');
$testResults['dashboard'] = $dashboardTest;
echo "  Dashboard: " . ($dashboardTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$dashboardTest['http_code']})\n";

$homeTest = testEndpoint('/');
$testResults['home'] = $homeTest;
echo "  Home Page: " . ($homeTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$homeTest['http_code']})\n";

echo "\n";

/**
 * Test user management endpoints
 */
echo "👥 Testing User Management Endpoints...\n";

$usersListTest = testEndpoint('/users/list');
$testResults['users_list'] = $usersListTest;
echo "  Users List: " . ($usersListTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$usersListTest['http_code']})\n";

$usersCreateTest = testEndpoint('/users/create');
$testResults['users_create'] = $usersCreateTest;
echo "  Users Create: " . ($usersCreateTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$usersCreateTest['http_code']})\n";

echo "\n";

/**
 * Test contract management endpoints
 */
echo "📝 Testing Contract Management Endpoints...\n";

$contractsListTest = testEndpoint('/contracts/list');
$testResults['contracts_list'] = $contractsListTest;
echo "  Contracts List: " . ($contractsListTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$contractsListTest['http_code']})\n";

$contractsCreateTest = testEndpoint('/contracts/create');
$testResults['contracts_create'] = $contractsCreateTest;
echo "  Contracts Create: " . ($contractsCreateTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$contractsCreateTest['http_code']})\n";

$contractsApiTest = testEndpoint('/api/contracts/list');
$testResults['contracts_api_list'] = $contractsApiTest;
echo "  Contracts API: " . ($contractsApiTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$contractsApiTest['http_code']})\n";

echo "\n";

/**
 * Test estimates and invoices endpoints
 */
echo "🧾 Estimates & Invoices Endpoints...\n";

$estimatesListTest = testEndpoint('/estimates-invoices/list');
$testResults['estimates_invoices_list'] = $estimatesListTest;
echo "  Estimates/Invoices List: " . ($estimatesListTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$estimatesListTest['http_code']})\n";

$estimatesApiTest = testEndpoint('/api/estimates-invoices/list');
$testResults['estimates_invoices_api'] = $estimatesApiTest;
echo "  Estimates/Invoices API: " . ($estimatesApiTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$estimatesApiTest['http_code']})\n";

echo "\n";

/**
 * Test API endpoints
 */
echo "🔌 Testing API Endpoints...\n";

$apiUsersTest = testEndpoint('/api/users/list');
$testResults['api_users_list'] = $apiUsersTest;
echo "  API Users List: " . ($apiUsersTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$apiUsersTest['http_code']})\n";

$apiProjectsTest = testEndpoint('/api/projects/list');
$testResults['api_projects_list'] = $apiProjectsTest;
echo "  API Projects List: " . ($apiProjectsTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$apiProjectsTest['http_code']})\n";

$apiClientsTest = testEndpoint('/api/clients/list');
$testResults['api_clients_list'] = $apiClientsTest;
echo "  API Clients List: " . ($apiClientsTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$apiClientsTest['http_code']})\n";

echo "\n";

/**
 * Test AJAX endpoints
 */
echo "⚡ Testing AJAX Endpoints...\n";

$ajaxUsersTest = testEndpoint('/ajax/users/list');
$testResults['ajax_users_list'] = $ajaxUsersTest;
echo "  AJAX Users List: " . ($ajaxUsersTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$ajaxUsersTest['http_code']})\n";

$ajaxProjectsTest = testEndpoint('/ajax/projects/list');
$testResults['ajax_projects_list'] = $ajaxProjectsTest;
echo "  AJAX Projects List: " . ($ajaxProjectsTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$ajaxProjectsTest['http_code']})\n";

$ajaxContractsTest = testEndpoint('/ajax/contracts/list');
$testResults['ajax_contracts_list'] = $ajaxContractsTest;
echo "  AJAX Contracts List: " . ($ajaxContractsTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$ajaxContractsTest['http_code']})\n";

echo "\n";

/**
 * Test permission-related endpoints
 */
echo "🔒 Testing Permission Endpoints...\n";

$permissionsTest = testEndpoint('/permissions/list');
$testResults['permissions_list'] = $permissionsTest;
echo "  Permissions List: " . ($permissionsTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$permissionsTest['http_code']})\n";

$rolesTest = testEndpoint('/roles/list');
$testResults['roles_list'] = $rolesTest;
echo "  Roles List: " . ($rolesTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$rolesTest['http_code']})\n";

echo "\n";

/**
 * Test financial management endpoints
 */
echo "💰 Testing Financial Management Endpoints...\n";

$expensesTest = testEndpoint('/expenses/list');
$testResults['expenses_list'] = $expensesTest;
echo "  Expenses List: " . ($expensesTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$expensesTest['http_code']})\n";

$paymentsTest = testEndpoint('/payments/list');
$testResults['payments_list'] = $paymentsTest;
echo "  Payments List: " . ($paymentsTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$paymentsTest['http_code']})\n";

$taxesTest = testEndpoint('/taxes/list');
$testResults['taxes_list'] = $taxesTest;
echo "  Taxes List: " . ($taxesTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$taxesTest['http_code']})\n";

echo "\n";

/**
 * Test project management endpoints
 */
echo "🏗️ Testing Project Management Endpoints...\n";

$projectsListTest = testEndpoint('/projects/list');
$testResults['projects_list'] = $projectsListTest;
echo "  Projects List: " . ($projectsListTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$projectsListTest['http_code']})\n";

$projectsCreateTest = testEndpoint('/projects/create');
$testResults['projects_create'] = $projectsCreateTest;
echo "  Projects Create: " . ($projectsCreateTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$projectsCreateTest['http_code']})\n";

$projectsApiTest = testEndpoint('/api/projects/list');
$testResults['projects_api_list'] = $projectsApiTest;
echo "  Projects API: " . ($projectsApiTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$projectsApiTest['http_code']})\n";

echo "\n";

/**
 * Test client management endpoints
 */
echo "👥 Testing Client Management Endpoints...\n";

$clientsListTest = testEndpoint('/clients/list');
$testResults['clients_list'] = $clientsListTest;
echo "  Clients List: " . ($clientsListTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$clientsListTest['http_code']})\n";

$clientsCreateTest = testEndpoint('/clients/create');
$testResults['clients_create'] = $clientsCreateTest;
echo "  Clients Create: " . ($clientsCreateTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$clientsCreateTest['http_code']})\n";

$clientsApiTest = testEndpoint('/api/clients/list');
$testResults['clients_api_list'] = $clientsApiTest;
echo "  Clients API: " . ($clientsApiTest['success'] ? "✅ PASS" : "❌ FAIL") . " (HTTP {$clientsApiTest['http_code']})\n";

echo "\n";

/**
 * Generate comprehensive report
 */
$endTime = microtime(true);
$totalTime = round($endTime - $startTime, 2);
$totalTests = count($testResults);
$passedTests = count(array_filter($testResults, function($test) { return $test['success']; }));
$failedTests = $totalTests - $passedTests;
$successRate = round(($passedTests / $totalTests) * 100, 1);

echo "📈 COMPREHENSIVE TEST RESULTS\n";
echo "==============================\n";
echo "Total Tests: {$totalTests}\n";
echo "Passed: {$passedTests} ✅\n";
echo "Failed: {$failedTests}❌\n";
echo "Success Rate: {$successRate}%\n";
echo "Total Time: {$totalTime} seconds\n\n";

echo "📋 DETAILED FAILURE REPORT\n";
echo "==========================\n";

$failedEndpoints = array_filter($testResults, function($test) { return !$test['success']; });

if (empty($failedEndpoints)) {
    echo "🎉 All endpoints passed! No failures detected.\n";
} else {
    foreach ($failedEndpoints as $endpointName => $test) {
        echo "❌ {$endpointName}\n";
        echo "   URL: {$test['url']}\n";
        echo "   Method: {$test['method']}\n";
        echo "   HTTP Code: {$test['http_code']}\n";
        if (!empty($test['error'])) {
            echo "   Error: {$test['error']}\n";
        }
        echo "   Response Preview: " . substr($test['response_body'], 0, 200) . "...\n\n";
    }
}

echo "📊 HTTP STATUS CODE DISTRIBUTION\n";
echo "================================\n";

$statusCodes = [];
foreach ($testResults as $test) {
    $code = $test['http_code'];
    $statusCodes[$code] = isset($statusCodes[$code]) ? $statusCodes[$code] + 1 : 1;
}

foreach ($statusCodes as $code => $count) {
    $statusText = '';
    switch ($code) {
        case 200: $statusText = 'OK'; break;
        case 301: $statusText = 'Moved Permanently'; break;
        case 302: $statusText = 'Found'; break;
        case 401: $statusText = 'Unauthorized'; break;
        case 403: $statusText = 'Forbidden'; break;
        case 404: $statusText = 'Not Found'; break;
        case 500: $statusText = 'Internal Server Error'; break;
        default: $statusText = 'Other';
    }
    echo "{$code} {$statusText}: {$count} endpoints\n";
}

echo "\n✅ Testing completed successfully!\n";

// Save results to file
$resultsFile = 'endpoint_test_results_' . date('Y-m-d_H-i-s') . '.json';
file_put_contents($resultsFile, json_encode([
    'timestamp' => date('Y-m-d H:i:s'),
    'total_tests' => $totalTests,
    'passed_tests' => $passedTests,
    'failed_tests' => $failedTests,
    'success_rate' => $successRate,
    'total_time' => $totalTime,
    'detailed_results' => $testResults
], JSON_PRETTY_PRINT));

echo "💾 Detailed results saved to: {$resultsFile}\n";