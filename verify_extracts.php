<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Configure database connection
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'dev_taskify',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Verify the data
echo "=== Arabic Extracts Data Verification ===\n\n";

// Count total extracts
$totalExtracts = Capsule::table('estimates_invoices')->count();
echo "Total Estimates/Invoices: $totalExtracts\n";

// Count extracts linked to contracts
$linkedExtracts = Capsule::table('estimates_invoices')->whereNotNull('contract_id')->count();
echo "Linked to contracts: $linkedExtracts\n";

// Count standalone extracts
$standaloneExtracts = Capsule::table('estimates_invoices')->whereNull('contract_id')->count();
echo "Standalone extracts: $standaloneExtracts\n";

// Show sample data
echo "\n=== Sample Extract Data ===\n";
$sample = Capsule::table('estimates_invoices')->first();
if ($sample) {
    echo "ID: {$sample->id}\n";
    echo "Name: {$sample->name}\n";
    echo "Type: {$sample->type}\n";
    echo "Status: {$sample->status}\n";
    echo "Contract ID: {$sample->contract_id}\n";
    echo "Client ID: {$sample->client_id}\n";
    echo "Total: {$sample->total}\n";
    echo "Created: {$sample->created_at}\n";
}

// Show sample items attached to extracts
echo "\n=== Sample Extract Items ===\n";
$sampleItems = Capsule::table('estimates_invoice_item')
    ->join('items', 'estimates_invoice_item.item_id', '=', 'items.id')
    ->select('items.title', 'estimates_invoice_item.qty', 'estimates_invoice_item.rate', 'estimates_invoice_item.amount')
    ->limit(5)
    ->get();

foreach ($sampleItems as $item) {
    echo "Item: {$item->title} | Qty: {$item->qty} | Rate: {$item->rate} | Amount: {$item->amount}\n";
}

echo "\n=== Verification Complete ===\n";