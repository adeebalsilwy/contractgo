<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Test PDF Generation
Route::get('/test-pdf', function () {
    try {
        // Test basic PDF generation
        $pdfService = app('App\Services\PdfService');
        
        // Test data
        $testData = [
            'title' => 'Test Document',
            'content' => 'This is a test PDF document with Arabic content: مرحبا بالعربية',
            'items' => [
                ['name' => 'Item 1', 'value' => 100],
                ['name' => 'Item 2', 'value' => 200],
            ]
        ];
        
        // Generate test PDF
        $pdfContent = $pdfService->generatePdf('pdf.reports.custom', $testData, 'test-document');
        
        // Return PDF response
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="test-document.pdf"',
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'error' => true,
            'message' => 'PDF generation failed: ' . $e->getMessage()
        ], 500);
    }
});

// Test Arabic Font Loading
Route::get('/test-arabic-fonts', function () {
    try {
        $fontPath = public_path('vendor/gpdf/fonts');
        $fonts = [];
        
        if (is_dir($fontPath)) {
            $files = scandir($fontPath);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'ttf') {
                    $fonts[] = $file;
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'fonts_directory' => $fontPath,
            'arabic_fonts' => $fonts,
            'font_count' => count($fonts)
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'error' => true,
            'message' => 'Font test failed: ' . $e->getMessage()
        ], 500);
    }
});

// Test Gpdf Configuration
Route::get('/test-gpdf-config', function () {
    try {
        $config = config('gpdf');
        
        return response()->json([
            'success' => true,
            'default_font' => $config['default_font'] ?? 'Not set',
            'font_dir' => $config['font_dir'] ?? 'Not set',
            'temp_dir' => $config['temp_dir'] ?? 'Not set',
            'show_numbers_as_hindi' => $config['show_numbers_as_hindi'] ?? false,
            'complete_config' => $config
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'error' => true,
            'message' => 'Config test failed: ' . $e->getMessage()
        ], 500);
    }
});

// Test PDF Service Availability
Route::get('/test-pdf-service', function () {
    try {
        $pdfService = app('App\Services\PdfService');
        
        return response()->json([
            'success' => true,
            'service_available' => true,
            'service_class' => get_class($pdfService),
            'methods' => get_class_methods($pdfService)
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'error' => true,
            'message' => 'Service test failed: ' . $e->getMessage()
        ], 500);
    }
});

echo "PDF Test Routes Available:\n";
echo "1. /test-pdf - Generate test PDF\n";
echo "2. /test-arabic-fonts - Check Arabic font availability\n";
echo "3. /test-gpdf-config - Check Gpdf configuration\n";
echo "4. /test-pdf-service - Check PDF service availability\n";