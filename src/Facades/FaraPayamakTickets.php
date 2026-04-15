<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getTickets($from = 0, $count = 50)
 *
 * @see \Farapayamak\Laravel\Services\TicketsService
 */
class FaraPayamakTickets extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'farapayamak.tickets';
    }
}