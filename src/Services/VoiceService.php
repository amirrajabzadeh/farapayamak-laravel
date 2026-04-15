<?php

namespace Farapayamak\Laravel\Services;

use SoapFault;

class VoiceService extends BaseService
{
    public function sendBulkSpeechText($title, $body, $receivers, $dateToSend = '', $repeatCount = 1)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'title' => $title,
                'body' => $body,
                'receivers' => $receivers,
                'dateToSend' => $dateToSend,
                'repeatCount' => $repeatCount
            ];
            $result = $this->client->SendBulkSpeechText($params);
            return $result->SendBulkSpeechTextResult;
        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }
}