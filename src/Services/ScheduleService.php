<?php

namespace Farapayamak\Laravel\Services;

use SoapFault;

/**
 * وب سرویس زمانبندی ارسال فراپیامک (Schedule.asmx)
 *
 * این کلاس شامل متدهای مربوط به ارسال زماندار و اعتباری پیامک‌ها می‌باشد
 *
 * @package Farapayamak\Laravel\Services
 */
class ScheduleService extends BaseService
{
    /**
     * نوع دوره تکرار
     */
    const PERIOD_TYPE_DAILY = 1;      // روزانه
    const PERIOD_TYPE_WEEKLY = 2;     // هفتگی
    const PERIOD_TYPE_MONTHLY = 3;    // ماهانه
    const PERIOD_TYPE_YEARLY = 4;     // سالانه

    /**
     * اضافه کردن ارسال زماندار
     *
     * @param string|array $to شماره گیرنده
     * @param string $from شماره فرستنده
     * @param string $text متن پیامک
     * @param bool $isFlash ارسال فلش
     * @param string $scheduleDateTime تاریخ و زمان ارسال (Y-m-d H:i:s)
     * @param int $period دوره تکرار (0 برای بدون تکرار)
     * @return int شناسه زمانبندی
     */
    public function addSchedule($to, $from, $text, $isFlash = false, $scheduleDateTime, $period = 0)
    {
        try {
            $toArray = is_array($to) ? $to : [$to];

            $params = [
                'username'         => $this->username,
                'password'         => $this->password,
                'to'               => $toArray,
                'from'             => $from,
                'text'             => $text,
                'isflash'          => $isFlash,
                'scheduledatetime' => $scheduleDateTime,
                'period'           => $period
            ];

            $result = $this->client->AddSchedule($params);
            return $result->AddScheduleResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * اضافه کردن ارسال زماندار چندتایی
     *
     * @param array $to آرایه شماره گیرندگان
     * @param string $from شماره فرستنده
     * @param string $text متن پیامک
     * @param bool $isFlash ارسال فلش
     * @param string $scheduleDateTime تاریخ و زمان ارسال
     * @param int $period دوره تکرار
     * @return int
     */
    public function addMultipleSchedule($to, $from, $text, $isFlash = false, $scheduleDateTime, $period = 0)
    {
        try {
            $params = [
                'username'         => $this->username,
                'password'         => $this->password,
                'to'               => $to,
                'from'             => $from,
                'text'             => $text,
                'isflash'          => $isFlash,
                'scheduledatetime' => $scheduleDateTime,
                'period'           => $period
            ];

            $result = $this->client->AddNewMultipleSchedule($params);
            return $result->AddNewMultipleScheduleResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * اضافه کردن ارسال اعتباری (Usance)
     *
     * @param string|array $to شماره گیرنده
     * @param string $from شماره فرستنده
     * @param string $text متن پیامک
     * @param bool $isFlash ارسال فلش
     * @param string $scheduleStartDateTime تاریخ شروع
     * @param int $countRepeat تعداد تکرار
     * @param string $scheduleEndDateTime تاریخ پایان
     * @param int $periodType نوع دوره تکرار
     * @return int
     */
    public function addUsance(
        $to,
        $from,
        $text,
        $isFlash = false,
        $scheduleStartDateTime,
        $countRepeat,
        $scheduleEndDateTime,
        $periodType
    ) {
        try {
            $toArray = is_array($to) ? $to : [$to];

            $params = [
                'username'              => $this->username,
                'password'              => $this->password,
                'to'                    => $toArray,
                'from'                  => $from,
                'text'                  => $text,
                'isflash'               => $isFlash,
                'schedulestartdatetime' => $scheduleStartDateTime,
                'countrepeat'           => $countRepeat,
                'scheduleenddatetime'   => $scheduleEndDateTime,
                'periodtype'            => $periodType
            ];

            $result = $this->client->AddNewUsance($params);
            return $result->AddNewUsanceResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت جزئیات زمانبندی
     *
     * @param int $scheduleId شناسه زمانبندی
     * @return array
     */
    public function getScheduleDetails($scheduleId)
    {
        try {
            $params = [
                'username'   => $this->username,
                'password'   => $this->password,
                'scheduleId' => $scheduleId
            ];

            $result = $this->client->GetScheduleDetails($params);
            return $result->GetScheduleDetailsResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت وضعیت زمانبندی
     *
     * @param int $scheduleId شناسه زمانبندی
     * @return int
     */
    public function getScheduleStatus($scheduleId)
    {
        try {
            $params = [
                'username'   => $this->username,
                'password'   => $this->password,
                'scheduleId' => $scheduleId
            ];

            $result = $this->client->GetScheduleStatus($params);
            return $result->GetScheduleStatusResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * حذف زمانبندی
     *
     * @param int $scheduleId شناسه زمانبندی
     * @return bool
     */
    public function removeSchedule($scheduleId)
    {
        try {
            $params = [
                'username'   => $this->username,
                'password'   => $this->password,
                'scheduleId' => $scheduleId
            ];

            $result = $this->client->RemoveSchedule($params);
            return $result->RemoveScheduleResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }
}