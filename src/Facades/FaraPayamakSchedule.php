<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static int addSchedule($to, $from, $text, $isFlash = false, $scheduleDateTime, $period = 0)
 * @method static array getScheduleDetails($scheduleId)
 * @method static int getScheduleStatus($scheduleId)
 * @method static bool removeSchedule($scheduleId)
 *
 * @see \Farapayamak\Laravel\Services\ScheduleService
 */
class FaraPayamakSchedule extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'farapayamak.schedule';
    }
}