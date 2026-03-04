<?php

/**
 * Language System Test Script
 * 
 * This script verifies that the Language Seeder and blade template fixes are working correctly.
 * 
 * Usage: php test_language_system.php
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Language;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "===========================================\n";
echo "   LANGUAGE SYSTEM VERIFICATION TEST\n";
echo "===========================================\n\n";

$tests_passed = 0;
$tests_failed = 0;

// Test 1: Check if Language model exists
echo "[Test 1] Checking Language model... ";
if (class_exists('App\Models\Language')) {
    echo "✓ PASS\n";
    $tests_passed++;
} else {
    echo "✗ FAIL - Language model not found\n";
    $tests_failed++;
    exit(1);
}

// Test 2: Check languages table exists
echo "[Test 2] Checking languages table... ";
try {
    $exists = DB::table('languages')->exists();
    echo "✓ PASS\n";
    $tests_passed++;
} catch (\Exception $e) {
    echo "✗ FAIL - " . $e->getMessage() . "\n";
    $tests_failed++;
}

// Test 3: Check if languages exist
echo "[Test 3] Checking language records... ";
$languages = Language::all();
if ($languages->count() > 0) {
    echo "✓ PASS (Found {$languages->count()} languages)\n";
    $tests_passed++;
} else {
    echo "✗ FAIL - No languages found\n";
    $tests_failed++;
}

// Test 4: Check Arabic language exists
echo "[Test 4] Checking Arabic language... ";
$arabic = Language::where('code', 'ar')->first();
if ($arabic) {
    echo "✓ PASS ({$arabic->name})\n";
    $tests_passed++;
} else {
    echo "✗ FAIL - Arabic language not found\n";
    $tests_failed++;
}

// Test 5: Check English language exists
echo "[Test 5] Checking English language... ";
$english = Language::where('code', 'en')->first();
if ($english) {
    echo "✓ PASS ({$english->name})\n";
    $tests_passed++;
} else {
    echo "✗ FAIL - English language not found\n";
    $tests_failed++;
}

// Test 6: Check blade file exists and is readable
echo "[Test 6] Checking languages.blade.php... ";
$blade_path = resource_path('views/settings/languages.blade.php');
if (file_exists($blade_path)) {
    $content = file_get_contents($blade_path);
    
    // Check for common issues
    $issues = [];
    
    // Check for unmatched @section/@endsection
    $section_count = substr_count($content, '@section');
    $endsection_count = substr_count($content, '@endsection');
    if ($section_count !== $endsection_count) {
        $issues[] = "Mismatched @section/@endsection tags";
    }
    
    // Check for obvious HTML structure issues near the end
    $last_50_lines = implode("\n", array_slice(file($blade_path), -50));
    if (preg_match('/<!--\s*<\/div>\s*-->/s', $last_50_lines)) {
        $issues[] = "Found commented closing div tags";
    }
    
    if (empty($issues)) {
        echo "✓ PASS\n";
        $tests_passed++;
    } else {
        echo "⚠ WARNING - " . implode(", ", $issues) . "\n";
        $tests_failed++;
    }
} else {
    echo "✗ FAIL - File not found\n";
    $tests_failed++;
}

// Test 7: Check LanguageSeeder exists
echo "[Test 7] Checking LanguageSeeder... ";
if (file_exists(database_path('seeders/LanguageSeeder.php'))) {
    echo "✓ PASS\n";
    $tests_passed++;
} else {
    echo "✗ FAIL - LanguageSeeder.php not found\n";
    $tests_failed++;
}

// Test 8: Check DatabaseSeeder includes LanguageSeeder
echo "[Test 8] Checking DatabaseSeeder configuration... ";
$db_seeder = file_get_contents(database_path('seeders/DatabaseSeeder.php'));
if (strpos($db_seeder, 'LanguageSeeder::class') !== false) {
    echo "✓ PASS\n";
    $tests_passed++;
} else {
    echo "✗ FAIL - LanguageSeeder not included in DatabaseSeeder\n";
    $tests_failed++;
}

// Summary
echo "\n===========================================\n";
echo "              TEST SUMMARY\n";
echo "===========================================\n";
echo "Tests Passed: {$tests_passed}\n";
echo "Tests Failed: {$tests_failed}\n";
echo "Total Tests:  " . ($tests_passed + $tests_failed) . "\n";

if ($tests_failed === 0) {
    echo "\n🎉 ALL TESTS PASSED! 🎉\n";
    echo "\nThe language system is working correctly.\n";
    echo "You can now access: http://127.0.0.1:8000/settings/languages\n";
    exit(0);
} else {
    echo "\n⚠️  SOME TESTS FAILED ⚠️\n";
    echo "\nPlease review the failures above.\n";
    exit(1);
}
