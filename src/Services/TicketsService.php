<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Services;

use SoapFault;

class TicketsService extends BaseService
{
    public function getTickets($from = 0, $count = 50)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'from' => $from,
                'count' => $count
            ];
            $result = $this->client->GetTickets($params);
            return $result->GetTicketsResult;
        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }
}