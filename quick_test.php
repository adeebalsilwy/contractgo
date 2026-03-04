#!/usr/bin/env php
<?php

/**
 * سكريبت اختبار سريع لتثبيت GPDF
 * 
 * الاستخدام:
 * php quick_test.php
 * 
 * التاريخ: 2026-03-04
 */

require_once 'vendor/autoload.php';

echo "\n";
echo "╔════════════════════════════════════════╗\n";
echo "║   اختبار تثبيت مكتبة GPDF             ║\n";
echo "╚════════════════════════════════════════╝\n";
echo "\n";

$success = true;
$errors = [];

// 1. اختبار وجود المكتبة
echo "1. التحقق من وجود المكتبة...\n";
if (class_exists('Omaralalwi\Gpdf\Gpdf')) {
    echo "   ✓ تم العثور على Gpdf class\n";
} else {
    echo "   ✗ لم يتم العثور على Gpdf class\n";
    echo "   → قم بتشغيل: composer require omaralalwi/gpdf\n";
    $success = false;
    $errors[] = 'Missing Gpdf class';
}

if (class_exists('Omaralalwi\Gpdf\GpdfConfig')) {
    echo "   ✓ تم العثور على GpdfConfig class\n";
} else {
    echo "   ✗ لم يتم العثور على GpdfConfig class\n";
    $success = false;
    $errors[] = 'Missing GpdfConfig class';
}

// 2. اختبار ملف الإعدادات
echo "\n2. التحقق من ملف الإعدادات...\n";
$configFile = 'config/gpdf.php';
if (file_exists($configFile)) {
    echo "   ✓ ملف الإعدادات موجود\n";
    $config = require $configFile;
    
    if (isset($config['default_font'])) {
        echo "   ✓ الخط الافتراضي: {$config['default_font']}\n";
    } else {
        echo "   ⚠ الخط الافتراضي غير محدد\n";
    }
} else {
    echo "   ✗ ملف الإعدادات غير موجود\n";
    $success = false;
    $errors[] = 'Missing config file';
}

// 3. اختبار مجلد الخطوط
echo "\n3. التحقق من مجلد الخطوط...\n";
$fontDir = 'public/vendor/gpdf/fonts';

if (!is_dir($fontDir)) {
    echo "   ✗ مجلد الخطوط غير موجود\n";
    echo "   → قم بتشغيل: php vendor/omaralalwi/gpdf/scripts/publish_fonts.php\n";
    $success = false;
    $errors[] = 'Missing fonts directory';
} else {
    echo "   ✓ مجلد الخطوط موجود\n";
    
    $fonts = glob($fontDir . '/*.ttf');
    echo "   ✓ عدد الخطوط الموجودة: " . count($fonts) . "\n";
    
    // التحقق من خط Tajawal
    if (file_exists($fontDir . '/Tajawal-Normal.ttf')) {
        echo "   ✓ خط Tajawal موجود\n";
    } else {
        echo "   ✗ خط Tajawal غير موجود\n";
        echo "   → قم بتشغيل: php vendor/omaralalwi/gpdf/scripts/publish_fonts.php\n";
        $success = false;
        $errors[] = 'Missing Tajawal font';
    }
    
    if (file_exists($fontDir . '/Tajawal-Bold.ttf')) {
        echo "   ✓ خط Tajawal Bold موجود\n";
    } else {
        echo "   ⚠ خط Tajawal Bold غير موجود\n";
    }
}

// 4. اختبار توليد PDF بسيط
echo "\n4. اختبار توليد PDF...\n";

$html = '<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <title>اختبار</title>
</head>
<body>
    <h1>اختبار توليد PDF</h1>
    <p>هذا نص عربي للاختبار</p>
    <p>مرحبا بالعالم!</p>
    <ul>
        <li>البند الأول</li>
        <li>البند الثاني</li>
        <li>البند الثالث</li>
    </ul>
</body>
</html>';

try {
    $gpdfConfigFile = config('gpdf');
    $config = new Omaralalwi\Gpdf\GpdfConfig($gpdfConfigFile);
    $gpdf = new Omaralalwi\Gpdf\Gpdf($config);
    
    $startTime = microtime(true);
    $pdfContent = $gpdf->generate($html);
    $endTime = microtime(true);
    
    $duration = round(($endTime - $startTime) * 1000, 2);
    
    if ($pdfContent && strlen($pdfContent) > 0) {
        echo "   ✓ تم توليد PDF بنجاح\n";
        echo "   ✓ حجم الملف: " . number_format(strlen($pdfContent)) . " bytes\n";
        echo "   ✓ الوقت المستغرق: {$duration} ms\n";
        
        // حفظ الملف للاختبار
        $testFile = 'test_gpdf_' . date('Ymd_His') . '.pdf';
        file_put_contents($testFile, $pdfContent);
        echo "   ✓ تم حفظ الملف: {$testFile}\n";
    } else {
        echo "   ✗ فشل توليد PDF\n";
        $success = false;
        $errors[] = 'PDF generation failed';
    }
    
} catch (\Exception $e) {
    echo "   ✗ خطأ في توليد PDF: " . $e->getMessage() . "\n";
    echo "   Trace: " . $e->getTraceAsString() . "\n";
    $success = false;
    $errors[] = 'PDF generation error: ' . $e->getMessage();
}

// 5. اختبار دعم العربية
echo "\n5. اختبار دعم اللغة العربية...\n";

$arabicHtml = '<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <style>
        @font-face {
            font-family: "Tajawal";
            src: url("'.public_path('vendor/gpdf/fonts/Tajawal-Normal.ttf').'") format("truetype");
        }
        body {
            font-family: "Tajawal", sans-serif;
        }
    </style>
</head>
<body>
    <h1>اختبار الخط العربي</h1>
    <p style="font-size: 18pt;">
        بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ
    </p>
    <p>
        هذا اختبار للغة العربية مع اتجاه RTL
    </p>
    <p>
        الأرقام: ١، ٢، ٣، ٤، ٥
    </p>
</body>
</html>';

try {
    $arabicPdf = $gpdf->generate($arabicHtml);
    
    if ($arabicPdf && strlen($arabicPdf) > 0) {
        echo "   ✓ تم توليد PDF بالعربية بنجاح\n";
        
        $arabicFile = 'test_arabic_' . date('Ymd_His') . '.pdf';
        file_put_contents($arabicFile, $arabicPdf);
        echo "   ✓ تم حفظ الملف العربي: {$arabicFile}\n";
    } else {
        echo "   ✗ فشل توليد PDF بالعربية\n";
        $success = false;
        $errors[] = 'Arabic PDF generation failed';
    }
    
} catch (\Exception $e) {
    echo "   ✗ خطأ في توليد PDF بالعربية: " . $e->getMessage() . "\n";
    $success = false;
    $errors[] = 'Arabic PDF generation error: ' . $e->getMessage();
}

// 6. الخلاصة
echo "\n";
echo "╔════════════════════════════════════════╗\n";
if ($success) {
    echo "║           ✅ جميع الاختبارات نجحت      ║\n";
    echo "╚════════════════════════════════════════╝\n";
    echo "\n";
    echo "🎉 النظام جاهز للاستخدام!\n";
    echo "\n";
    echo "الملفات التي تم إنشاؤها:\n";
    echo "  - " . $testFile . "\n";
    echo "  - " . $arabicFile . "\n";
    echo "\n";
    exit(0);
} else {
    echo "║           ❌ بعض الاختبارات فشلت       ║\n";
    echo "╚════════════════════════════════════════╝\n";
    echo "\n";
    echo "الأخطاء:\n";
    foreach ($errors as $i => $error) {
        echo "   " . ($i + 1) . ". {$error}\n";
    }
    echo "\n";
    echo "الحلول المقترحة:\n";
    echo "  1. تأكد من تثبيت المكتبة: composer require omaralalwi/gpdf\n";
    echo "  2. انشر الخطوط: php vendor/omaralalwi/gpdf/scripts/publish_fonts.php\n";
    echo "  3. تحقق من وجود PHP extensions: dom, mbstring, xml\n";
    echo "\n";
    exit(1);
}
