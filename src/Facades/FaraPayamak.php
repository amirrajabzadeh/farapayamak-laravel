<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade اصلی برای وب سرویس ارسال پیامک فراپیامک
 *
 * @method static int|array sendSimpleSms(string $to, string $from, string $text, bool $isFlash = false)
 * @method static int|array sendSimpleSmsToMultiple(array $to, string $from, string $text, bool $isFlash = false)
 * @method static string|array sendOtp(string $to, string $from, int $code)
 * @method static int|array sendByBaseNumber(string $text, string $to, int $bodyId)
 * @method static float|array getCredit()
 * @method static int|array getDeliveryStatus(int $recId)
 * @method static array getMultipleDeliveryStatus(array $recIds)
 * @method static float|array getSmsPrice(int $irancellCount, int $mtnCount, string $from, string $text)
 * @method static bool|array isAuthenticated()
 * @method static float|array getBasePrice()
 * @method static array getUserDetails()
 *
 * @see \Amirrajabzadeh\FarapayamakLaravel\Services\SendService
 */
class FaraPayamak extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'farapayamak.send';
    }
}