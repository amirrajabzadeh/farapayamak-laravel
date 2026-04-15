<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static int addNumberBulk($from, $title, $messages, $receivers, $dateToSend = '')
 * @method static array getBulkDetails($bulkId)
 *
 * @see \Farapayamak\Laravel\Services\ActionsService
 */
class FaraPayamakActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'farapayamak.actions';
    }
}