<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed sendSimpleSms($to, $from, $text, $isFlash = false)
 * @method static mixed sendOtp($to, $from, $code)
 * @method static mixed sendByBaseNumber($text, $to, $bodyId)
 * @method static mixed getCredit()
 * @method static mixed getDeliveryStatus($recId)
 * @method static mixed getUserNumbers()
 *
 * @mixin \Farapayamak\Laravel\Services\SendService
 *
 * @see \Farapayamak\Laravel\Services\SendService
 */
class FaraPayamak extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'farapayamak.send';
    }
}