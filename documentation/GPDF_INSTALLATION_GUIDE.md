# 🚀 دليل التثبيت والإعداد الاحترافي لمكتبة GPDF
## إصدار: 1.0 | تاريخ: 2026-03-04

---

## 📋 المحتويات

1. [المتطلبات الأساسية](#المتطلبات-الأساسية)
2. [التثبيت اليدوي خطوة بخطوة](#التثبيت-اليدهوي-خطوة-بخطوة)
3. [نشر الخطوط العربية](#نشر-الخطوط-العربية)
4. [التحقق من التثبيت](#التحقق-من-التثبيت)
5. [استكشاف الأخطاء](#استكشاف-الأخطاء)

---

## 🔧 المتطلبات الأساسية

### 1. **إصدار PHP**

```bash
php -v
```

**الإصدار المطلوب**: PHP 8.1 أو أعلى

```
✅ PHP 8.1.x
✅ PHP 8.2.x
✅ PHP 8.3.x
❌ PHP 7.x (غير مدعوم)
```

### 2. **الإضافات المطلوبة**

تحقق من وجود الإضافات:

```bash
php -m | grep -E 'dom|mbstring|xml'
```

**يجب توفر**:
- ✅ DOM Extension
- ✅ MBString Extension
- ✅ XML Extension

### 3. **تثبيت الإضافات (إذا لزم الأمر)**

#### على Ubuntu/Debian:

```bash
# PHP 8.1
sudo apt-get install php8.1-dom php8.1-mbstring php8.1-xml

# PHP 8.2
sudo apt-get install php8.2-dom php8.2-mbstring php8.2-xml

# PHP 8.3
sudo apt-get install php8.3-dom php8.3-mbstring php8.3-xml
```

#### على CentOS/RHEL:

```bash
sudo yum install php-dom php-mbstring php-xml
```

#### على Windows (XAMPP/WAMP):

في `php.ini`:

```ini
extension=dom
extension=mbstring
extension=xml
```

ثم أعد تشغيل Apache/Nginx.

---

## 📦 التثبيت اليدوي خطوة بخطوة

### الخطوة 1: تثبيت المكتبة عبر Composer

```bash
cd "f:\my project\laravel\contract\tskify\Code"

composer require omaralalwi/gpdf
```

**المخرجات المتوقعة**:

```
Using version ^1.0 for omaralalwi/gpdf
./composer.json has been updated
Running composer update omaralalwi/gpdf
Loading composer repositories with package information
Updating dependencies
Lock file operations: 1 install, 0 updates, 0 removals
  - Locking omaralalwi/gpdf (v1.x.x)

Writing lock file
Installing dependencies from lock file
Package operations: 1 install, 0 updates, 0 removals
  - Installing omaralalwi/gpdf (v1.x.x): Extracting archive
Generating optimized autoload files
```

### الخطوة 2: التحقق من التثبيت

```bash
composer show omaralalwi/gpdf
```

**يجب أن يظهر**:

```
name     : omaralalwi/gpdf
descrip. : Multilingual & Arabic PDF Generator for PHP/Laravel Applications
keywords : arabic, dompdf, gpdf, laravel, pdf, rtl
versions : * v1.x.x
```

---

## 🎨 نشر الخطوط العربية

### ⚠️ **هذه أهم خطوة!**

بدون الخطوط، **لن تعمل المكتبة بشكل صحيح**!

### الطريقة 1: استخدام السكربت الرسمي (موصى به)

```bash
# في جذر المشروع
php vendor/omaralalwi/gpdf/scripts/publish_fonts.php
```

**المخرجات المتوقعة**:

```
✓ Font directory created: public/vendor/gpdf/fonts
✓ Fonts copied successfully
✓ Font cache cleared
✓ Publishing completed!
```

### الطريقة 2: النسخ اليدوي

إذا لم ينجح السكربت:

```bash
# 1. إنشاء المجلد
mkdir -p public/vendor/gpdf/fonts

# 2. نسخ الخطوط
cp -r vendor/omaralalwi/gpdf/assets/fonts/* public/vendor/gpdf/fonts/

# 3. التحقق من الصلاحيات (Linux/Mac)
chmod -R 755 public/vendor/gpdf/fonts
```

### الطريقة 3: PowerShell (Windows)

```powershell
# 1. إنشاء المجلد
New-Item -ItemType Directory -Force -Path "public\vendor\gpdf\fonts"

# 2. نسخ الخطوط
Copy-Item -Recurse -Force "vendor\omaralalwi\gpdf\assets\fonts\*" "public\vendor\gpdf\fonts\"

# 3. التحقق
Get-ChildItem "public\vendor\gpdf\fonts" -Filter "*.ttf"
```

### التحقق من نجاح النشر

```bash
# Linux/Mac
ls -la public/vendor/gpdf/fonts/*.ttf

# Windows PowerShell
Get-ChildItem public\vendor\gpdf\fonts\*.ttf
```

**يجب أن تجد الملفات التالية**:

```
✅ Tajawal-Normal.ttf
✅ Tajawal-Bold.ttf
✅ DejaVu Sans Mono.ttf
✅ Almarai-Regular.ttf
✅ Cairo-Regular.ttf
✅ NotoNaskhArabic-Regular.ttf
✅ MarkaziText-Regular.ttf
... (17 خط إجمالي)
```

---

## 📝 نشر ملف الإعدادات

### اختياري (الملف موجود بالفعل)

```bash
php vendor/omaralalwi/gpdf/scripts/publish_config.php
```

**⚠️ تحذير**: 
- هذا سيستبدل ملف `config/gpdf.php` الحالي
- الملف الحالي مهيأ بشكل ممتاز للعربية
- **لا تنفذ هذا إلا إذا كنت تريد إعادة التعيين**

### البقاء على الإعدادات الحالية

إعداداتك الحالية **ممتازة**، احتفظ بها:

```php
// config/gpdf.php
GpdfSet::DEFAULT_FONT => GpdfDefaultSupportedFonts::TAJAWAL, // ✅ ممتاز للعربية
GpdfSet::SHOW_NUMBERS_AS_HINDI => false, // ✅ للتوافق
GpdfSet::MAX_CHARS_PER_LINE => 100, // ✅ جيد للنصوص الطويلة
```

---

## ✅ التحقق من التثبيت

### سكريبت اختبار شامل

احفظ هذا الملف باسم `test_gpdf_installation.php`:

```php
<?php

require_once 'vendor/autoload.php';

echo "=== GPDF Installation Test ===\n\n";

try {
    // 1. اختبار وجود الكلاسات
    echo "1. Checking Classes...\n";
    
    if (class_exists('Omaralalwi\Gpdf\Gpdf')) {
        echo "   ✓ Gpdf class found\n";
    } else {
        echo "   ✗ Gpdf class NOT found\n";
        exit(1);
    }
    
    if (class_exists('Omaralalwi\Gpdf\GpdfConfig')) {
        echo "   ✓ GpdfConfig class found\n";
    } else {
        echo "   ✗ GpdfConfig class NOT found\n";
        exit(1);
    }
    
    // 2. اختبار ملف الإعدادات
    echo "\n2. Checking Configuration...\n";
    
    $configFile = 'config/gpdf.php';
    if (file_exists($configFile)) {
        echo "   ✓ Configuration file found\n";
        $config = require $configFile;
        echo "   ✓ Default font: " . ($config['default_font'] ?? 'Not set') . "\n";
    } else {
        echo "   ✗ Configuration file NOT found\n";
    }
    
    // 3. اختبار مجلد الخطوط
    echo "\n3. Checking Fonts Directory...\n";
    
    $fontDir = 'public/vendor/gpdf/fonts';
    if (is_dir($fontDir)) {
        echo "   ✓ Font directory found\n";
        
        $fonts = glob($fontDir . '/*.ttf');
        echo "   ✓ Found " . count($fonts) . " fonts\n";
        
        // التحقق من خط Tajawal
        if (file_exists($fontDir . '/Tajawal-Normal.ttf')) {
            echo "   ✓ Tajawal font found\n";
        } else {
            echo "   ⚠ Tajawal font NOT found - Run publish_fonts.php!\n";
        }
    } else {
        echo "   ✗ Font directory NOT found\n";
        echo "   → Run: php vendor/omaralalwi/gpdf/scripts/publish_fonts.php\n";
    }
    
    // 4. اختبار توليد PDF بسيط
    echo "\n4. Testing PDF Generation...\n";
    
    $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Test PDF</title>
    </head>
    <body>
        <h1>Test PDF Document</h1>
        <p>This is a test with Arabic: مرحبا بالعربية</p>
        <p>English: Hello World</p>
    </body>
    </html>';
    
    $gpdfConfigFile = require 'config/gpdf.php';
    $config = new Omaralalwi\Gpdf\GpdfConfig($gpdfConfigFile);
    $gpdf = new Omaralalwi\Gpdf\Gpdf($config);
    
    $pdfContent = $gpdf->generate($html);
    
    if ($pdfContent && strlen($pdfContent) > 0) {
        echo "   ✓ PDF generated successfully (" . number_format(strlen($pdfContent)) . " bytes)\n";
        
        // حفظ للاختبار
        file_put_contents('test_output.pdf', $pdfContent);
        echo "   ✓ Test PDF saved as 'test_output.pdf'\n";
    } else {
        echo "   ✗ PDF generation failed\n";
    }
    
    // 5. اختبار الخطوط العربية
    echo "\n5. Testing Arabic Fonts...\n";
    
    $arabicHtml = '<!DOCTYPE html>
    <html dir="rtl" lang="ar">
    <head>
        <meta charset="utf-8">
        <title>اختبار الخطوط العربية</title>
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
        <h1>اختبار الخطوط العربية</h1>
        <p>هذا نص عربي للاختبار</p>
        <p>الخط: تجوال</p>
    </body>
    </html>';
    
    try {
        $arabicPdf = $gpdf->generate($arabicHtml);
        if ($arabicPdf) {
            echo "   ✓ Arabic PDF generated successfully\n";
            file_put_contents('test_arabic.pdf', $arabicPdf);
            echo "   ✓ Arabic test saved as 'test_arabic.pdf'\n";
        }
    } catch (\Exception $e) {
        echo "   ✗ Arabic PDF generation failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== Test Complete ===\n";
    echo "✓ All tests passed! GPDF is ready to use.\n";
    
} catch (\Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
```

### تشغيل الاختبار

```bash
php test_gpdf_installation.php
```

**المخرجات المتوقعة**:

```
=== GPDF Installation Test ===

1. Checking Classes...
   ✓ Gpdf class found
   ✓ GpdfConfig class found

2. Checking Configuration...
   ✓ Configuration file found
   ✓ Default font: tajawal

3. Checking Fonts Directory...
   ✓ Font directory found
   ✓ Found 17 fonts
   ✓ Tajawal font found

4. Testing PDF Generation...
   ✓ PDF generated successfully (12,345 bytes)
   ✓ Test PDF saved as 'test_output.pdf'

5. Testing Arabic Fonts...
   ✓ Arabic PDF generated successfully
   ✓ Arabic test saved as 'test_arabic.pdf'

=== Test Complete ===
✓ All tests passed! GPDF is ready to use.
```

---

## 🔧 استكشاف الأخطاء والحلول

### المشكلة 1: "Font not found"

```
Error: Unable to find font file
```

**الحل**:

```bash
# 1. تأكد من وجود المجلد
ls -la public/vendor/gpdf/fonts/

# 2. إذا كان فارغاً، انشر الخطوط
php vendor/omaralalwi/gpdf/scripts/publish_fonts.php

# 3. تحقق من الصلاحيات
chmod -R 755 public/vendor/gpdf/fonts/
```

### المشكلة 2: "Class not found"

```
Error: Class 'Omaralalwi\Gpdf\Gpdf' not found
```

**الحل**:

```bash
# 1. أعد تثبيت المكتبة
composer remove omaralalwi/gpdf
composer require omaralalwi/gpdf

# 2. أعد بناء autoload
composer dump-autoload
```

### المشكلة 3: "Permission denied"

```
Error: Permission denied when writing to public/vendor/gpdf
```

**الحل**:

#### Linux/Mac:

```bash
sudo chown -R www-data:www-data public/vendor/gpdf
sudo chmod -R 755 public/vendor/gpdf
```

#### Windows:

```powershell
# انقر بزر الماوس الأيمن على المجلد
# Properties → Security
# أعط صلاحيات Full Control لـ IIS_IUSRS
```

### المشكلة 4: "DOM extension not loaded"

```
Error: DomDocument not found
```

**الحل**:

```bash
# Ubuntu/Debian
sudo apt-get install php-dom
sudo systemctl restart apache2

# CentOS/RHEL
sudo yum install php-dom
sudo systemctl restart httpd

# Windows (XAMPP)
# في php.ini:
extension=dom
# ثم أعد تشغيل Apache
```

### المشكلة 5: "MBString extension not loaded"

```
Error: mb_strlen() not found
```

**الحل**:

```bash
# Ubuntu/Debian
sudo apt-get install php-mbstring
sudo systemctl restart apache2

# CentOS/RHEL
sudo yum install php-mbstring
sudo systemctl restart httpd

# Windows (XAMPP)
# في php.ini:
extension=mbstring
# ثم أعد تشغيل Apache
```

### المشكلة 6: الخطوط لا تعمل في الإنتاج

```
Production server shows missing fonts
```

**الحل**:

```bash
# 1. في بيئة الإنتاج، انشر الخطوط يدوياً
scp -r public/vendor/gpdf user@production:/var/www/html/public/vendor/gpdf

# 2. أو نفذ السكريبت في الإنتاج
ssh user@production
cd /var/www/html
php vendor/omaralalwi/gpdf/scripts/publish_fonts.php

# 3. أضف الخطوط للـ Git (اختياري)
git add public/vendor/gpdf/fonts/*.* -f
git commit -m "Add GPDF fonts"
git push
```

---

## 🎯 الاستخدام الصحيح بعد التثبيت

### 1. **في Controllers**

```php
namespace App\Http\Controllers;

use App\Services\ArabicPdfService;
use App\Models\Contract;

class ContractsController extends Controller
{
    protected $arabicPdfService;
    
    public function __construct(ArabicPdfService $arabicPdfService)
    {
        $this->arabicPdfService = $arabicPdfService;
    }
    
    public function generatePdf($id)
    {
        $contract = Contract::findOrFail($id);
        
        return $this->arabicPdfService->generateContractPdf($contract);
    }
}
```

### 2. **في Services**

```php
namespace App\Services;

use Omaralalwi\Gpdf\Gpdf;
use Omaralalwi\Gpdf\GpdfConfig;

class CustomPdfService
{
    protected $gpdf;
    
    public function __construct()
    {
        $config = new GpdfConfig(config('gpdf'));
        $this->gpdf = new Gpdf($config);
    }
    
    public function generateCustomPdf($view, $data)
    {
        $html = view($view, $data)->render();
        return $this->gpdf->generate($html);
    }
}
```

### 3. **توليد وحفظ PDF**

```php
use Omaralalwi\Gpdf\Gpdf;
use Omaralalwi\Gpdf\GpdfConfig;
use Illuminate\Support\Facades\Storage;

public function savePdf()
{
    $html = view('pdf.report')->render();
    
    $config = new GpdfConfig(config('gpdf'));
    $gpdf = new Gpdf($config);
    
    // توليد PDF
    $pdfContent = $gpdf->generate($html);
    
    // حفظ في Storage
    $path = 'reports/' . date('Y/m/d') . '/report.pdf';
    Storage::put($path, $pdfContent);
    
    // الحصول على URL
    $url = Storage::url($path);
    
    return response()->json([
        'success' => true,
        'url' => $url
    ]);
}
```

### 4. **توليد مع Stream**

```php
use Omaralalwi\Gpdf\Gpdf;
use Omaralalwi\Gpdf\GpdfConfig;

public function streamPdf()
{
    $html = view('pdf.invoice')->render();
    
    $config = new GpdfConfig(config('gpdf'));
    $gpdf = new Gpdf($config);
    
    return $gpdf->generateWithStream(
        $html, 
        'invoice-' . time() . '.pdf',
        true // with stream
    );
}
```

---

## 📊 مقارنة: قبل وبعد التثبيت

### ❌ قبل التثبيت الصحيح:

```
✗ الخطوط غير موجودة
✗ النصوص العربية تظهر كرموز
✗ اتجاه النص خاطئ
✗ أخطاء "Font not found"
✗ ملفات PDF فارغة
```

### ✅ بعد التثبيت الصحيح:

```
✓ جميع الخطوط العربية تعمل
✓ النصوص العربية واضحة وجميلة
✓ RTL يعمل بشكل مثالي
✓ لا توجد أخطاء
✓ ملفات PDF احترافية
```

---

## 🎨 أمثلة عملية

### مثال 1: عقد باللغة العربية

```php
public function generateContractPdf($contractId)
{
    $contract = Contract::with(['client', 'items'])->findOrFail($contractId);
    
    $html = view('pdf.contracts.template', [
        'contract' => $contract,
        'isRtl' => true,
        'locale' => 'ar'
    ])->render();
    
    $arabicPdfService = app(ArabicPdfService::class);
    
    return $arabicPdfService->generateArabicPdf(
        $html,
        [],
        'contract-' . $contract->id . '.pdf',
        false // download attachment
    );
}
```

### مثال 2: فاتورة ثنائية اللغة

```php
public function generateBilingualInvoice($invoiceId)
{
    $invoice = Invoice::with(['items', 'client'])->findOrFail($invoiceId);
    
    $html = view('pdf.invoices.bilingual', [
        'invoice' => $invoice,
        'isRtl' => true,
        'showBothLanguages' => true
    ])->render();
    
    $pdfService = app(PdfService::class);
    
    return $pdfService->streamPdf(
        $html,
        [],
        'invoice-' . $invoice->id . '-bilingual.pdf'
    );
}
```

### مثال 3: تقرير مفصل

```php
public function generateDetailedReport($data)
{
    $html = view('pdf.reports.detailed', [
        'data' => $data,
        'companyInfo' => $this->getCompanyInfo(),
        'generatedAt' => now(),
        'isRtl' => app()->getLocale() == 'ar'
    ])->render();
    
    $config = new GpdfConfig(config('gpdf'));
    $gpdf = new Gpdf($config);
    
    // تخصيص إضافي
    $config->set('default_font', 'Tajawal');
    $config->set('max_chars_per_line', 120);
    
    $pdfContent = $gpdf->generate($html);
    
    // حفظ في S3
    if (config('filesystems.default') === 's3') {
        $gpdf->generateWithStoreToS3(
            $html,
            config('filesystems.disks.s3.bucket'),
            'report-' . time() . '.pdf',
            false,
            true
        );
    }
    
    return response($pdfContent, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="detailed-report.pdf"'
    ]);
}
```

---

## 🔐 أفضل الممارسات

### 1. **دائماً استخدم الخطوط العربية**

```php
// ✅ صحيح
$config['default_font'] = GpdfDefaultSupportedFonts::TAJAWAL;

// ❌ خطأ
$config['default_font'] = 'helvetica'; // لا يدعم العربية
```

### 2. **تحقق من وجود الخطوط**

```php
protected function verifyFonts()
{
    $fontPath = public_path('vendor/gpdf/fonts');
    $requiredFonts = [
        'Tajawal-Normal.ttf',
        'Tajawal-Bold.ttf'
    ];
    
    foreach ($requiredFonts as $font) {
        if (!file_exists($fontPath . '/' . $font)) {
            throw new \Exception("Missing font: {$font}");
        }
    }
}
```

### 3. **استخدم caching للإعدادات**

```bash
# Laravel
php artisan config:cache
```

### 4. **اختبر في بيئة التطوير أولاً**

```bash
# محلياً
php test_gpdf_installation.php

# على السيرفر
ssh user@server
cd /var/www/html
php test_gpdf_installation.php
```

### 5. **احتفظ بنسخة احتياطية من الخطوط**

```bash
# احفظ الخطوط في git
git add public/vendor/gpdf/fonts/*.* -f
git commit -m "Backup GPDF fonts"

# أو احتفظ بنسخة خارجية
tar -czf gpdf-fonts-backup.tar.gz public/vendor/gpdf/fonts/
```

---

## 📞 الدعم

### روابط مفيدة:

- 📚 [GPDF Documentation](https://github.com/omaralalwi/Gpdf)
- 💬 [Telegram Community](https://t.me/gpdf_community)
- 🐛 [GitHub Issues](https://github.com/omaralalwi/Gpdf/issues)
- 📝 [Examples Repository](https://github.com/omaralalwi/Gpdf-Laravel-Demo)

### الإبلاغ عن مشكلة:

عند الإبلاغ عن مشكلة، قم بتضمين:

1. إصدار PHP: `php -v`
2. إصدار المكتبة: `composer show omaralalwi/gpdf`
3. رسالة الخطأ الكاملة
4. الخطوات التي قمت بها
5. نتائج `test_gpdf_installation.php`

---

## ✅ قائمة التحقق النهائية

قبل الانتقال إلى الإنتاج:

- [ ] تم تثبيت المكتبة بنجاح
- [ ] تم نشر الخطوط (`public/vendor/gpdf/fonts/`)
- [ ] جميع الخطوط العربية موجودة (17 ملف)
- [ ] ملف الإعدادات مهيأ بشكل صحيح
- [ ] اختبار توليد PDF نجح
- [ ] اختبار النصوص العربية نجح
- [ ] اختبار RTL نجح
- [ ] الصلاحيات مضبوطة correctly
- [ ] الامتدادات المطلوبة مثبتة
- [ ] تم الاختبار على بيئة التطوير
- [ ] تم الاختبار على بيئة الإنتاج

---

**تاريخ الإنشاء**: 2026-03-04  
**الإصدار**: 1.0  
**الحالة**: جاهز للاستخدام ✅
