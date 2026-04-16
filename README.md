# <div dir="rtl">فراپیامک لاراول - Farapayamak Laravel</div>

> <div dir="rtl">پکیج رسمی و کامل وب سرویس فراپیامک (ملی پیامک) برای لاراول</div>

[![Latest Version](https://img.shields.io/packagist/v/amirrajabzadeh/farapayamak-laravel.svg?style=flat-square)](https://packagist.org/packages/amirrajabzadeh/farapayamak-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/amirrajabzadeh/farapayamak-laravel.svg?style=flat-square)](https://packagist.org/packages/amirrajabzadeh/farapayamak-laravel)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-8892BF.svg?style=flat-square)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/laravel-8.x%20%7C%209.x%20%7C%2010.x%20%7C%2011.x%20%7C%2012.x-FF2D20.svg?style=flat-square)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

---

<div dir="rtl">

**نویسنده:** امیر رجب زاده  
**ایمیل:** [amir.irdev@gmail.com](mailto:amir.irdev@gmail.com)  
**وبسایت:** [https://amirrajabzadeh.ir](https://amirrajabzadeh.ir)  
**گیت‌هاب:** [amirrajabzadeh/farapayamak-laravel](https://github.com/amirrajabzadeh/farapayamak-laravel)

</div>

---

## <div dir="rtl">✨ ویژگی‌ها</div>

<div dir="rtl">

- ✅ پشتیبانی از تمام **8 وب سرویس** فراپیامک
- ✅ متد **SendOtp** برای ارسال کد تأیید یکبارمصرف
- ✅ **اضافه شدن خودکار "لغو11"** به انتهای پیام (الزامی برای وب سرویس)
- ✅ ارسال پیامک **ساده**، **پیشرفته**، **چندتایی** و **زماندار**
- ✅ مدیریت کامل **دفترچه تلفن** و **گروه‌ها**
- ✅ دریافت پیامک‌های **رسیده** از صندوق ورودی
- ✅ دریافت **اعتبار** و **وضعیت تحویل** پیامک
- ✅ **خروجی آرایه استاندارد** با ساختار یکسان برای همه متدها
- ✅ **بدون نیاز به دیتابیس**
- ✅ **کامنت‌گذاری کامل به فارسی**
- ✅ پشتیبانی از **لاراول 8 تا 12**
- ✅ پشتیبانی از **PHP 7.4 تا 8.4**

</div>

---

## <div dir="rtl">📋 نیازمندی‌ها</div>

<div dir="rtl">

- **PHP** نسخه 7.4 یا بالاتر (تا 8.4)
- **لاراول** نسخه 8.x، 9.x، 10.x، 11.x یا 12.x
- **افزونه‌های PHP:**
  - `ext-soap` (برای ارتباط با وب سرویس SOAP)
  - `ext-json` (برای پردازش JSON)

> **نکته:** برای فعال کردن افزونه SOAP در فایل `php.ini`، خط `extension=soap` را از حالت کامنت خارج کنید.

</div>

---

## <div dir="rtl">📦 نصب</div>

```bash
composer require amirrajabzadeh/farapayamak-laravel
```

---

## <div dir="rtl">⚙️ تنظیمات</div>

### <div dir="rtl">1. افزودن متغیرهای محیطی در فایل `.env`</div>

```env
FARAPAYAMAK_USERNAME=نام_کاربری_شما
FARAPAYAMAK_PASSWORD=رمز_عبور_شما
FARAPAYAMAK_DEBUG=false
```

### <div dir="rtl">2. انتشار فایل کانفیگ (اختیاری)</div>

```bash
php artisan vendor:publish --tag=farapayamak-config
```

<div dir="rtl">

فایل کانفیگ در `config/farapayamak.php` قرار می‌گیرد.

</div>

---

## <div dir="rtl">🚀 راهنمای استفاده</div>

### <div dir="rtl">ساختار خروجی استاندارد</div>

تمامی متدهای این پکیج یک **آرایه استاندارد** با ساختار زیر برمی‌گردانند:

```php
[
    'success' => true,      // boolean: موفقیت یا خطا
    'message' => '...',     // string: پیام توضیحی فارسی
    'data' => mixed,        // mixed: داده اصلی (RecId, Credit, ...)
    'code' => null,         // int|null: کد خطا (در صورت موفقیت null)
    'raw' => mixed          // mixed: پاسخ خام وب سرویس (برای دیباگ)
]
```

### <div dir="rtl">1. ارسال پیامک ساده</div>

```php
<?php

namespace App\Http\Controllers;

use Amirrajabzadeh\FarapayamakLaravel\Facades\FaraPayamak;

class SmsController extends Controller
{
    public function sendSms()
    {
        $result = FaraPayamak::sendSimpleSms(
            '09130908908',    // شماره گیرنده
            '5000xxxx',       // شماره فرستنده (ثبت شده در پنل)
            'سلام! این یک پیام آزمایشی است.'  // متن پیام (لغو11 خودکار اضافه می‌شود)
        );

        if ($result['success']) {
            return "✅ " . $result['message'] . " - RecId: " . $result['data'];
        }
        
        return "❌ خطا: " . $result['message'] . " (کد: " . $result['code'] . ")";
    }
}
```

### <div dir="rtl">2. ارسال OTP (کد تأیید)</div>

```php
<?php

namespace App\Http\Controllers;

use Amirrajabzadeh\FarapayamakLaravel\Facades\FaraPayamak;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string|size:11',
            'code' => 'required|integer|digits:4|digits:6'
        ]);

        $result = FaraPayamak::sendOtp(
            $request->mobile,          // شماره گیرنده
            '',                        // شماره فرستنده (اختیاری)
            (int)$request->code        // کد عددی 4 یا 6 رقمی
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['message']
        ], 500);
    }
}
```

### <div dir="rtl">3. دریافت اعتبار</div>

```php
$result = FaraPayamak::getCredit();

if ($result['success']) {
    echo $result['message'];  // "اعتبار شما 10,270 ریال است"
    echo "مبلغ: " . $result['data'];  // 10270.95
}
```

### <div dir="rtl">4. دریافت وضعیت تحویل پیامک</div>

```php
$result = FaraPayamak::getDeliveryStatus($recId);

if ($result['success']) {
    echo "وضعیت: " . $result['message'];  // "رسیده به گوشی"
    echo "کد: " . $result['data'];  // 1
}
```

### <div dir="rtl">5. دریافت پیامک‌های رسیده</div>

```php
use Amirrajabzadeh\FarapayamakLaravel\Facades\FaraPayamakReceive;

// دریافت 50 پیامک آخر
$result = FaraPayamakReceive::getInboxMessages(1, 50);

if ($result['success']) {
    $messages = $result['data'];
    foreach ($messages as $message) {
        echo $message['text'];
    }
}
```

### <div dir="rtl">6. مدیریت دفترچه تلفن</div>

```php
use Amirrajabzadeh\FarapayamakLaravel\Facades\FaraPayamakContacts;

// دریافت لیست گروه‌ها
$result = FaraPayamakContacts::getGroups();

if ($result['success']) {
    $groups = $result['data'];
}

// اضافه کردن گروه جدید
$result = FaraPayamakContacts::addGroup('مشتریان ویژه', 'گروه تست');
```

### <div dir="rtl">7. ارسال زماندار</div>

```php
use Amirrajabzadeh\FarapayamakLaravel\Facades\FaraPayamakSchedule;

$result = FaraPayamakSchedule::addSchedule(
    '09130908908',                    // شماره گیرنده
    '5000xxxx',                       // شماره فرستنده
    'پیامک زماندار',                   // متن
    false,                            // فلش
    '2024-12-25 14:30:00',           // تاریخ و زمان ارسال
    0                                 // دوره تکرار (0=بدون تکرار)
);

if ($result['success']) {
    echo "زمانبندی با موفقیت انجام شد. شناسه: " . $result['data'];
}
```

---

## <div dir="rtl">🔧 تزریق مستقیم (بدون Facade)</div>

```php
<?php

namespace App\Http\Controllers;

use Amirrajabzadeh\FarapayamakLaravel\Services\SendService;

class SmsController extends Controller
{
    protected $sendService;

    public function __construct(SendService $sendService)
    {
        $this->sendService = $sendService;
    }

    public function send()
    {
        $result = $this->sendService->sendSimpleSms(
            '09130908908',
            '5000xxxx',
            'متن پیامک'
        );
        
        return $result;
    }
}
```

---

## <div dir="rtl">📚 سایر سرویس‌ها</div>

<div dir="rtl">

پکیج شامل 8 سرویس مجزا است:

</div>

| Facade | سرویس | <div dir="rtl">توضیحات</div> |
|:---|:---|:---|
| `FaraPayamak` یا `FaraPayamakSend` | Send | <div dir="rtl">ارسال پیامک، OTP، دریافت اعتبار</div> |
| `FaraPayamakReceive` | Receive | <div dir="rtl">دریافت پیامک‌های رسیده</div> |
| `FaraPayamakContacts` | Contacts | <div dir="rtl">مدیریت دفترچه تلفن</div> |
| `FaraPayamakSchedule` | Schedule | <div dir="rtl">ارسال زماندار</div> |
| `FaraPayamakActions` | Actions | <div dir="rtl">ارسال انبوه (Bulk)</div> |
| `FaraPayamakVoice` | Voice | <div dir="rtl">پیامک صوتی</div> |
| `FaraPayamakUsers` | Users | <div dir="rtl">مدیریت کاربران</div> |
| `FaraPayamakTickets` | Tickets | <div dir="rtl">مدیریت تیکت‌ها</div> |

---

## <div dir="rtl">⚠️ کدهای خطا</div>

| کد | <div dir="rtl">توضیحات</div> |
|:---|:---|
| **>1000** | <div dir="rtl">موفقیت آمیز (RecId - شناسه پیامک)</div> |
| **0** | <div dir="rtl">نام کاربری یا رمز عبور اشتباه است</div> |
| **1** | <div dir="rtl">درخواست با موفقیت انجام شد (بدون RecId)</div> |
| **2** | <div dir="rtl">اعتبار کافی نیست</div> |
| **3** | <div dir="rtl">محدودیت ارسال روزانه</div> |
| **4** | <div dir="rtl">محدودیت حجم ارسال</div> |
| **5** | <div dir="rtl">شماره فرستنده نامعتبر</div> |
| **6** | <div dir="rtl">سامانه در حال بروزرسانی</div> |
| **7** | <div dir="rtl">متن حاوی کلمات فیلتر شده</div> |
| **14** | <div dir="rtl">شماره گیرنده ای یافت نشد</div> |
| **15** | <div dir="rtl">متن پیامک خالی است</div> |
| **16** | <div dir="rtl">شماره موبایل معتبر نیست</div> |

---

## <div dir="rtl">🔧 عیب‌یابی</div>

### <div dir="rtl">خطا: `Class 'SoapClient' not found`</div>

<div dir="rtl">

**راه حل:** افزونه SOAP را در PHP فعال کنید:

```ini
; در فایل php.ini
extension=soap
```

</div>

### <div dir="rtl">خطا: شماره فرستنده معتبر نیست (کد 5)</div>

<div dir="rtl">

**راه حل:** شماره فرستنده باید یکی از شماره‌های ثبت شده در پنل فراپیامک باشد.

</div>

### <div dir="rtl">خطا: متن حاوی کلمات فیلتر شده (کد 7)</div>

<div dir="rtl">

**راه حل:** متن پیامک خود را بررسی کنید. همچنین به صورت خودکار "لغو11" به انتهای پیام اضافه می‌شود.

</div>

---

## <div dir="rtl">📞 پشتیبانی</div>

<div dir="rtl">

- **ایمیل:** [amir.irdev@gmail.com](mailto:amir.irdev@gmail.com)
- **وبسایت:** [https://amirrajabzadeh.ir](https://amirrajabzadeh.ir)
- **گیت‌هاب:** [https://github.com/amirrajabzadeh/farapayamak-laravel](https://github.com/amirrajabzadeh/farapayamak-laravel)
- **گزارش باگ:** [Issues](https://github.com/amirrajabzadeh/farapayamak-laravel/issues)

</div>

---

## <div dir="rtl">📄 لایسنس</div>

<div dir="rtl">

این پکیج تحت لایسنس **MIT** منتشر شده است.

---

**ساخته شده با ❤️ توسط [امیر رجب زاده](https://amirrajabzadeh.ir)**

</div>

[![GitHub stars](https://img.shields.io/github/stars/amirrajabzadeh/farapayamak-laravel.svg?style=social)](https://github.com/amirrajabzadeh/farapayamak-laravel)
