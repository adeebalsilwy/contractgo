<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PDF Generation Test ===\n\n";

try {
    // Test PDF service
    $pdfService = app('App\Services\PdfService');
    
    echo "✓ PDF Service instantiated successfully\n";
    
    // Test contract PDF generation with a sample contract
    $contract = \App\Models\Contract::first();
    
    if ($contract) {
        echo "✓ Found sample contract: {$contract->id} - {$contract->title}\n";
        
        // Test PDF generation
        $testPdf = $pdfService->generatePdf('pdf.contracts.template', [
            'contract' => $contract,
            'items' => [],
            'companyInfo' => [
                'name' => 'Test Company',
                'name_ar' => 'شركة اختبار',
                'name_en' => 'Test Company',
                'address' => 'Test Address',
                'phone' => '123456789',
                'email' => 'test@company.com',
                'website' => 'www.test.com',
                'vat_number' => 'VAT123456'
            ],
            'general_settings' => [],
            'isRtl' => false
        ]);
        
        if ($testPdf && strlen($testPdf) > 0) {
            echo "✓ PDF generated successfully (" . strlen($testPdf) . " bytes)\n";
            
            // Test saving to file
            $testFile = __DIR__ . '/test_contract.pdf';
            file_put_contents($testFile, $testPdf);
            echo "✓ PDF saved to: {$testFile}\n";
            
            // Test mobile detection
            $isMobile = $pdfService->isMobileDevice();
            echo "✓ Mobile detection: " . ($isMobile ? 'Mobile device detected' : 'Desktop device detected') . "\n";
            
        } else {
            echo "✗ Failed to generate PDF\n";
        }
    } else {
        echo "⚠ No contracts found in database\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";