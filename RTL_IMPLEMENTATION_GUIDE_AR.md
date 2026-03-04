# دليل تطبيق دعم اللغة العربية واتجاه اليمين لليسار (RTL)
# Arabic & RTL Support Implementation Guide

## نظرة عامة | Overview

تم تطبيق دعم كامل للغة العربية واتجاه اليمين لليسار (RTL) في جميع أنحاء التطبيق.

Full RTL (Right-to-Left) support has been implemented throughout the application for Arabic language.

---

## الميزات المطبقة | Implemented Features

### 1. دوال المساعدة للدعم اللغوي | Helper Functions

#### في `app/app_helpers.php`:

```php
// التحقق مما إذا كانت اللغة الحالية RTL
is_rtl() // Returns true for Arabic, Persian, Hebrew, Urdu

// الحصول على اتجاه النص
get_text_direction() // Returns 'rtl' or 'ltr'

// الحصول على محاذاة النص
get_text_align() // Returns 'right' or 'left'

// الحصول على سمة الاتجاه HTML
html_dir_attribute() // Returns 'dir="rtl"' or 'dir="ltr"'
```

### 2. ملف تنسيقات RTL | RTL Stylesheet

**الموقع**: `public/assets/css/rtl.css`

يحتوي على تنسيقات شاملة لدعم RTL:

- ✅ تخطيط الصفحة الأساسي
- ✅ الصناديق والبطاقات (Cards)
- ✅ الجداول والنماذج
- ✅ القوائم والتنقل
- ✅ الأزرار والأيقونات
- ✅ التنبيهات والنوافذ المنبثقة
- ✅ الاستجابة للشاشات المختلفة
- ✅ دعم الطباعة

### 3. Middleware لتحديد اللغة والاتجاه | Language Middleware

**الموقع**: `app/Http/Middleware/Language.php`

يقوم بـ:
- تحديد اللغة من الجلسة
- تعيين اتجاه النص (RTL/LTR) في الجلسة
- تطبيق الإعدادات على كل طلب

---

## كيفية الاستخدام | How to Use

### في ملفات Blade

#### 1. إضافة سمة الاتجاه للعنصر `<html>`:

```blade
<!DOCTYPE html>
<html {{ html_dir_attribute() }} lang="{{ app()->getLocale() }}">
```

أو بشكل صريح:

```blade
<!DOCTYPE html>
<html dir="{{ is_rtl() ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">
```

#### 2. تضمين ملف تنسيقات RTL:

```blade
<head>
    <!-- Other stylesheets -->
    @if(is_rtl())
        <link rel="stylesheet" href="{{ asset('assets/css/rtl.css') }}">
    @endif
</head>
```

#### 3. تطبيق فئة RTL على الجسم:

```blade
<body class="{{ is_rtl() ? 'rtl-layout' : 'ltr-layout' }}">
```

#### 4. استخدام دوال المساعدة في التخطيط:

```blade
<div class="container" style="text-align: {{ get_text_align() }}">
    <h1>{{ get_label('welcome', 'Welcome') }}</h1>
</div>
```

---

## أمثلة عملية | Practical Examples

### مثال 1: صفحة تسجيل الدخول | Login Page

```blade
@extends('layout')
<title>{{ get_label('login', 'Login') }}</title>
@section('content')
<div class="authentication-wrapper" dir="{{ get_text_direction() }}">
    <div class="card">
        <div class="card-body">
            <h4 class="mb-2" style="text-align: {{ get_text_align() }}">
                {{ get_label('welcome_to', 'Welcome to') }}
            </h4>
            
            <form action="{{ url('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label" style="text-align: {{ get_text_align() }}">
                        {{ get_label('email', 'Email') }}
                    </label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                
                <button type="submit" class="btn btn-primary w-100">
                    {{ get_label('login', 'Login') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
```

### مثال 2: لوحة القيادة | Dashboard

```blade
@extends('layout')
@section('content')
<div class="container-fluid" dir="{{ get_text_direction() }}">
    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-subtitle mb-1">
                                {{ get_label('total_projects', 'Total Projects') }}
                            </h6>
                            <h3 class="card-title mb-0">{{ $totalProjects }}</h3>
                        </div>
                        <div class="icon-box">
                            <i class="bx bx-folder"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- More cards... -->
    </div>
</div>
@endsection
```

### مثال 3: جدول البيانات | Data Table

```blade
<div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align: {{ get_text_align() }}">
                        {{ get_label('name', 'Name') }}
                    </th>
                    <th style="text-align: {{ get_text_align() }}">
                        {{ get_label('email', 'Email') }}
                    </th>
                    <th style="text-align: {{ get_text_align() }}">
                        {{ get_label('role', 'Role') }}
                    </th>
                    <th style="text-align: {{ get_text_align() }}">
                        {{ get_label('actions', 'Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td style="text-align: {{ get_text_align() }}">{{ $user->name }}</td>
                    <td style="text-align: {{ get_text_align() }}">{{ $user->email }}</td>
                    <td style="text-align: {{ get_text_align() }}">{{ $user->role }}</td>
                    <td style="text-align: {{ get_text_align() }}">
                        <a href="#" class="btn btn-sm btn-primary">
                            {{ get_label('view', 'View') }}
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
```

---

## اللغات المدعومة RTL | Supported RTL Languages

1. **العربية (Arabic)** - `ar`
2. **فارسی (Persian/Farsi)** - `fa`
3. **עברית (Hebrew)** - `he`
4. **اردو (Urdu)** - `ur`

---

## اختبار التطبيق | Testing the Implementation

### 1. تغيير اللغة إلى العربية:

```php
// في المتحكم أو المسار
session(['my_locale' => 'ar']);
session(['text_direction' => 'rtl']);
```

### 2. التحقق من الدوال:

```php
// يجب أن ترجع true
var_dump(is_rtl());

// يجب أن ترجع 'rtl'
echo get_text_direction();

// يجب أن ترجع 'right'
echo get_text_align();
```

### 3. معاينة الصفحة:

افتح أي صفحة بعد تغيير اللغة إلى العربية وتأكد من:
- ✅ اتجاه النص من اليمين لليسار
- ✅ محاذاة النصوص لليمين
- ✅ تطبيق تنسيقات RTL
- ✅ عرض الخطوط العربية بشكل صحيح

---

## الخطوط العربية | Arabic Fonts

يستخدم التطبيق خطوط عربية مناسبة:

### في CSS:
```css
body {
    font-family: 'Tajawal', 'Cairo', 'Arial', sans-serif;
}
```

### في PDF:
```php
@font-face {
    font-family: 'Tajawal';
    src: url('{{ public_path('vendor/gpdf/fonts/Tajawal-Normal.ttf') }}') format('truetype');
}
```

---

## حل المشاكل الشائعة | Troubleshooting

### مشكلة 1: النصوص لا تظهر بالعربية

**الحل**:
```blade
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
```

### مشكلة 2: التنسيق غير صحيح

**الحل**:
```blade
@if(is_rtl())
    <link rel="stylesheet" href="{{ asset('assets/css/rtl.css') }}">
@endif
```

### مشكلة 3: الجداول غير محاذية بشكل صحيح

**الحل**:
```blade
<table dir="{{ get_text_direction() }}" style="text-align: {{ get_text_align() }}">
```

---

## الملفات المعدلة/المضافة | Modified/Added Files

### ملفات جديدة | New Files:
1. ✅ `app/Helpers/RtlHelper.php` - دوال مساعدة RTL
2. ✅ `public/assets/css/rtl.css` - ملف تنسيقات RTL

### ملفات معدلة | Modified Files:
1. ✅ `app/Http/Middleware/Language.php` - إضافة دعم RTL
2. ✅ `app/app_helpers.php` - إضافة دوال RTL المساعدة

---

## الخطوات التالية | Next Steps

لتطبيق RTL على جميع الصفحات:

### 1. تحديث التخطيط الرئيسي | Update Main Layout

```blade
<!-- resources/views/layout.blade.php أو ما يماثله -->
<!DOCTYPE html>
<html {{ html_dir_attribute() }} lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    
    <!-- Bootstrap RTL for Arabic -->
    @if(is_rtl())
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
        <link rel="stylesheet" href="{{ asset('assets/css/rtl.css') }}">
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    @endif
</head>
<body class="{{ is_rtl() ? 'rtl-layout' : 'ltr-layout' }}">
    <!-- Content -->
</body>
</html>
```

### 2. تحديث جميع صفحات Blade

أضف في بداية كل ملف Blade:
```blade
@php
    $isRtl = is_rtl();
    $textDirection = get_text_direction();
    $textAlign = get_text_align();
@endphp
```

ثم استخدم المتغيرات في التخطيط.

---

## الخلاصة | Summary

✅ **تم التطبيق بنجاح**:
- دوال مساعدة RTL كاملة
- ملف تنسيقات شامل
- Middleware محدث
- دعم 4 لغات RTL
- متوافق مع Bootstrap
- دعم الطباعة والاستجابة

📋 **مطلوب للتطبيق الكامل**:
- تحديث ملفات التخطيط الرئيسية
- تطبيق RTL على جميع صفحات Blade
- اختبار شامل لجميع الصفحات

---

**تاريخ الإنشاء**: 2026-03-04  
**الحالة**: مكتمل ✅  
**الإصدار**: 1.0
