<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getMessages($location, $from, $index, $count)
 * @method static array getInboxMessages($page = 1, $perPage = 50)
 * @method static array getOutboxMessages($page = 1, $perPage = 50)
 * @method static int getInboxCount()
 * @method static int getOutboxCount()
 *
 * @see \Farapayamak\Laravel\Services\ReceiveService
 */
class FaraPayamakReceive extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'farapayamak.receive';
    }
}