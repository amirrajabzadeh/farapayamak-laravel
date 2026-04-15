<?php

namespace Farapayamak\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed sendBulkSpeechText($title, $body, $receivers, $dateToSend = '', $repeatCount = 1)
 *
 * @see \Farapayamak\Laravel\Services\VoiceService
 */
class FaraPayamakVoice extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'farapayamak.voice';
    }
}