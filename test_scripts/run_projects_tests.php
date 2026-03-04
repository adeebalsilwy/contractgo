#!/usr/bin/env php
<?php

/**
 * Projects System Test Runner
 * 
 * This script runs comprehensive tests for all project functionality
 * including controllers, views, APIs, and components.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class ProjectsTestRunner
{
    private $testResults = [];
    private $passedTests = 0;
    private $failedTests = 0;
    private $startTime;

    public function __construct()
    {
        $this->startTime = microtime(true);
        echo "🚀 Starting Comprehensive Projects System Testing\n";
        echo "==============================================\n\n";
    }

    /**
     * Run all project tests
     */
    public function runAllTests()
    {
        try {
            // Test 1: Projects Controller Tests
            $this->runTestSuite('ProjectsControllerTest', [
                'test_project_index_page',
                'test_project_list_view',
                'test_favorite_projects_view',
                'test_create_project',
                'test_create_project_validation',
                'test_create_project_invalid_dates',
                'test_create_project_invalid_budget',
                'test_create_project_with_users_and_clients',
                'test_create_project_with_tags',
                'test_show_project',
                'test_update_project',
                'test_update_project_validation',
                'test_delete_project',
                'test_delete_multiple_projects',
                'test_update_project_favorite_status',
                'test_update_project_pinned_status',
                'test_update_project_status',
                'test_update_project_priority',
                'test_duplicate_project',
                'test_api_list_projects',
                'test_api_get_single_project',
                'test_api_search_projects',
                'test_api_filter_projects_by_status',
                'test_kanban_view',
                'test_gantt_chart_view',
                'test_calendar_view',
                'test_get_calendar_data',
                'test_get_statuses',
                'test_get_priorities',
                'test_mind_map_view',
                'test_upload_media',
                'test_generate_project_pdf',
                'test_unauthorized_access',
                'test_show_bulk_upload_form',
                'test_save_view_preference'
            ]);

            // Test 2: Projects View Tests
            $this->runTestSuite('ProjectsViewTest', [
                'test_projects_view_with_data',
                'test_projects_view_with_favorites',
                'test_projects_view_empty',
                'test_projects_view_breadcrumbs',
                'test_projects_view_favorite_breadcrumbs',
                'test_projects_view_action_buttons',
                'test_projects_view_default_view_badge',
                'test_projects_view_non_default_badge',
                'test_projects_view_with_filters',
                'test_projects_view_with_favorite_filters',
                'test_projects_view_component_rendering',
                'test_projects_view_javascript_variables',
                'test_projects_view_table_structure',
                'test_projects_view_with_custom_fields',
                'test_projects_view_with_different_roles',
                'test_projects_view_performance'
            ]);

            // Test 3: Projects Card Component Tests
            $this->runTestSuite('ProjectsCardComponentTest', [
                'test_projects_card_component_rendering',
                'test_projects_card_empty_state',
                'test_projects_card_with_data',
                'test_projects_card_filter_inputs',
                'test_projects_card_filter_inputs_with_data',
                'test_projects_card_hidden_inputs',
                'test_projects_card_table_structure',
                'test_projects_card_table_columns',
                'test_projects_card_column_visibility',
                'test_projects_card_sorting_attributes',
                'test_projects_card_with_user_contexts',
                'test_projects_card_with_custom_fields',
                'test_projects_card_data_reload',
                'test_projects_card_column_saving',
                'test_projects_card_javascript_integration',
                'test_projects_card_mobile_responsive',
                'test_projects_card_error_handling'
            ]);

            // Test 4: Integration Tests
            $this->runTestSuite('ProjectsSystemIntegrationTest', [
                'test_complete_project_crud_operations',
                'test_project_api_endpoints',
                'test_project_views_and_components',
                'test_project_relationships',
                'test_project_status_priority_management',
                'test_project_search_and_filtering',
                'test_project_file_upload',
                'test_project_permissions',
                'test_project_bulk_operations',
                'test_project_data_validation',
                'test_project_ajax_features',
                'test_project_performance',
                'test_project_edge_cases',
                'test_project_integration'
            ]);

            $this->printSummary();
            
        } catch (Exception $e) {
            echo "❌ Fatal Error: " . $e->getMessage() . "\n";
            echo "Stack Trace: " . $e->getTraceAsString() . "\n";
            $this->failedTests++;
        }
    }

    /**
     * Run a specific test suite
     */
    private function runTestSuite($testClass, $methods)
    {
        echo "🧪 Running {$testClass}...\n";
        echo str_repeat("-", 50) . "\n";
        
        $testInstance = $this->createTestInstance($testClass);
        $suitePassed = 0;
        $suiteFailed = 0;
        
        foreach ($methods as $method) {
            try {
                // Set up test
                if (method_exists($testInstance, 'setUp')) {
                    $testInstance->setUp();
                }
                
                // Run test method
                $testInstance->$method();
                
                // Tear down test
                if (method_exists($testInstance, 'tearDown')) {
                    $testInstance->tearDown();
                }
                
                echo "✅ {$method}\n";
                $suitePassed++;
                $this->passedTests++;
                
            } catch (Exception $e) {
                echo "❌ {$method}: " . $e->getMessage() . "\n";
                $suiteFailed++;
                $this->failedTests++;
                
                // Log detailed error information
                echo "   Error Details: " . $e->getFile() . ":" . $e->getLine() . "\n";
                if (method_exists($e, 'getResponse')) {
                    $response = $e->getResponse();
                    if ($response) {
                        echo "   Response Status: " . $response->getStatusCode() . "\n";
                        echo "   Response Content: " . substr($response->getContent(), 0, 200) . "...\n";
                    }
                }
            } catch (Error $e) {
                echo "💥 {$method}: Fatal Error - " . $e->getMessage() . "\n";
                $suiteFailed++;
                $this->failedTests++;
            }
        }
        
        echo "\n📊 {$testClass} Results: {$suitePassed} passed, {$suiteFailed} failed\n\n";
    }

    /**
     * Create test instance
     */
    private function createTestInstance($className)
    {
        $fullClassName = "Tests\\Feature\\{$className}";
        if (!class_exists($fullClassName)) {
            throw new Exception("Test class {$fullClassName} not found");
        }
        
        $testInstance = new $fullClassName();
        
        // Initialize Laravel testing environment
        if (method_exists($testInstance, 'createApplication')) {
            $app = $testInstance->createApplication();
            $testInstance->setApplication($app);
        }
        
        return $testInstance;
    }

    /**
     * Print comprehensive test summary
     */
    private function printSummary()
    {
        $endTime = microtime(true);
        $totalTime = $endTime - $this->startTime;
        
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "📋 COMPREHENSIVE PROJECTS SYSTEM TEST SUMMARY\n";
        echo str_repeat("=", 60) . "\n";
        
        echo "✅ Passed Tests: {$this->passedTests}\n";
        echo "❌ Failed Tests: {$this->failedTests}\n";
        echo "⏱️  Total Execution Time: " . number_format($totalTime, 2) . " seconds\n";
        
        $totalTests = $this->passedTests + $this->failedTests;
        $successRate = $totalTests > 0 ? ($this->passedTests / $totalTests) * 100 : 0;
        
        echo "📈 Success Rate: " . number_format($successRate, 1) . "%\n\n";
        
        if ($this->failedTests === 0) {
            echo "🎉 ALL TESTS PASSED! The Projects system is working correctly.\n";
            echo "✨ Ready for production deployment.\n";
        } else {
            echo "⚠️  Some tests failed. Please review the errors above.\n";
            echo "🔧 Recommended actions:\n";
            echo "   1. Fix the failing tests\n";
            echo "   2. Run tests again\n";
            echo "   3. Verify all functionality works as expected\n";
        }
        
        echo "\n" . str_repeat("=", 60) . "\n";
        
        // Generate detailed report
        $this->generateDetailedReport();
    }

    /**
     * Generate detailed test report
     */
    private function generateDetailedReport()
    {
        $report = [
            'timestamp' => date('Y-m-d H:i:s'),
            'total_tests' => $this->passedTests + $this->failedTests,
            'passed_tests' => $this->passedTests,
            'failed_tests' => $this->failedTests,
            'success_rate' => $this->passedTests > 0 ? ($this->passedTests / ($this->passedTests + $this->failedTests)) * 100 : 0,
            'execution_time' => microtime(true) - $this->startTime,
            'environment' => [
                'php_version' => phpversion(),
                'laravel_version' => app()->version(),
                'operating_system' => PHP_OS
            ]
        ];
        
        $reportFile = __DIR__ . '/../storage/logs/projects_test_report_' . date('Y-m-d_H-i-s') . '.json';
        file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT));
        
        echo "📄 Detailed report saved to: {$reportFile}\n";
    }
}

// Run the tests
if (php_sapi_name() === 'cli') {
    $runner = new ProjectsTestRunner();
    $runner->runAllTests();
} else {
    echo "This script must be run from command line.\n";
    exit(1);
}