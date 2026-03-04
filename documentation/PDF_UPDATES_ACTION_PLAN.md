# 🔧 خطة التحديثات المطلوبة - نظام PDF
## الأولويات والإجراءات الفورية

---

## 📊 ملخص الحالة الحالية

### ✅ النقاط الإيجابية:
1. ✅ مكتبة GPDF مثبتة ومهيأة بشكل ممتاز
2. ✅ ملف الإعدادات `config/gpdf.php` مثالي للعربية
3. ✅ خدمتا `PdfService` و `ArabicPdfService` احترافيتان
4. ✅ القوالب تدعم RTL والعربية بشكل كامل
5. ✅ معظم Controllers تستخدم الخدمات بشكل صحيح

### ⚠️ النقاط الحرجة:
1. ❌ **الخطوط غير منشورة** - مجلد `public/vendor/gpdf/fonts/` فارغ
2. ❌ **ContractsController** يستخدم DomPDF القديم بدلاً من GPDF
3. ⚠️ عدم توحيد في استخدام الخدمات بين Controllers

---

## 🔴 المرحلة 1: عاجل جداً (اليوم)

### المهمة 1.1: نشر الخطوط العربية

**الأولوية**: 🔴 حرجة  
**الوقت المطلوب**: 5 دقائق  
**التأثير**: عالي جداً - بدون الخطوط لن تعمل المكتبة

#### الخطوات:

```bash
# 1. الانتقال لمجلد المشروع
cd "f:\my project\laravel\contract\tskify\Code"

# 2. تنفيذ سكريبت نشر الخطوط
php vendor/omaralalwi/gpdf/scripts/publish_fonts.php

# 3. التحقق من النجاح
ls -la public/vendor/gpdf/fonts/*.ttf
```

#### التحقق من النتيجة:

```bash
# يجب أن تجد الملفات التالية:
Tajawal-Normal.ttf          # الأساسي
Tajawal-Bold.ttf            # للعناوين
DejaVu Sans Mono.ttf        # احتياطي
Almarai-Regular.ttf         # بديل
Cairo-Regular.ttf           # بديل
NotoNaskhArabic-Regular.ttf # للمخطوطات
MarkaziText-Regular.ttf     # للنصوص الكلاسيكية
... (17 ملف إجمالي)
```

#### إذا فشل السكريبت:

**الحل اليدوي (Windows PowerShell)**:

```powershell
# 1. إنشاء المجلد
New-Item -ItemType Directory -Force -Path "public\vendor\gpdf\fonts"

# 2. نسخ الخطوط يدوياً
Copy-Item -Recurse -Force "vendor\omaralalwi\gpdf\assets\fonts\*" "public\vendor\gpdf\fonts\"

# 3. التحقق
Get-ChildItem "public\vendor\gpdf\fonts\*.ttf" | Select-Object Name
```

---

### المهمة 1.2: اختبار التثبيت

**الأولوية**: 🔴 حرجة  
**الوقت المطلوب**: 3 دقائق  

#### إنشاء ملف الاختبار:

احفظ هذا الملف باسم `test_gpdf_quick.php`:

```php
<?php

require_once 'vendor/autoload.php';

echo "=== Quick GPDF Test ===\n\n";

try {
    // اختبار وجود الخطوط
    $fontDir = 'public/vendor/gpdf/fonts';
    
    if (!is_dir($fontDir)) {
        echo "❌ Font directory not found!\n";
        echo "→ Run: php vendor/omaralalwi/gpdf/scripts/publish_fonts.php\n";
        exit(1);
    }
    
    $fonts = glob($fontDir . '/*.ttf');
    echo "✓ Found " . count($fonts) . " fonts\n";
    
    // التحقق من Tajawal
    if (file_exists($fontDir . '/Tajawal-Normal.ttf')) {
        echo "✓ Tajawal font found\n";
    } else {
        echo "❌ Tajawal font NOT found\n";
        exit(1);
    }
    
    // اختبار توليد PDF
    $html = '<!DOCTYPE html>
    <html dir="rtl" lang="ar">
    <head><meta charset="utf-8"><title>Test</title></head>
    <body>
        <h1>اختبار</h1>
        <p>مرحبا بالعربية</p>
    </body>
    </html>';
    
    $config = new Omaralalwi\Gpdf\GpdfConfig(config('gpdf'));
    $gpdf = new Omaralalwi\Gpdf\Gpdf($config);
    
    $pdfContent = $gpdf->generate($html);
    
    if ($pdfContent && strlen($pdfContent) > 0) {
        echo "✓ Arabic PDF generated successfully\n";
        file_put_contents('test_quick.pdf', $pdfContent);
        echo "✓ Test saved as 'test_quick.pdf'\n";
        echo "\n✅ All tests passed!\n";
    } else {
        echo "❌ PDF generation failed\n";
        exit(1);
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
```

#### تشغيل الاختبار:

```bash
php test_gpdf_quick.php
```

**المخرجات المتوقعة**:

```
=== Quick GPDF Test ===

✓ Found 17 fonts
✓ Tajawal font found
✓ Arabic PDF generated successfully
✓ Test saved as 'test_quick.pdf'

✅ All tests passed!
```

---

### المهمة 1.3: تحديث ContractsController

**الأولوية**: 🔴 حرجة  
**الوقت المطلوب**: 10 دقائق  
**الملف**: [`app/Http/Controllers/ContractsController.php`](f:\my project\laravel\contract\tskify\Code\app\Http\Controllers\ContractsController.php)

#### المشكلة الحالية:

```php
// السطر 1275-1280 (تقريباً)
// ❌ قديم - يستخدم DomPDF مباشرة
$html = view('contracts.pdf_template', ...)->render();
$pdf = \App::make('dompdf.wrapper');
$pdf->loadHTML($html);
return $pdf->download('contract_' . $contract->id . '.pdf');
```

#### الحل:

استبدال الدالة `generatePdf()` بالكامل:

```php
/**
 * Generate PDF for a contract
 * 
 * @param int $id
 * @return \Illuminate\Http\Response
 */
public function generatePdf($id)
{
    try {
        // تحميل العقد مع العلاقات
        $contract = Contract::with([
            'client', 
            'project', 
            'contract_type', 
            'siteSupervisor', 
            'quantityApprover', 
            'accountant', 
            'reviewer', 
            'finalApprover',
            'createdBy',
            'archivedBy',
            'quantities',
            'approvals',
            'amendments',
            'journalEntries'
        ])->findOrFail($id);
        
        // التحقق من الصلاحيات
        if (!isAdminOrHasAllDataAccess() && !$this->user->contracts()->where('id', $id)->exists()) {
            return response()->json([
                'error' => true, 
                'message' => 'Unauthorized access to contract.'
            ], 403);
        }
        
        // استخدام خدمة PDF العربية
        $arabicPdfService = app('App\\Services\\ArabicPdfService');
        
        return $arabicPdfService->generateContractPdf($contract);
        
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'error' => true, 
            'message' => 'Contract not found.'
        ], 404);
    } catch (\Exception $e) {
        \Log::error('ContractsController@generatePdf error: ' . $e->getMessage());
        return response()->json([
            'error' => true, 
            'message' => 'Error generating PDF: ' . $e->getMessage()
        ], 500);
    }
}
```

#### التغييرات المطلوبة:

1. ✅ إزالة استخدام `\App::make('dompdf.wrapper')`
2. ✅ استخدام `ArabicPdfService`
3. ✅ إضافة معالجة أخطاء محسنة
4. ✅ إضافة Logging للأخطاء
5. ✅ التحقق من الصلاحيات

#### السكريبت الآلي للتحديث:

أنشئ ملف `update_contracts_controller.php`:

```php
<?php

$controllerPath = 'app/Http/Controllers/ContractsController.php';

if (!file_exists($controllerPath)) {
    echo "❌ Controller not found at: {$controllerPath}\n";
    exit(1);
}

$content = file_get_contents($controllerPath);

// البحث عن الدالة القديمة واستبدالها
$oldMethod = '/public function generatePdf\(\$id\)\s*\{[^}]*\$html = view\(\'contracts\.pdf_template\'.*?\$pdf->download\(.*?\);\s*\}/s';

$newMethod = <<<'PHP'
public function generatePdf($id)
{
    try {
        $contract = Contract::with([
            'client', 'project', 'contract_type', 'siteSupervisor', 
            'quantityApprover', 'accountant', 'reviewer', 'finalApprover',
            'createdBy', 'archivedBy', 'quantities', 'approvals', 
            'amendments', 'journalEntries'
        ])->findOrFail($id);
        
        if (!isAdminOrHasAllDataAccess() && !$this->user->contracts()->where('id', $id)->exists()) {
            return response()->json(['error' => true, 'message' => 'Unauthorized access to contract.'], 403);
        }
        
        $arabicPdfService = app('App\\Services\\ArabicPdfService');
        return $arabicPdfService->generateContractPdf($contract);
        
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => true, 'message' => 'Contract not found.'], 404);
    } catch (\Exception $e) {
        \Log::error('ContractsController@generatePdf error: ' . $e->getMessage());
        return response()->json(['error' => true, 'message' => 'Error generating PDF: ' . $e->getMessage()], 500);
    }
}
PHP;

if (preg_match($oldMethod, $content)) {
    $content = preg_replace($oldMethod, $newMethod, $content);
    file_put_contents($controllerPath, $content);
    echo "✅ ContractsController updated successfully!\n";
} else {
    echo "⚠️ Could not find old method or already updated.\n";
    echo "Please update manually.\n";
}
```

---

## 🟡 المرحلة 2: تحسينات (هذا الأسبوع)

### المهمة 2.1: توحيد الاستخدام في جميع Controllers

**الأولوية**: 🟡 متوسطة  
**الوقت المطلوب**: 30 دقيقة  

#### Controllers التي تستخدم PdfService بالفعل:

✅ ProjectsController  
✅ TasksController  
✅ UserController  
✅ ClientController  

✅ **كلها جيدة** - لا حاجة للتعديل

#### Controllers التي تحتاج تحديث:

**EstimatesInvoicesController** - ✅ ممتاز بالفعل!

```php
// ✅ يستخدم ArabicPdfService بشكل صحيح
$arabicPdfService = app('App\\Services\\ArabicPdfService');
return $arabicPdfService->generateEstimatePdf($estimate);
```

---

### المهمة 2.2: إضافة دالة downloadPdf في ContractsController

**الأولوية**: 🟡 متوسطة  
**الوقت المطلوب**: 5 دقائق  

#### إضافة الدالة:

```php
/**
 * Generate and download PDF for a contract
 *
 * @param int $id
 * @return \Illuminate\Http\Response
 */
public function downloadPdf($id)
{
    try {
        $contract = Contract::findOrFail($id);
        
        // Check if user has permission
        if (!isAdminOrHasAllDataAccess() && !$this->user->contracts()->where('id', $id)->exists()) {
            return response()->json(['error' => true, 'message' => 'Unauthorized access to contract.'], 403);
        }
        
        $arabicPdfService = app('App\\Services\\ArabicPdfService');
        
        $filename = 'contract-' . $contract->id . '-' . str_slug($contract->title) . '.pdf';
        
        return $arabicPdfService->generateArabicPdf(
            'pdf.contracts.template',
            [
                'contract' => $contract,
                'items' => $contract->quantities ?? [],
                'companyInfo' => $this->getCompanyInfo(),
            ],
            $filename,
            false // false = download attachment
        );
        
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => true, 'message' => 'Contract not found.'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => true, 'message' => 'Error generating PDF: ' . $e->getMessage()], 500);
    }
}

/**
 * Get company information
 *
 * @return array
 */
protected function getCompanyInfo()
{
    $generalSettings = get_settings('general_settings');
    
    return [
        'name' => $generalSettings['company_title'] ?? 'Company Name',
        'name_ar' => $generalSettings['company_title_ar'] ?? 'اسم الشركة',
        'name_en' => $generalSettings['company_title_en'] ?? 'Company Name',
        'address' => $generalSettings['company_address'] ?? '',
        'phone' => $generalSettings['company_phone'] ?? '',
        'email' => $generalSettings['company_email'] ?? '',
        'website' => $generalSettings['company_website'] ?? '',
        'vat_number' => $generalSettings['company_vat_number'] ?? '',
        'logo' => $generalSettings['company_logo'] ?? null,
        'full_logo' => $generalSettings['full_logo'] ?? null
    ];
}
```

---

### المهمة 2.3: إضافة Route للدالة الجديدة

**الأولوية**: 🟡 متوسطة  
**الوقت المطلوب**: 2 دقائق  

#### في `routes/web.php`:

```php
// Contract PDF routes
Route::get('/contracts/{id}/pdf', [ContractsController::class, 'generatePdf'])
    ->name('contracts.pdf.generate');
    
Route::get('/contracts/{id}/pdf/download', [ContractsController::class, 'downloadPdf'])
    ->name('contracts.pdf.download');
```

---

## 🟢 المرحلة 3: اختبارات وتحسينات (الأسبوع القادم)

### المهمة 3.1: كتابة Unit Tests

**الأولوية**: 🟢 منخفضة  
**الوقت المطلوب**: ساعة  

#### إنشاء ملف الاختبار:

```bash
php artisan make:test ArabicPdfServiceTest
```

#### محتوى الاختبار:

```php
<?php

namespace Tests\Feature;

use App\Services\ArabicPdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArabicPdfServiceTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_generates_arabic_pdf_with_tajawal_font()
    {
        $service = app(ArabicPdfService::class);
        
        $html = '<!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head><meta charset="utf-8"></head>
        <body>
            <h1>اختبار الخط العربي</h1>
            <p>مرحبا بالعالم</p>
        </body>
        </html>';
        
        $pdf = $service->generateArabicPdf($html, [], 'test.pdf', false);
        
        $this->assertNotEmpty($pdf->getContent());
        $this->assertEquals('application/pdf', $pdf->headers->get('Content-Type'));
    }
    
    /** @test */
    public function it_generates_contract_pdf()
    {
        $contract = \App\Models\Contract::factory()->create();
        $service = app(ArabicPdfService::class);
        
        $response = $service->generateContractPdf($contract);
        
        $this->assertNotEmpty($response->getContent());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }
    
    /** @test */
    public function it_handles_rtl_layout_correctly()
    {
        app()->setLocale('ar');
        
        $service = app(ArabicPdfService::class);
        
        $html = view('pdf.test', ['text' => 'نص عربي'])->render();
        
        $pdf = $service->generateArabicPdf($html);
        
        $this->assertStringContainsString('dir="rtl"', $html);
    }
}
```

#### تشغيل الاختبارات:

```bash
php artisan test --filter ArabicPdfServiceTest
```

---

### المهمة 3.2: تحسين الأداء

**الأولوية**: 🟢 منخفضة  
**الوقت المطلوب**: 45 دقيقة  

#### إضافة Caching للخطوط:

في `app/Services/PdfService.php`:

```php
use Illuminate\Support\Facades\Cache;

protected function getCachedConfig()
{
    return Cache::remember('gpdf_config', 3600, function () {
        return config('gpdf');
    });
}

public function __construct()
{
    $gpdfConfigFile = $this->getCachedConfig();
    $this->config = new GpdfConfig($gpdfConfigFile);
    $this->gpdf = new Gpdf($this->config);
}
```

---

## 📋 قائمة التحقق النهائية

### المرحلة 1 (عاجل):

- [ ] تنفيذ `php vendor/omaralalwi/gpdf/scripts/publish_fonts.php`
- [ ] التحقق من وجود 17 ملف خط في `public/vendor/gpdf/fonts/`
- [ ] تشغيل `php test_gpdf_quick.php` والنجاح
- [ ] تحديث `ContractsController::generatePdf()`
- [ ] اختبار توليد PDF العقد

### المرحلة 2 (هذا الأسبوع):

- [ ] إضافة `ContractsController::downloadPdf()`
- [ ] إضافة Routes الجديدة
- [ ] توحيد الاستخدام في جميع Controllers
- [ ] اختبار شامل لجميع أنواع PDF

### المرحلة 3 (الأسبوع القادم):

- [ ] كتابة Unit Tests
- [ ] كتابة Integration Tests
- [ ] إضافة Caching
- [ ] Performance Testing
- [ ] Production Deployment

---

## 🎯 نتائج متوقعة بعد التحديث

### قبل التحديث:

```
❌ الخطوط غير موجودة
❌ ContractsController يستخدم DomPDF القديم
❌ عدم توحيد في الخدمات
❌ لا توجد اختبارات
```

### بعد التحديث:

```
✅ جميع الخطوط العربية موجودة وتعمل
✅ جميع Controllers تستخدم GPDF
✅ استخدام موحد لـ ArabicPdfService
✅ اختبارات شاملة تغطي النظام
✅ أداء محسن مع Caching
✅ دعم كامل للعربية و RTL
```

---

## 📞 الدعم والمساعدة

### عند مواجهة مشكلة:

1. **تحقق من logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **تحقق من وجود الخطوط**:
   ```bash
   ls -la public/vendor/gpdf/fonts/*.ttf
   ```

3. **أعد اختبار التثبيت**:
   ```bash
   php test_gpdf_quick.php
   ```

4. **راجع التوثيق**:
   - [GPDF Documentation](https://github.com/omaralalwi/Gpdf)
   - [Installation Guide](documentation/GPDF_INSTALLATION_GUIDE.md)
   - [Analysis Report](documentation/GPDF_SYSTEM_ANALYSIS_REPORT.md)

---

## 🔗 الملفات المتعلقة بالتحديث

### الملفات التي تم تحليلها:

1. ✅ [`config/gpdf.php`](f:\my project\laravel\contract\tskify\Code\config\gpdf.php) - ممتاز
2. ✅ [`app/Services/PdfService.php`](f:\my project\laravel\contract\tskify\Code\app\Services\PdfService.php) - احترافي
3. ✅ [`app/Services/ArabicPdfService.php`](f:\my project\laravel\contract\tskify\Code\app\Services\ArabicPdfService.php) - مثالي
4. ⚠️ [`app/Http/Controllers/ContractsController.php`](f:\my project\laravel\contract\tskify\Code\app\Http\Controllers\ContractsController.php) - **يحتاج تحديث**
5. ✅ [`app/Http/Controllers/EstimatesInvoicesController.php`](f:\my project\laravel\contract\tskify\Code\app\Http\Controllers\EstimatesInvoicesController.php) - ممتاز
6. ✅ باقي Controllers - جيدة

### القوالب:

- ✅ [`resources/views/pdf/contracts/template.blade.php`](f:\my project\laravel\contract\tskify\Code\resources\views\pdf\contracts\template.blade.php)
- ✅ [`resources/views/pdf/estimates/template.blade.php`](f:\my project\laravel\contract\tskify\Code\resources\views\pdf\estimates\template.blade.php)
- ✅ باقي القوالب - جميعها جيدة

---

**تاريخ الخطة**: 2026-03-04  
**الحالة**: جاهزة للتنفيذ ✅  
**الأولوية القصوى**: نشر الخطوط + تحديث ContractsController
