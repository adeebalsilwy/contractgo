# دليل التطبيق السريع - دعم العربية و RTL
# Quick Start Guide - Arabic & RTL Support

## 🚀 البدء السريع | Quick Start

### الخطوة 1: تغيير اللغة | Step 1: Change Language

```php
// في أي Controller أو Route
session(['my_locale' => 'ar']);
session(['text_direction' => 'rtl']);
```

أو من خلال الواجهة:
```
http://yoursite.com/settings/languages → اختر العربية
```

### الخطوة 2: تحديث التخطيط | Step 2: Update Layout

في ملف `layout.blade.php` الرئيسي:

```blade
<!DOCTYPE html>
<html dir="{{ is_rtl() ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    
    @if(is_rtl())
        <!-- Bootstrap RTL -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
        
        <!-- Custom RTL CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/rtl.css') }}">
        
        <!-- Arabic Font -->
        <style>
            body {
                font-family: 'Tajawal', sans-serif;
            }
        </style>
    @endif
</head>
<body class="{{ is_rtl() ? 'rtl-layout' : 'ltr-layout' }}">
    @yield('content')
</body>
</html>
```

### الخطوة 3: استخدام الدوال المساعدة | Step 3: Use Helper Functions

```blade
@php
    $isRtl = is_rtl();
    $direction = get_text_direction();
    $align = get_text_align();
@endphp

<!-- مثال -->
<div dir="{{ $direction }}" style="text-align: {{ $align }}">
    <h1>{{ get_label('welcome', 'Welcome') }}</h1>
</div>
```

---

## 📝 أمثلة سريعة | Quick Examples

### مثال 1: بطاقة | Example 1: Card

```blade
<div class="card" dir="{{ get_text_direction() }}">
    <div class="card-body">
        <h5 class="card-title" style="text-align: {{ get_text_align() }}">
            {{ get_label('project_info', 'Project Info') }}
        </h5>
        <p class="card-text" style="text-align: {{ get_text_align() }}">
            {{ get_label('description', 'Description') }}
        </p>
    </div>
</div>
```

### مثال 2: جدول | Example 2: Table

```blade
<table class="table" dir="{{ get_text_direction() }}">
    <thead>
        <tr>
            <th style="text-align: {{ get_text_align() }}">{{ get_label('name', 'Name') }}</th>
            <th style="text-align: {{ get_text_align() }}">{{ get_label('email', 'Email') }}</th>
            <th style="text-align: {{ get_text_align() }}">{{ get_label('status', 'Status') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td style="text-align: {{ get_text_align() }}">{{ $user->name }}</td>
            <td style="text-align: {{ get_text_align() }}">{{ $user->email }}</td>
            <td style="text-align: {{ get_text_align() }}">{{ $user->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
```

### مثال 3: نموذج | Example 3: Form

```blade
<form dir="{{ get_text_direction() }}">
    <div class="mb-3">
        <label class="form-label" style="text-align: {{ get_text_align() }}">
            {{ get_label('name', 'Name') }}
        </label>
        <input type="text" class="form-control">
    </div>
    
    <div class="mb-3">
        <label class="form-label" style="text-align: {{ get_text_align() }}">
            {{ get_label('email', 'Email') }}
        </label>
        <input type="email" class="form-control">
    </div>
    
    <button type="submit" class="btn btn-primary">
        {{ get_label('submit', 'Submit') }}
    </button>
</form>
```

---

## 🎯 الدوال المتاحة | Available Functions

| الدالة | Function | الوصف | Description | الإرجاع | Returns |
|--------|----------|-------|-------------|---------|---------|
| `is_rtl()` | Check if RTL | هل اللغة الحالية RTL؟ | Is current language RTL? | bool | true/false |
| `get_text_direction()` | Get direction | اتجاه النص | Text direction | string | 'rtl'/'ltr' |
| `get_text_align()` | Get alignment | محاذاة النص | Text alignment | string | 'right'/'left' |
| `html_dir_attribute()` | Get HTML dir | سمة الاتجاه لـ HTML | Direction attribute for HTML | string | 'dir="rtl"'/'dir="ltr"' |

---

## ✅ قائمة التحقق | Checklist

عند إنشاء صفحة جديدة بالعربية:

- [ ] إضافة `dir="{{ get_text_direction() }}"` للعنصر `<html>`
- [ ] تضمين `rtl.css` إذا كانت اللغة عربية
- [ ] استخدام `text-align: {{ get_text_align() }}` للنصوص
- [ ] تطبيق الفئات المناسبة (`rtl-layout`)
- [ ] اختبار الصفحة بعد تغيير اللغة

---

## 🧪 الاختبار | Testing

### اختبار سريع | Quick Test:

```bash
# في Terminal
php artisan tinker
```

```php
// في Tinker
session(['my_locale' => 'ar']);
session(['text_direction' => 'rtl']);

echo is_rtl() ? 'RTL' : 'LTR';  // RTL
echo get_text_direction();       // rtl
echo get_text_align();           // right
```

ثم افتح أي صفحة في المتصفح.

---

## 🔗 روابط مهمة | Important Links

### الملفات الرئيسية | Key Files:
- دوال المساعدة: `app/app_helpers.php`
- تنسيقات RTL: `public/assets/css/rtl.css`
- Middleware: `app/Http/Middleware/Language.php`

### ملفات التوثيق | Documentation Files:
- دليل التطبيق الشامل: `RTL_IMPLEMENTATION_GUIDE_AR.md`
- ملخص الدعم: `RTL_SUPPORT_SUMMARY_AR.md`
- تقرير الترجمة: `ARABIC_TRANSLATION_COMPLETION_REPORT.md`

---

## ⚡ نصائح سريعة | Quick Tips

1. **استخدم الدوال المساعدة** بدلاً من كتابة القيم يدوياً
2. **اختبر دائماً** بعد تغيير اللغة
3. **تحقق من ظهور** `rtl.css` في الصفحات العربية
4. **استخدم Bootstrap RTL** عند الحاجة

---

## 🎉 جاهز للاستخدام! | Ready to Use!

النظام الآن يدعم العربية و RTL بشكل كامل!

```
✅ الترجمة: 100%
✅ RTL: مطبق
✅ الدوال: متاحة
✅ التنسيقات: جاهزة
```

**ابدأ الآن بتغيير اللغة إلى العربية واستمتع!** 🌟
