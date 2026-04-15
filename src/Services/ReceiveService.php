<?php

namespace Farapayamak\Laravel\Services;

use SoapFault;

/**
 * وب سرویس دریافت پیامک فراپیامک (Receive.asmx)
 *
 * این کلاس شامل متدهای مربوط به دریافت پیامک‌های رسیده،
 * مدیریت صندوق ورودی و خروجی می‌باشد
 *
 * @package Farapayamak\Laravel\Services
 */
class ReceiveService extends BaseService
{
    /**
     * موقعیت پیامک‌ها
     * 1: دریافتی (صندوق ورودی)
     * 2: ارسالی (صندوق خروجی)
     */
    const LOCATION_INBOX = 1;
    const LOCATION_OUTBOX = 2;

    /**
     * دریافت لیست پیامک‌ها
     *
     * @param int $location موقعیت (1: دریافتی، 2: ارسالی)
     * @param string $from شماره فرستنده/گیرنده برای فیلتر
     * @param int $index شماره شروع برای صفحه‌بندی
     * @param int $count تعداد خروجی
     * @return array
     */
    public function getMessages($location, $from, $index, $count)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'location' => $location,
                'from'     => $from,
                'index'    => $index,
                'count'    => $count
            ];

            $result = $this->client->GetMessages($params);
            return $result->GetMessagesResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت پیامک‌های دریافتی (صندوق ورودی)
     *
     * @param int $page شماره صفحه
     * @param int $perPage تعداد در هر صفحه
     * @return array
     */
    public function getInboxMessages($page = 1, $perPage = 50)
    {
        $index = ($page - 1) * $perPage;
        return $this->getMessages(self::LOCATION_INBOX, '', $index, $perPage);
    }

    /**
     * دریافت پیامک‌های ارسالی (صندوق خروجی)
     *
     * @param int $page شماره صفحه
     * @param int $perPage تعداد در هر صفحه
     * @return array
     */
    public function getOutboxMessages($page = 1, $perPage = 50)
    {
        $index = ($page - 1) * $perPage;
        return $this->getMessages(self::LOCATION_OUTBOX, '', $index, $perPage);
    }

    /**
     * دریافت تعداد پیامک‌های صندوق ورودی
     *
     * @return int
     */
    public function getInboxCount()
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password
            ];

            $result = $this->client->GetInboxCount($params);
            return $result->GetInboxCountResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت تعداد پیامک‌های صندوق خروجی
     *
     * @return int
     */
    public function getOutboxCount()
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password
            ];

            $result = $this->client->GetOutBoxCount($params);
            return $result->GetOutBoxCountResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت آخرین پیامک رسیده
     *
     * @param string $sender شماره فرستنده (اختیاری)
     * @param string $receiver شماره گیرنده (اختیاری)
     * @return array
     */
    public function getLatestReceiveMessage($sender = '', $receiver = '')
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'sender'   => $sender,
                'receiver' => $receiver
            ];

            $result = $this->client->GetLatestReceiveMsg($params);
            return $result->GetLatestReceiveMsgResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت پیامک‌های بعد از یک ID مشخص
     *
     * @param int $location موقعیت
     * @param string $from شماره فرستنده/گیرنده
     * @param int $count تعداد
     * @param int $msgId شناسه پیامک
     * @return array
     */
    public function getMessagesAfterId($location, $from, $count, $msgId)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'location' => $location,
                'from'     => $from,
                'count'    => $count,
                'msgId'    => $msgId
            ];

            $result = $this->client->GetMessagesAfterID($params);
            return $result->GetMessagesAfterIDResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت پیامک‌ها با فیلتر تاریخ
     *
     * @param int $location موقعیت
     * @param string $from شماره فرستنده/گیرنده
     * @param int $index شماره شروع
     * @param int $count تعداد
     * @param string $dateFrom تاریخ از
     * @param string $dateTo تاریخ تا
     * @param bool $isRead خوانده شده/نشده
     * @return array
     */
    public function getMessagesFilterByDate($location, $from, $index, $count, $dateFrom, $dateTo, $isRead = false)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'location' => $location,
                'from'     => $from,
                'index'    => $index,
                'count'    => $count,
                'dateFrom' => $dateFrom,
                'dateTo'   => $dateTo,
                'isRead'   => $isRead
            ];

            $result = $this->client->GetMessagesFilterByDate($params);
            return $result->GetMessagesFilterByDateResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * تغییر وضعیت خوانده شده پیامک
     *
     * @param array $msgIds آرایه شناسه پیامک‌ها
     * @return bool
     */
    public function changeMessageIsRead($msgIds)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'msgIds'   => $msgIds
            ];

            $result = $this->client->ChangeMessageIsRead($params);
            return $result->ChangeMessageIsReadResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * حذف پیامک‌ها
     *
     * @param int $location موقعیت
     * @param array $msgIds آرایه شناسه پیامک‌ها
     * @return bool
     */
    public function removeMessages($location, $msgIds)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'location' => $location,
                'msgIds'   => $msgIds
            ];

            $result = $this->client->RemoveMessages($params);
            return $result->RemoveMessagesResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }
}