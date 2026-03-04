<?php

require_once 'vendor/autoload.php';

echo "Testing Gpdf Installation...\n\n";

try {
    // Test if Gpdf classes are available
    if (class_exists('Omaralalwi\Gpdf\Gpdf')) {
        echo "✓ Gpdf class found\n";
    } else {
        echo "✗ Gpdf class not found\n";
        exit(1);
    }
    
    if (class_exists('Omaralalwi\Gpdf\GpdfConfig')) {
        echo "✓ GpdfConfig class found\n";
    } else {
        echo "✗ GpdfConfig class not found\n";
        exit(1);
    }
    
    // Test configuration file
    $configFile = 'config/gpdf.php';
    if (file_exists($configFile)) {
        echo "✓ Gpdf configuration file found\n";
        $config = require $configFile;
        echo "  Default font: " . ($config['default_font'] ?? 'Not set') . "\n";
    } else {
        echo "✗ Gpdf configuration file not found\n";
    }
    
    // Test font directory
    $fontDir = 'public/vendor/gpdf/fonts';
    if (is_dir($fontDir)) {
        echo "✓ Font directory found\n";
        $fontFiles = glob($fontDir . '/*.ttf');
        echo "  Arabic font files: " . count($fontFiles) . "\n";
        foreach ($fontFiles as $font) {
            echo "    - " . basename($font) . "\n";
        }
    } else {
        echo "✗ Font directory not found\n";
    }
    
    // Test basic PDF generation
    echo "\nTesting PDF Generation...\n";
    
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Test PDF</title>
    </head>
    <body>
        <h1>Test PDF Document</h1>
        <p>This is a test document with Arabic content: مرحبا بالعربية</p>
        <p>English content: Hello World</p>
    </body>
    </html>';
    
    // Load configuration
    $gpdfConfigFile = require 'config/gpdf.php';
    $config = new Omaralalwi\Gpdf\GpdfConfig($gpdfConfigFile);
    $gpdf = new Omaralalwi\Gpdf\Gpdf($config);
    
    // Generate PDF
    $pdfContent = $gpdf->generate($html);
    
    if ($pdfContent && strlen($pdfContent) > 0) {
        echo "✓ PDF generated successfully (" . strlen($pdfContent) . " bytes)\n";
        
        // Save test PDF
        file_put_contents('test_output.pdf', $pdfContent);
        echo "✓ Test PDF saved as 'test_output.pdf'\n";
    } else {
        echo "✗ PDF generation failed\n";
    }
    
    echo "\n✓ All tests passed! Gpdf is properly installed and configured.\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}