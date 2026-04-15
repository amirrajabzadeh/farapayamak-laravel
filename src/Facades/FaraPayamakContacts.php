<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getGroups()
 * @method static int addGroup($groupName, $description = '', $showToChilds = false)
 * @method static array getContacts($groupId = 0, $keyword = '', $from = 0, $count = 100)
 *
 * @see \Farapayamak\Laravel\Services\ContactsService
 */
class FaraPayamakContacts extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'farapayamak.contacts';
    }
}