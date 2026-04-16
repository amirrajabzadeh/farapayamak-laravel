<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade برای وب سرویس ارسال پیامک فراپیامک (Send.asmx)
 *
 * @method static int|array sendSimpleSms(string $to, string $from, string $text, bool $isFlash = false)
 *         ارسال پیامک ساده به یک گیرنده (طبق مستندات SendSimpleSMS2)
 *
 * @method static int|array sendSimpleSmsToMultiple(array $to, string $from, string $text, bool $isFlash = false)
 *         ارسال پیامک به چند گیرنده (حداکثر 100 شماره - طبق مستندات SendSimpleSMS)
 *
 * @method static string|array sendOtp(string $to, string $from, int $code)
 *         ارسال کد تأیید یکبارمصرف (OTP)
 *
 * @method static int|array sendByBaseNumber(string $text, string $to, int $bodyId)
 *         ارسال پیامک از طریق خط خدماتی
 *
 * @method static float|array getCredit()
 *         دریافت اعتبار باقی‌مانده حساب به ریال
 *
 * @method static int|array getDeliveryStatus(int $recId)
 *         دریافت وضعیت تحویل پیامک
 *
 * @method static array getMultipleDeliveryStatus(array $recIds)
 *         دریافت وضعیت تحویل چندین پیامک
 *
 * @method static float|array getSmsPrice(int $irancellCount, int $mtnCount, string $from, string $text)
 *         دریافت قیمت پیامک قبل از ارسال
 *
 * @method static bool|array isAuthenticated()
 *         بررسی اعتبار نام کاربری و رمز عبور
 *
 * @method static float|array getBasePrice()
 *         دریافت قیمت پایه
 *
 * @method static array getUserDetails()
 *         دریافت جزئیات کاربر
 *
 * @see \Amirrajabzadeh\FarapayamakLaravel\Services\SendService
 */
class FaraPayamakSend extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'farapayamak.send';
    }
}