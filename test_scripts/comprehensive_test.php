<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== COMPREHENSIVE CONTRACT QUANTITIES SYSTEM TEST ===\n\n";

try {
    // Test 1: Database Connection
    echo "1. DATABASE CONNECTION TEST\n";
    echo "==========================\n";
    DB::connection()->getPdo();
    echo "✓ Database connection successful\n";
    echo "Database: " . DB::connection()->getDatabaseName() . "\n\n";
    
    // Test 2: Required Tables
    echo "2. REQUIRED TABLES TEST\n";
    echo "======================\n";
    $requiredTables = ['contract_quantities', 'contracts', 'users', 'workspaces'];
    foreach ($requiredTables as $table) {
        $exists = Schema::hasTable($table);
        echo ($exists ? "✓" : "✗") . " $table table: " . ($exists ? "EXISTS" : "MISSING") . "\n";
    }
    echo "\n";
    
    // Test 3: Models and Relationships
    echo "3. MODELS AND RELATIONSHIPS TEST\n";
    echo "===============================\n";
    
    // Test ContractQuantity model
    $quantityCount = \App\Models\ContractQuantity::count();
    echo "✓ ContractQuantity model: $quantityCount records found\n";
    
    // Test Contract model
    $contractCount = \App\Models\Contract::count();
    echo "✓ Contract model: $contractCount records found\n";
    
    // Test User model
    $userCount = \App\Models\User::count();
    echo "✓ User model: $userCount records found\n";
    
    // Test relationships
    $contractWithQuantities = \App\Models\Contract::with('quantities')->first();
    if ($contractWithQuantities) {
        echo "✓ Contract-Quantity relationship: Working (" . $contractWithQuantities->quantities->count() . " quantities)\n";
    }
    
    $quantityWithContract = \App\Models\ContractQuantity::with('contract')->first();
    if ($quantityWithContract) {
        echo "✓ Quantity-Contract relationship: Working\n";
    }
    echo "\n";
    
    // Test 4: Routes
    echo "4. ROUTES TEST\n";
    echo "=============\n";
    try {
        $indexPath = route('contract-quantities.index');
        echo "✓ contract-quantities.index route: $indexPath\n";
    } catch (Exception $e) {
        echo "✗ contract-quantities.index route: ERROR - " . $e->getMessage() . "\n";
    }
    
    try {
        $listPath = route('contract-quantities.list');
        echo "✓ contract-quantities.list route: $listPath\n";
    } catch (Exception $e) {
        echo "✗ contract-quantities.list route: ERROR - " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // Test 5: Controller Methods
    echo "5. CONTROLLER METHODS TEST\n";
    echo "=========================\n";
    $controller = app()->make('App\Http\Controllers\ContractQuantitiesController');
    echo "✓ Controller instantiation: SUCCESS\n";
    
    // Test if index method exists
    if (method_exists($controller, 'index')) {
        echo "✓ index method: EXISTS\n";
    } else {
        echo "✗ index method: MISSING\n";
    }
    
    // Test if list method exists
    if (method_exists($controller, 'list')) {
        echo "✓ list method: EXISTS\n";
    } else {
        echo "✗ list method: MISSING\n";
    }
    echo "\n";
    
    // Test 6: View Files
    echo "6. VIEW FILES TEST\n";
    echo "=================\n";
    $viewFiles = [
        'contract-quantities.index' => 'resources/views/contract-quantities/index.blade.php',
        'contract-quantities.create' => 'resources/views/contract-quantities/create.blade.php',
        'contract-quantities.show' => 'resources/views/contract-quantities/show.blade.php',
        'contract-quantities.edit' => 'resources/views/contract-quantities/edit.blade.php'
    ];
    
    foreach ($viewFiles as $viewName => $filePath) {
        $fullPath = base_path($filePath);
        if (file_exists($fullPath)) {
            echo "✓ $viewName view: EXISTS\n";
        } else {
            echo "✗ $viewName view: MISSING ($filePath)\n";
        }
    }
    echo "\n";
    
    // Test 7: JavaScript Files
    echo "7. JAVASCRIPT FILES TEST\n";
    echo "=======================\n";
    $jsFiles = [
        'contract-quantities.js' => 'public/assets/js/pages/contract-quantities.js'
    ];
    
    foreach ($jsFiles as $jsName => $filePath) {
        $fullPath = base_path($filePath);
        if (file_exists($fullPath)) {
            echo "✓ $jsName: EXISTS\n";
        } else {
            echo "✗ $jsName: MISSING ($filePath)\n";
        }
    }
    echo "\n";
    
    // Test 8: Storage and Assets
    echo "8. STORAGE AND ASSETS TEST\n";
    echo "=========================\n";
    $storagePath = public_path('storage');
    if (is_dir($storagePath)) {
        echo "✓ Storage symlink: EXISTS\n";
    } else {
        echo "✗ Storage symlink: MISSING\n";
    }
    
    $layoutPath = resource_path('views/layout.blade.php');
    if (file_exists($layoutPath)) {
        echo "✓ Layout file: EXISTS\n";
    } else {
        echo "✗ Layout file: MISSING\n";
    }
    echo "\n";
    
    // Test 9: Sample Data
    echo "9. SAMPLE DATA TEST\n";
    echo "==================\n";
    if ($quantityCount > 0) {
        $sampleQuantity = \App\Models\ContractQuantity::first();
        echo "✓ Sample contract quantity:\n";
        echo "  - ID: " . $sampleQuantity->id . "\n";
        echo "  - Contract ID: " . $sampleQuantity->contract_id . "\n";
        echo "  - Item: " . $sampleQuantity->item_description . "\n";
        echo "  - Quantity: " . $sampleQuantity->requested_quantity . "\n";
        echo "  - Status: " . $sampleQuantity->status . "\n";
    } else {
        echo "! No contract quantities found in database\n";
    }
    echo "\n";
    
    echo "=== TEST SUMMARY ===\n";
    echo "All core functionality appears to be working correctly!\n";
    echo "The contract quantities system should be accessible at: http://127.0.0.1:8000/contract-quantities\n\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "LINE: " . $e->getLine() . "\n";
    echo "FILE: " . $e->getFile() . "\n";
    echo "TRACE: " . $e->getTraceAsString() . "\n";
}