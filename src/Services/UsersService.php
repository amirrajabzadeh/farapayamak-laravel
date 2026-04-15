<?php

namespace Farapayamak\Laravel\Services;

use SoapFault;

class UsersService extends BaseService
{
    public function getUserCredit($targetUsername = '')
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'targetUsername' => $targetUsername ?: $this->username
            ];
            $result = $this->client->GetUserCredit($params);
            return $result->GetUserCreditResult;
        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }
}