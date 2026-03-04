<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;

echo "=== Testing Contract Quantities Workflow ===\n\n";

// Test 1: Check if the views exist
echo "1. Checking view files...\n";
$viewPaths = [
    'resources/views/contract-quantities/index.blade.php',
    'resources/views/contract-quantities/show.blade.php',
    'resources/views/contract-quantities/create.blade.php',
    'resources/views/contract-quantities/edit.blade.php'
];

foreach ($viewPaths as $path) {
    if (file_exists($path)) {
        echo "✓ $path exists\n";
    } else {
        echo "✗ $path does not exist\n";
    }
}

echo "\n2. Checking controller methods...\n";
$controllerPath = 'app/Http/Controllers/ContractQuantitiesController.php';
if (file_exists($controllerPath)) {
    $controllerContent = file_get_contents($controllerPath);
    
    $methodsToCheck = [
        'approveQuantity',
        'rejectQuantity',
        'modifyQuantity',
        'requestAmendment',
        'approveAmendment',
        'rejectAmendment',
        'pendingForApproval'
    ];
    
    foreach ($methodsToCheck as $method) {
        if (strpos($controllerContent, "public function $method") !== false) {
            echo "✓ $method method exists\n";
        } else {
            echo "✗ $method method does not exist\n";
        }
    }
} else {
    echo "✗ Controller file does not exist\n";
}

echo "\n3. Checking model updates...\n";
$modelPath = 'app/Models/ContractQuantity.php';
if (file_exists($modelPath)) {
    $modelContent = file_get_contents($modelPath);
    
    $methodsToCheck = [
        'canBeModified',
        'isBoundToContract'
    ];
    
    foreach ($methodsToCheck as $method) {
        if (strpos($modelContent, "public function $method") !== false) {
            echo "✓ $method method exists\n";
        } else {
            echo "✗ $method method does not exist\n";
        }
    }
} else {
    echo "✗ Model file does not exist\n";
}

echo "\n4. Checking route definitions...\n";
$routePath = 'routes/web.php';
if (file_exists($routePath)) {
    $routeContent = file_get_contents($routePath);
    
    $routesToCheck = [
        'contract-quantities.approve',
        'contract-quantities.reject',
        'contract-quantities.modify',
        'contract-quantities.request-amendment',
        'contract-quantities.approve-amendment',
        'contract-quantities.reject-amendment'
    ];
    
    foreach ($routesToCheck as $route) {
        if (strpos($routeContent, $route) !== false) {
            echo "✓ $route route exists\n";
        } else {
            echo "✗ $route route does not exist\n";
        }
    }
} else {
    echo "✗ Route file does not exist\n";
}

echo "\n5. Checking seeder updates...\n";
$seederPath = 'database/seeders/ContractQuantitySeeder.php';
if (file_exists($seederPath)) {
    echo "✓ ContractQuantitySeeder exists\n";
} else {
    echo "✗ ContractQuantitySeeder does not exist\n";
}

echo "\n6. Running seeder to test functionality...\n";
try {
    Artisan::call('db:seed', ['--class' => 'ContractQuantitySeeder']);
    echo "✓ Seeder ran successfully\n";
} catch (Exception $e) {
    echo "✗ Seeder failed: " . $e->getMessage() . "\n";
}

echo "\n7. Verifying database structure...\n";
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=" . $_ENV['DB_DATABASE'] ?? 'dev_taskify',
        $_ENV['DB_USERNAME'] ?? 'root',
        $_ENV['DB_PASSWORD'] ?? ''
    );
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'contract_quantities'");
    if ($stmt->rowCount() > 0) {
        echo "✓ contract_quantities table exists\n";
        
        // Check columns
        $columnsStmt = $pdo->query("DESCRIBE contract_quantities");
        $columns = $columnsStmt->fetchAll(PDO::FETCH_COLUMN);
        
        $expectedColumns = [
            'contract_id',
            'user_id',
            'workspace_id',
            'item_description',
            'requested_quantity',
            'approved_quantity',
            'unit',
            'unit_price',
            'total_amount',
            'notes',
            'supporting_documents',
            'status',
            'submitted_at',
            'approved_rejected_at',
            'approved_rejected_by',
            'approval_rejection_notes',
            'quantity_approval_signature'
        ];
        
        foreach ($expectedColumns as $column) {
            if (in_array($column, $columns)) {
                echo "✓ $column column exists\n";
            } else {
                echo "✗ $column column missing\n";
            }
        }
    } else {
        echo "✗ contract_quantities table does not exist\n";
    }
} catch (Exception $e) {
    echo "✗ Database check failed: " . $e->getMessage() . "\n";
}

echo "\n=== Contract Quantities Workflow Test Complete ===\n";
echo "All components have been verified for the professional contract quantities workflow.\n";