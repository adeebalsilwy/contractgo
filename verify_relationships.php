<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Configure database connection
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'dev_taskify',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Test relationships
echo "=== Contract-Extract Relationships Verification ===\n\n";

// Get a contract with its extracts
$contract = Capsule::table('contracts')->first();
if ($contract) {
    echo "Sample Contract:\n";
    echo "ID: {$contract->id}\n";
    echo "Title: {$contract->title}\n";
    echo "Value: {$contract->value}\n";
    echo "Start Date: {$contract->start_date}\n";
    echo "End Date: {$contract->end_date}\n\n";
    
    // Get extracts linked to this contract
    $extracts = Capsule::table('estimates_invoices')
        ->where('contract_id', $contract->id)
        ->get();
    
    echo "Extracts linked to this contract: " . count($extracts) . "\n";
    foreach ($extracts as $extract) {
        echo "- {$extract->name} ({$extract->type}) - Status: {$extract->status}\n";
    }
}

echo "\n=== Testing Arabic Content ===\n";
// Show some Arabic content examples
$arabicExtracts = Capsule::table('estimates_invoices')
    ->where('name', 'like', '%مستخلص%')
    ->orWhere('name', 'like', '%تقدير%')
    ->limit(5)
    ->get();

echo "Arabic extracts found: " . count($arabicExtracts) . "\n";
foreach ($arabicExtracts as $extract) {
    echo "- {$extract->name}\n";
}

echo "\n=== Verification Complete ===\n";