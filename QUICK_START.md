# 🚀 البدء السريع - نظام PDF
## دليل من 5 دقائق

---

## ⚡ الخطوة 1: اختبار النظام (دقيقة واحدة)

```bash
cd "f:\my project\laravel\contract\tskify\Code"
php quick_test.php
```

### ✅ إذا كانت النتيجة ناجحة:

```
╔════════════════════════════════════════╗
║           ✅ جميع الاختبارات نجحت      ║
╚════════════════════════════════════════╝

🎉 النظام جاهز للاستخدام!
```

**→ انتقل مباشرة للخطوة 3**

### ❌ إذا فشل اختبار الخطوط:

```
✗ مجلد الخطوط غير موجود
أو
✗ خط Tajawal غير موجود
```

**→ انتقل للخطوة 2**

---

## ⚡ الخطوة 2: نشر الخطوط (3 دقائق)

```bash
php vendor/omaralalwi/gpdf/scripts/publish_fonts.php
```

**تحقق من النجاح**:

```bash
ls -la public/vendor/gpdf/fonts/*.ttf
```

يجب أن تجد 17 ملف، منها:
- `Tajawal-Normal.ttf`
- `Tajawal-Bold.ttf`

ثم أعد: `php quick_test.php`

---

## ⚡ الخطوة 3: تحديث ContractsController (دقيقتين)

افتح الملف:
```
app/Http/Controllers/ContractsController.php
```

ابحث عن الدالة `generatePdf()` (السطر 1208 تقريباً)

**استبدل الكود بالكامل بـ**:

```php
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
```

---

## ✅ الخطوة 4: اختبار نهائي (دقيقة واحدة)

افتح المتصفح:
```
http://localhost:8000/contracts/{id}/pdf
```

استبدل `{id}` برقم عقد حقيقي.

**يجب أن يبدأ تحميل PDF فوراً!** 🎉

---

## 📋 ملخص سريع

### ما تم إنجازه:

- ✅ اختبار النظام
- ✅ نشر الخطوط (إذا لزم الأمر)
- ✅ تحديث ContractsController
- ✅ اختبار التحميل

### الوقت الإجمالي: **5-7 دقائق**

---

## 🎯 الخطوات التالية (اختياري)

### للمزيد من التفاصيل:

1. **دليل التثبيت الكامل**: [GPDF_INSTALLATION_GUIDE.md](documentation/GPDF_INSTALLATION_GUIDE.md)
2. **التحليل الشامل**: [GPDF_SYSTEM_ANALYSIS_REPORT.md](documentation/GPDF_SYSTEM_ANALYSIS_REPORT.md)
3. **خطة العمل**: [PDF_UPDATES_ACTION_PLAN.md](documentation/PDF_UPDATES_ACTION_PLAN.md)
4. **الملخص العربي**: [PDF_SUMMARY_AR.md](documentation/PDF_SUMMARY_AR.md)

### لتحسينات إضافية:

- إضافة downloadPdf() في ContractsController
- كتابة Unit Tests
- تحسين الأداء مع Caching

---

## 🆘 مشكلة سريعة؟

### "Font directory not found"

```bash
php vendor/omaralalwi/gpdf/scripts/publish_fonts.php
```

### "Class not found"

```bash
composer dump-autoload
```

### "Unauthorized access"

تأكد من أن المستخدم لديه صلاحية viewing العقد.

### PDF لا يحمل

1. تحقق من logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. شغّل الاختبار:
   ```bash
   php quick_test.php
   ```

---

## ✨ خلاصة

النظام الآن **جاهز للاستخدام**! 🎉

**ما تحتاجه**:
- ✅ الخطوط منشورة
- ✅ ContractsController محدث
- ✅ الاختبار ناجح

**الوقت المستغرق**: 5-7 دقائق فقط!

---

**تاريخ الإنشاء**: 2026-03-04  
**الوقت المتوقع**: 5-7 دقائق  
**المستوى**: مبتدئ ✅
