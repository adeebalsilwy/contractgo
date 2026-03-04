<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Contract Quantities functionality...\n\n";

try {
    // Test database connection
    echo "1. Testing database connection...\n";
    DB::connection()->getPdo();
    echo "   ✓ Database connection successful\n\n";
    
    // Test if contract_quantities table exists
    echo "2. Checking if contract_quantities table exists...\n";
    $tableExists = Schema::hasTable('contract_quantities');
    if ($tableExists) {
        echo "   ✓ contract_quantities table exists\n";
        
        // Check table structure
        $columns = Schema::getColumnListing('contract_quantities');
        echo "   Table columns: " . implode(', ', $columns) . "\n\n";
    } else {
        echo "   ✗ contract_quantities table does not exist\n";
        echo "   Running migrations...\n";
        Artisan::call('migrate', ['--path' => 'database/migrations/2026_02_22_200147_create_contract_quantities_table.php']);
        echo "   Migration output: " . Artisan::output() . "\n\n";
    }
    
    // Test ContractQuantity model
    echo "3. Testing ContractQuantity model...\n";
    $count = \App\Models\ContractQuantity::count();
    echo "   ✓ ContractQuantity model working, found $count records\n\n";
    
    // Test Contract model relationship
    echo "4. Testing Contract model relationships...\n";
    $contractWithQuantities = \App\Models\Contract::with('quantities')->first();
    if ($contractWithQuantities) {
        echo "   ✓ Contract model working, found contract with " . $contractWithQuantities->quantities->count() . " quantities\n";
    } else {
        echo "   ! No contracts found with quantities\n";
    }
    echo "\n";
    
    // Test route existence
    echo "5. Testing route generation...\n";
    $route = route('contract-quantities.index');
    echo "   ✓ Route generated: $route\n\n";
    
    echo "All tests completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}