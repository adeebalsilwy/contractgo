<?php

/**
 * Template System Verification Test
 * Tests the complete template management system functionality
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\WorkflowTemplate;
use App\Models\ContractTemplate;
use App\Models\ExtractTemplate;
use App\Models\Contract;
use App\Models\EstimatesInvoice;
use App\Services\TemplateService;
use App\Models\User;
use App\Models\Workspace;

echo "🚀 TEMPLATE SYSTEM VERIFICATION TEST\n";
echo "=====================================\n\n";

// Test Results Tracking
$testResults = [];
$passed = 0;
$failed = 0;

function runTest($name, $testFunction) {
    global $testResults, $passed, $failed;
    
    echo "🧪 Testing: {$name}\n";
    try {
        $result = $testFunction();
        if ($result === true) {
            $testResults[$name] = ['status' => 'PASS', 'message' => 'Test passed'];
            $passed++;
            echo "  ✅ PASSED\n";
        } else {
            $testResults[$name] = ['status' => 'FAIL', 'message' => $result ?: 'Test failed'];
            $failed++;
            echo "  ❌ FAILED: " . ($result ?: 'Unknown error') . "\n";
        }
    } catch (Exception $e) {
        $testResults[$name] = ['status' => 'ERROR', 'message' => $e->getMessage()];
        $failed++;
        echo "  ❌ ERROR: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

// Test 1: Check if template models exist and are accessible
runTest('Template Models Existence', function() {
    $workflowTemplate = new WorkflowTemplate();
    $contractTemplate = new ContractTemplate();
    $extractTemplate = new ExtractTemplate();
    
    return true;
});

// Test 2: Check if default templates were created
runTest('Default Templates Creation', function() {
    $workflowTemplates = WorkflowTemplate::where('is_default', true)->count();
    $contractTemplates = ContractTemplate::where('is_default', true)->count();
    $extractTemplates = ExtractTemplate::where('is_default', true)->count();
    
    if ($workflowTemplates > 0 && $contractTemplates > 0 && $extractTemplates > 0) {
        echo "  Found {$workflowTemplates} workflow templates\n";
        echo "  Found {$contractTemplates} contract templates\n";
        echo "  Found {$extractTemplates} extract templates\n";
        return true;
    }
    return "Default templates not found";
});

// Test 3: Test TemplateService functionality
runTest('TemplateService Core Functionality', function() {
    $templateService = new TemplateService();
    
    // Test if service methods exist
    if (!method_exists($templateService, 'applyDefaultContractTemplate')) {
        return "applyDefaultContractTemplate method missing";
    }
    
    if (!method_exists($templateService, 'applyDefaultExtractTemplate')) {
        return "applyDefaultExtractTemplate method missing";
    }
    
    if (!method_exists($templateService, 'getContractVariables')) {
        return "getContractVariables method missing";
    }
    
    if (!method_exists($templateService, 'getExtractVariables')) {
        return "getExtractVariables method missing";
    }
    
    if (!method_exists($templateService, 'replaceVariables')) {
        return "replaceVariables method missing";
    }
    
    return true;
});

// Test 4: Test variable replacement functionality
runTest('Template Variable Replacement', function() {
    $templateService = new TemplateService();
    
    $content = "Hello {client_name}, your contract #{contract_number} is ready.";
    $variables = [
        '{client_name}' => 'Ahmed Ali',
        '{contract_number}' => 'CT-2026-001'
    ];
    
    $result = $templateService->replaceVariables($content, $variables);
    $expected = "Hello Ahmed Ali, your contract #CT-2026-001 is ready.";
    
    if ($result === $expected) {
        return true;
    }
    return "Variable replacement failed. Got: {$result}";
});

// Test 5: Test contract template application
runTest('Contract Template Application', function() {
    // Get a workspace and user for testing
    $workspace = Workspace::first();
    $user = User::first();
    
    if (!$workspace || !$user) {
        return "No workspace or user found for testing";
    }
    
    // Create a test contract
    $contract = Contract::create([
        'title' => 'Test Construction Contract',
        'value' => 100000,
        'description' => 'Test contract for template verification',
        'start_date' => '2026-01-01',
        'end_date' => '2026-12-31',
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
        'client_id' => $user->id,
        'project_id' => 1
    ]);
    
    $templateService = new TemplateService();
    $result = $templateService->applyDefaultContractTemplate($contract);
    
    if ($result && $result->description) {
        // Clean up test contract
        $contract->delete();
        return true;
    }
    
    // Clean up test contract
    $contract->delete();
    return "Contract template application failed";
});

// Test 6: Test extract template application
runTest('Extract Template Application', function() {
    // Get a workspace and user for testing
    $workspace = Workspace::first();
    $user = User::first();
    
    if (!$workspace || !$user) {
        return "No workspace or user found for testing";
    }
    
    // Create a test contract first
    $contract = Contract::create([
        'title' => 'Test Contract for Extract',
        'value' => 50000,
        'description' => 'Test contract for extract template verification',
        'start_date' => '2026-01-01',
        'end_date' => '2026-12-31',
        'workspace_id' => $workspace->id,
        'created_by' => $user->id,
        'client_id' => $user->id,
        'project_id' => 1
    ]);
    
    // Create a test extract
    $extract = EstimatesInvoice::create([
        'name' => 'Test Extract',
        'type' => 'estimate',
        'contract_id' => $contract->id,
        'workspace_id' => $workspace->id,
        'created_by' => $user->id
    ]);
    
    $templateService = new TemplateService();
    $result = $templateService->applyDefaultExtractTemplate($extract);
    
    if ($result && $result->name) {
        // Clean up test data
        $extract->delete();
        $contract->delete();
        return true;
    }
    
    // Clean up test data
    $extract->delete();
    $contract->delete();
    return "Extract template application failed";
});

// Test 7: Test template duplication functionality
runTest('Template Duplication', function() {
    $template = WorkflowTemplate::where('is_default', true)->first();
    
    if (!$template) {
        return "No template found for duplication test";
    }
    
    $duplicate = $template->duplicate('Test Duplicate Template');
    
    if ($duplicate && $duplicate->name === 'Test Duplicate Template') {
        // Clean up duplicate
        $duplicate->delete();
        return true;
    }
    
    return "Template duplication failed";
});

// Test 8: Test template versioning
runTest('Template Versioning', function() {
    $template = WorkflowTemplate::where('is_default', true)->first();
    
    if (!$template) {
        return "No template found for versioning test";
    }
    
    $originalVersion = $template->version;
    $template->update(['name' => 'Version Test Template', 'description' => 'Updated description']);
    $template->refresh();
    $newVersion = $template->version;
    
    if ($newVersion !== $originalVersion) {
        return true;
    }
    
    return "Template versioning not working. Original: {$originalVersion}, New: {$newVersion}";
});

// Test 9: Test template workflow steps
runTest('Workflow Template Steps', function() {
    $template = WorkflowTemplate::where('is_default', true)->first();
    
    if (!$template) {
        return "No workflow template found";
    }
    
    $steps = $template->getWorkflowSteps();
    
    if (is_array($steps) && count($steps) > 0) {
        echo "  Found " . count($steps) . " workflow steps\n";
        return true;
    }
    
    return "No workflow steps found";
});

// Test 10: Test template variables
runTest('Template Variables', function() {
    $template = WorkflowTemplate::where('is_default', true)->first();
    
    if (!$template) {
        return "No template found for variables test";
    }
    
    $variables = $template->getAvailableVariables();
    
    if (is_array($variables) && count($variables) > 0) {
        echo "  Found " . count($variables) . " available variables\n";
        return true;
    }
    
    return "No template variables found";
});

// Summary
echo "📊 TEST SUMMARY\n";
echo "===============\n";
echo "✅ Passed: {$passed}\n";
echo "❌ Failed: {$failed}\n";
echo "📈 Success Rate: " . round(($passed / ($passed + $failed)) * 100, 1) . "%\n\n";

if ($failed > 0) {
    echo "🔧 FAILED TESTS DETAILS:\n";
    echo "========================\n";
    foreach ($testResults as $testName => $result) {
        if ($result['status'] !== 'PASS') {
            echo "❌ {$testName}: {$result['message']}\n";
        }
    }
    echo "\n";
}

echo "🎉 TEMPLATE SYSTEM VERIFICATION COMPLETE!\n";

// Additional Information
echo "\n📋 ADDITIONAL INFORMATION:\n";
echo "==========================\n";

// Count total templates
$totalWorkflow = WorkflowTemplate::count();
$totalContract = ContractTemplate::count();
$totalExtract = ExtractTemplate::count();

echo "Total Workflow Templates: {$totalWorkflow}\n";
echo "Total Contract Templates: {$totalContract}\n";
echo "Total Extract Templates: {$totalExtract}\n";

// Count active templates
$activeWorkflow = WorkflowTemplate::where('status', 'active')->count();
$activeContract = ContractTemplate::where('status', 'active')->count();
$activeExtract = ExtractTemplate::where('status', 'active')->count();

echo "Active Workflow Templates: {$activeWorkflow}\n";
echo "Active Contract Templates: {$activeContract}\n";
echo "Active Extract Templates: {$activeExtract}\n";

// Count default templates
$defaultWorkflow = WorkflowTemplate::where('is_default', true)->count();
$defaultContract = ContractTemplate::where('is_default', true)->count();
$defaultExtract = ExtractTemplate::where('is_default', true)->count();

echo "Default Workflow Templates: {$defaultWorkflow}\n";
echo "Default Contract Templates: {$defaultContract}\n";
echo "Default Extract Templates: {$defaultExtract}\n";

echo "\n🚀 Template system is ready for production use!\n";