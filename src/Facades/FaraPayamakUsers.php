<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed getUserCredit($targetUsername = '')
 * @method static mixed getUserDetails($targetUsername = '')
 *
 * @see \Farapayamak\Laravel\Services\UsersService
 */
class FaraPayamakUsers extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'farapayamak.users';
    }
}