<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Services;

use SoapFault;

/**
 * وب سرویس اقدامات انبوه فراپیامک (Actions.asmx)
 *
 * این کلاس شامل متدهای مربوط به ارسال انبوه پیامک (Bulk) می‌باشد
 *
 * @package Farapayamak\Laravel\Services
 */
class ActionsService extends BaseService
{
    /**
     * اضافه کردن ارسال انبوه (Bulk)
     *
     * @param string $from شماره فرستنده
     * @param string $title عنوان
     * @param array $messages آرایه متن پیامک‌ها
     * @param array $receivers آرایه شماره گیرندگان
     * @param string $dateToSend تاریخ ارسال (خالی برای ارسال فوری)
     * @return int شناسه Bulk
     */
    public function addNumberBulk($from, $title, $messages, $receivers, $dateToSend = '')
    {
        try {
            $params = [
                'username'    => $this->username,
                'password'    => $this->password,
                'from'        => $from,
                'title'       => $title,
                'messages'    => $messages,
                'receivers'   => $receivers,
                'dateToSend'  => $dateToSend
            ];

            $result = $this->client->AddNumberBulk($params);
            return $result->AddNumberBulkResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت جزئیات Bulk
     *
     * @param int $bulkId شناسه Bulk
     * @return array
     */
    public function getBulkDetails($bulkId)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'bulkId'   => $bulkId
            ];

            $result = $this->client->GetBulkDetails($params);
            return $result->GetBulkDetailsResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت وضعیت تحویل Bulk
     *
     * @param array $recIds آرایه شناسه پیامک‌ها
     * @return array
     */
    public function getBulkDeliveries($recIds)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'recIds'   => $recIds
            ];

            $result = $this->client->GetBulkDeliveries($params);
            return $result->GetBulkDeliveriesResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت وضعیت تحویل Bulk نسخه 2
     *
     * @param int $recId شناسه پیامک
     * @return array
     */
    public function getBulkDeliveries2($recId)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'recId'    => $recId
            ];

            $result = $this->client->GetBulkDeliveries2($params);
            return $result->GetBulkDeliveries2Result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }
}