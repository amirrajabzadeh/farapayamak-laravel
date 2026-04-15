# <div dir="rtl">فراپیامک لاراول - Farapayamak Laravel</div>

> <div dir="rtl">پکیج رسمی و کامل وب سرویس فراپیامک (ملی پیامک) برای لاراول</div>

[![Latest Version](https://img.shields.io/packagist/v/farapayamak/laravel.svg?style=flat-square)](https://packagist.org/packages/farapayamak/laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/farapayamak/laravel.svg?style=flat-square)](https://packagist.org/packages/farapayamak/laravel)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-8892BF.svg?style=flat-square)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/laravel-8.x%20%7C%209.x%20%7C%2010.x%20%7C%2011.x%20%7C%2012.x-FF2D20.svg?style=flat-square)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

---

<div dir="rtl">

**نویسنده:** امیر رجب زاده  
**ایمیل:** [amir.irdev@gmail.com](mailto:amir.irdev@gmail.com)  
**وبسایت:** [https://amirrajabzadeh.ir](https://amirrajabzadeh.ir)  
**گیت‌هاب:** [farapayamak/laravel](https://github.com/amirrajabzadeh/farapayamak-laravel)

</div>

---

## <div dir="rtl">📋 فهرست مطالب</div>

<div dir="rtl">

- [ویژگی‌ها](#ویژگی‌ها)
- [نیازمندی‌ها](#نیازمندی‌ها)
- [نصب](#نصب)
- [تنظیمات](#تنظیمات)
- [راهنمای استفاده](#راهنمای-استفاده)
  - [ارسال پیامک ساده](#ارسال-پیامک-ساده)
  - [ارسال OTP (کد تأیید)](#ارسال-otp-کد-تأیید)
  - [دریافت اعتبار](#دریافت-اعتبار)
  - [دریافت وضعیت تحویل](#دریافت-وضعیت-تحویل)
  - [دریافت پیامک‌های رسیده](#دریافت-پیامک‌های-رسیده)
  - [مدیریت دفترچه تلفن](#مدیریت-دفترچه-تلفن)
  - [ارسال زماندار](#ارسال-زماندار)
- [سایر سرویس‌ها](#سایر-سرویس‌ها)
- [کدهای خطا](#کدهای-خطا)
- [عیب‌یابی](#عیب‌یابی)
- [لایسنس](#لایسنس)

</div>

---

## <div dir="rtl">✨ ویژگی‌ها</div>

<div dir="rtl">

- ✅ پشتیبانی از تمام **8 وب سرویس** فراپیامک (Send, Receive, Contacts, Schedule, Actions, Voice, Users, Tickets)
- ✅ متد **SendOtp** برای ارسال کد تأیید یکبارمصرف
- ✅ ارسال پیامک **ساده**، **پیشرفته**، **چندتایی** و **زماندار**
- ✅ مدیریت کامل **دفترچه تلفن** و **گروه‌ها**
- ✅ دریافت پیامک‌های **رسیده** از صندوق ورودی
- ✅ دریافت **اعتبار** و **وضعیت تحویل** پیامک
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
composer require farapayamak/laravel
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

### <div dir="rtl">1. ارسال پیامک ساده</div>

```php
<?php

namespace App\Http\Controllers;

use Farapayamak\Laravel\Facades\FaraPayamak;

class SmsController extends Controller
{
    public function sendSms()
    {
        $result = FaraPayamak::sendSimpleSms(
            '09130908908',    // شماره گیرنده
            '5000xxxx',       // شماره فرستنده (ثبت شده در پنل)
            'سلام! این یک پیام آزمایشی است.',
            false             // فلش (true/false)
        );

        if (is_numeric($result) && $result > 0) {
            return "✅ پیامک با موفقیت ارسال شد. کد پیگیری: " . $result;
        }
        
        return "❌ خطا در ارسال: " . json_encode($result);
    }
}
```

### <div dir="rtl">2. ارسال OTP (کد تأیید)</div>

```php
<?php

namespace App\Http\Controllers;

use Farapayamak\Laravel\Facades\FaraPayamak;
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

        if (!is_array($result) || !isset($result['error'])) {
            return response()->json([
                'success' => true,
                'message' => 'کد تأیید با موفقیت ارسال شد ✅'
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
$credit = FaraPayamak::getCredit();

if (is_numeric($credit)) {
    echo "اعتبار شما: " . number_format($credit) . " ریال";
} else {
    echo "خطا در دریافت اعتبار";
}
```

### <div dir="rtl">4. دریافت وضعیت تحویل پیامک</div>

```php
$status = FaraPayamak::getDeliveryStatus($recId);

$statusText = [
    0  => 'ارسال شده به مخابرات',
    1  => 'رسیده به گوشی',
    2  => 'نرسیده به گوشی',
    3  => 'خطای مخابراتی',
    5  => 'خطای نامشخص',
    8  => 'رسیده به مخابرات',
    16 => 'نرسیده به مخابرات',
    35 => 'شماره در لیست سیاه',
    100 => 'نامشخص',
    200 => 'ارسال شده',
    300 => 'فیلتر شده',
    400 => 'در لیست ارسال',
    500 => 'عدم پذیرش',
];

echo $statusText[$status] ?? 'نامشخص';
```

### <div dir="rtl">5. دریافت پیامک‌های رسیده</div>

```php
use Farapayamak\Laravel\Facades\FaraPayamakReceive;

// دریافت 50 پیامک آخر
$messages = FaraPayamakReceive::getInboxMessages(1, 50);

// تعداد پیامک‌های خوانده نشده
$count = FaraPayamakReceive::getInboxCount();
```

### <div dir="rtl">6. مدیریت دفترچه تلفن</div>

```php
use Farapayamak\Laravel\Facades\FaraPayamakContacts;

// دریافت لیست گروه‌ها
$groups = FaraPayamakContacts::getGroups();

// اضافه کردن گروه جدید
$groupId = FaraPayamakContacts::addGroup('مشتریان ویژه', 'گروه تست');

// دریافت مخاطبین یک گروه
$contacts = FaraPayamakContacts::getContacts($groupId);
```

### <div dir="rtl">7. ارسال زماندار</div>

```php
use Farapayamak\Laravel\Facades\FaraPayamakSchedule;

$scheduleId = FaraPayamakSchedule::addSchedule(
    '09130908908',                    // شماره گیرنده
    '5000xxxx',                       // شماره فرستنده
    'پیامک زماندار',                   // متن
    false,                            // فلش
    '2024-12-25 14:30:00',           // تاریخ و زمان ارسال
    0                                 // دوره تکرار (0=بدون تکرار)
);
```

---

## <div dir="rtl">🔧 استفاده از تزریق مستقیم (بدون Facade)</div>

<div dir="rtl">

اگر Facade در IDE شما شناسایی نمی‌شود، می‌توانید از تزریق مستقیم استفاده کنید:

</div>

```php
<?php

namespace App\Http\Controllers;

use Farapayamak\Laravel\Services\SendService;

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

### <div dir="rtl">کدهای برگشتی از متد `sendSimpleSms`:</div>

| کد | <div dir="rtl">توضیحات</div> |
|:---|:---|
| **>0** | <div dir="rtl">موفقیت آمیز (RecId - شناسه پیامک)</div> |
| **2** | <div dir="rtl">اعتبار کافی نیست</div> |
| **3** | <div dir="rtl">محدودیت ارسال روزانه</div> |
| **4** | <div dir="rtl">محدودیت حجم ارسال</div> |
| **5** | <div dir="rtl">شماره فرستنده نامعتبر</div> |
| **6** | <div dir="rtl">سامانه در حال بروزرسانی</div> |
| **7** | <div dir="rtl">متن حاوی کلمات فیلتر شده</div> |
| **10** | <div dir="rtl">نام کاربری یا رمز عبور اشتباه</div> |

---

## <div dir="rtl">🔧 عیب‌یابی</div>

### <div dir="rtl">خطا: `Class 'SoapClient' not found`</div>

<div dir="rtl">

**راه حل:** افزونه SOAP را در PHP فعال کنید:

</div>

```ini
; در فایل php.ini
extension=soap
```

### <div dir="rtl">خطا: `Could not resolve host`</div>

<div dir="rtl">

**راه حل:** اتصال اینترنت خود را بررسی کنید.

</div>

### <div dir="rtl">خطا: `Authentication failed`</div>

<div dir="rtl">

**راه حل:** نام کاربری و رمز عبور خود را در فایل `.env` بررسی کنید.

</div>

### <div dir="rtl">خطا: فضاهای نام (Namespace) در IDE شناسایی نمی‌شود</div>

<div dir="rtl">

**راه حل:** دستورات زیر را اجرا کنید:

</div>

```bash
composer dump-autoload
php artisan optimize:clear
```

<div dir="rtl">

سپس PHPStorm را ریستارت کنید.

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

این پکیج تحت لایسنس **MIT** منتشر شده است. برای اطلاعات بیشتر فایل [LICENSE](LICENSE) را مشاهده کنید.

---

**ساخته شده با ❤️ توسط [امیر رجب زاده](https://amirrajabzadeh.ir)**

</div>

[![GitHub stars](https://img.shields.io/github/stars/amirrajabzadeh/farapayamak-laravel.svg?style=social)](https://github.com/amirrajabzadeh/farapayamak-laravel)
[![GitHub forks](https://img.shields.io/github/forks/amirrajabzadeh/farapayamak-laravel.svg?style=social)](https://github.com/amirrajabzadeh/farapayamak-laravel)