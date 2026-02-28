<?php

// First, let's authenticate and get a session/cookie
function authenticate() {
    $loginUrl = 'http://127.0.0.1:8001/users/authenticate';
    
    $postData = [
        'email' => 'ahmed.almasri@example.com',
        'password' => 'password123' // You might need to adjust this
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $loginUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    
    // Extract cookies from headers
    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $headers, $matches);
    $cookies = [];
    foreach($matches[1] as $item) {
        parse_str($item, $cookie);
        $cookies = array_merge($cookies, $cookie);
    }
    
    curl_close($ch);
    
    return [
        'cookies' => $cookies,
        'response' => $body,
        'headers' => $headers
    ];
}

function testEndpoint($url, $method = 'GET', $data = null, $cookies = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    // Add cookies to request
    if (!empty($cookies)) {
        $cookie_string = '';
        foreach ($cookies as $key => $value) {
            $cookie_string .= $key . '=' . $value . '; ';
        }
        curl_setopt($ch, CURLOPT_COOKIE, $cookie_string);
    }
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    
    $headers = [
        'Accept: application/json',
        'Content-Type: application/x-www-form-urlencoded'
    ];
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    return [
        'url' => $url,
        'method' => $method,
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error
    ];
}

echo "Starting authentication...\n";

// Attempt to authenticate
$auth = authenticate();
if ($auth['cookies']) {
    echo "Authentication successful. Got cookies:\n";
    print_r($auth['cookies']);
    echo "\n";
    $cookies = $auth['cookies'];
} else {
    echo "Authentication failed. Proceeding with tests without authentication.\n";
    $cookies = [];
}

// Test endpoints
$baseUrl = 'http://127.0.0.1:8001';

$endpoints = [
    // Public endpoints (with authentication)
    ['url' => $baseUrl . '/users/list', 'method' => 'GET'],
    ['url' => $baseUrl . '/users/list?page=1&limit=5', 'method' => 'GET'],
    ['url' => $baseUrl . '/users/list?search=admin', 'method' => 'GET'],
    ['url' => $baseUrl . '/users/list?sort=first_name&order=asc', 'method' => 'GET'],
    
    // Contracts endpoints
    ['url' => $baseUrl . '/contracts/list', 'method' => 'GET'],
    ['url' => $baseUrl . '/contracts/list?page=1&limit=5', 'method' => 'GET'],
    
    // Estimates/Invoices endpoints
    ['url' => $baseUrl . '/estimates-invoices/list', 'method' => 'GET'],
    ['url' => $baseUrl . '/estimates-invoices/list?page=1&limit=5', 'method' => 'GET'],
    
    // Contract Quantities
    ['url' => $baseUrl . '/contract-quantities/list', 'method' => 'GET'],
    
    // Contract Approvals
    ['url' => $baseUrl . '/contract-approvals/list', 'method' => 'GET'],
    
    // Journal Entries
    ['url' => $baseUrl . '/journal-entries/list', 'method' => 'GET'],
    
    // Projects and Tasks
    ['url' => $baseUrl . '/projects/list', 'method' => 'GET'],
    ['url' => $baseUrl . '/tasks/list', 'method' => 'GET'],
    
    // Clients
    ['url' => $baseUrl . '/clients/list', 'method' => 'GET'],
    
    // Workspaces
    ['url' => $baseUrl . '/workspaces/list', 'method' => 'GET'],
];

echo "Testing Endpoints...\n";
echo str_repeat("=", 50) . "\n\n";

foreach ($endpoints as $endpoint) {
    echo "Testing: " . $endpoint['method'] . " " . $endpoint['url'] . "\n";
    
    $result = testEndpoint($endpoint['url'], $endpoint['method'], null, $cookies);
    
    echo "HTTP Status: " . $result['http_code'] . "\n";
    
    if ($result['error']) {
        echo "Error: " . $result['error'] . "\n";
    } else {
        // Try to parse JSON response
        $json = json_decode($result['response'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "Response Type: JSON\n";
            if (isset($json['total'])) {
                echo "Total Records: " . $json['total'] . "\n";
            }
            if (isset($json['rows'])) {
                echo "Rows Returned: " . count($json['rows']) . "\n";
            }
            if (isset($json['error']) && $json['error']) {
                echo "API Error: " . (isset($json['message']) ? $json['message'] : 'Unknown error') . "\n";
            }
            // Show the full JSON response for debugging
            echo "Full Response: " . json_encode($json, JSON_PRETTY_PRINT) . "\n";
        } else {
            echo "Response Type: HTML/Text (length: " . strlen($result['response']) . ")\n";
            // Show first 200 characters of response for debugging
            if (strlen($result['response']) > 0) {
                echo "Preview: " . substr($result['response'], 0, 200) . "...\n";
            }
        }
    }
    
    echo str_repeat("-", 30) . "\n\n";
}

echo "Testing Complete!\n";
?>