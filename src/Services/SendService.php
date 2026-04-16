<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Services;

use SoapFault;

/**
 * وب سرویس ارسال پیامک فراپیامک (Send.asmx)
 *
 * این کلاس شامل تمام متدهای مربوط به ارسال پیامک، OTP،
 * دریافت اعتبار، وضعیت تحویل و ... می‌باشد
 *
 * @package Farapayamak\Laravel\Services
 */
class SendService extends BaseService
{
    /**
     * ارسال پیامک ساده (نسخه 2 - پیشنهادی)
     *
     * @param string|array $to شماره گیرنده (رشته یا آرایه)
     * @param string $from شماره فرستنده (ثبت شده در پنل)
     * @param string $text متن پیامک
     * @param bool $isFlash ارسال فلش (true/false)
     * @return int|array در موفقیت: RecId، در خطا: آرایه خطا
     *
     * کدهای برگشتی:
     * >0 : موفقیت آمیز (RecId)
     * 2  : اعتبار کافی نیست
     * 3  : محدودیت ارسال روزانه
     * 4  : محدودیت حجم ارسال
     * 5  : شماره فرستنده نامعتبر
     * 7  : متن حاوی کلمات فیلتر شده
     * 10 : نام کاربری یا رمز عبور اشتباه
     */
    public function sendSimpleSms($to, $from, $text, $isFlash = false)
    {
        try {
            $toArray = is_array($to) ? array_map('strval', $to) : [(string)$to];

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'to'       => $toArray,
                'from'     => (string)$from,
                'text'     => (string)$text,
                'isflash'  => (bool)$isFlash
            ];

            $result = $this->client->SendSimpleSMS2($params);
            return $result->SendSimpleSMS2Result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ارسال پیامک ساده (نسخه 1)
     *
     * @param string|array $to شماره گیرنده
     * @param string $from شماره فرستنده
     * @param string $text متن پیامک
     * @param bool $isFlash ارسال فلش
     * @return int|array
     */
    public function sendSimpleSmsV1($to, $from, $text, $isFlash = false)
    {
        try {
            if (is_array($to)) {
                $toArray = array_map('strval', $to);
            } else {
                $toArray = [(string)$to];
            }

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'to'       => $toArray,
                'from'     => $from,
                'text'     => $text,
                'isflash'  => $isFlash
            ];

            $result = $this->client->SendSimpleSMS($params);
            return $result->SendSimpleSMSResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ارسال پیامک پیشرفته با قابلیت UDH (برای پیامک‌های بلند)
     *
     * @param array $to آرایه شماره گیرندگان
     * @param string $from شماره فرستنده
     * @param string $text متن پیامک
     * @param bool $isFlash ارسال فلش
     * @param string $udh هدر کاربر داده (User Data Header)
     * @param array $recId آرایه خالی برای دریافت شناسه پیامک‌ها
     * @param array $status آرایه خالی برای دریافت وضعیت‌ها
     * @return int|array
     */
    public function sendAdvancedSms($to, $from, $text, $isFlash = false, $udh = '', &$recId = [], &$status = [])
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'to'       => $to,
                'from'     => $from,
                'text'     => $text,
                'isflash'  => $isFlash,
                'udh'      => $udh,
                'recId'    => $recId,
                'status'   => $status
            ];

            $result = $this->client->SendSms($params);
            return $result->SendSmsResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ارسال پیامک پیشرفته نسخه 2
     *
     * @param array $to آرایه شماره گیرندگان
     * @param string $from شماره فرستنده
     * @param string $text متن پیامک
     * @param bool $isFlash ارسال فلش
     * @param string $udh هدر کاربر داده
     * @param array $recId آرایه خالی برای دریافت شناسه پیامک‌ها
     * @param array $status آرایه خالی برای دریافت وضعیت‌ها
     * @param int $filterId شناسه فیلتر
     * @return int|array
     */
    public function sendAdvancedSms2($to, $from, $text, $isFlash = false, $udh = '', &$recId = [], &$status = [], $filterId = 0)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'to'       => $to,
                'from'     => $from,
                'text'     => $text,
                'isflash'  => $isFlash,
                'udh'      => $udh,
                'recId'    => $recId,
                'status'   => $status,
                'filterId' => $filterId
            ];

            $result = $this->client->SendSms2($params);
            return $result->SendSms2Result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ارسال پیامک چندتایی (به چند شماره با متن یکسان)
     *
     * @param array $to آرایه شماره گیرندگان
     * @param string $from شماره فرستنده
     * @param string $text متن پیامک
     * @param bool $isFlash ارسال فلش
     * @param string $udh هدر کاربر داده
     * @param array $recId آرایه خالی برای دریافت شناسه پیامک‌ها
     * @return int|array
     */
    public function sendMultipleSms($to, $from, $text, $isFlash = false, $udh = '', &$recId = [])
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'to'       => $to,
                'from'     => $from,
                'text'     => $text,
                'isflash'  => $isFlash,
                'udh'      => $udh,
                'recId'    => $recId
            ];

            $result = $this->client->SendMultipleSMS($params);
            return $result->SendMultipleSMSResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ارسال پیامک چندتایی نسخه 2
     *
     * @param array $to آرایه شماره گیرندگان
     * @param string $from شماره فرستنده
     * @param string $text متن پیامک
     * @param bool $isFlash ارسال فلش
     * @param string $udh هدر کاربر داده
     * @param array $recId آرایه خالی برای دریافت شناسه پیامک‌ها
     * @param array $status آرایه خالی برای دریافت وضعیت‌ها
     * @return int|array
     */
    public function sendMultipleSms2($to, $from, $text, $isFlash = false, $udh = '', &$recId = [], &$status = [])
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'to'       => $to,
                'from'     => $from,
                'text'     => $text,
                'isflash'  => $isFlash,
                'udh'      => $udh,
                'recId'    => $recId,
                'status'   => $status
            ];

            $result = $this->client->SendMultipleSMS2($params);
            return $result->SendMultipleSMS2Result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ⭐ ارسال OTP (کد تأیید یکبارمصرف) - متد صحیح بر اساس مستندات
     *
     * @param string $to شماره گیرنده
     * @param string $from شماره فرستنده (اختیاری - می تواند خالی باشد)
     * @param int $code کد تأیید عددی (مثلاً 123456)
     * @return string|array نتیجه ارسال
     *
     * مثال:
     * $result = $sendService->sendOtp('09123456789', '5000xxx', 123456);
     */
    public function sendOtp($to, $from, $code)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'to'       => $to,
                'from'     => $from,
                'code'     => (int)$code  // حتماً به integer تبدیل کن
            ];

            $result = $this->client->SendOtp($params);
            return $result->SendOtpResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ارسال پیامک از طریق خط خدماتی (Base Number)
     *
     * @param string $text متن پیامک
     * @param string $to شماره گیرنده
     * @param int $bodyId شناسه بدنه پیامک در پنل
     * @return int|array
     */
    public function sendByBaseNumber($text, $to, $bodyId)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'text'     => $text,
                'to'       => $to,
                'bodyId'   => $bodyId
            ];

            $result = $this->client->SendByBaseNumber($params);
            return $result->SendByBaseNumberResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ارسال پیامک از طریق خط خدماتی نسخه 2
     *
     * @param string $text متن پیامک
     * @param string $to شماره گیرنده
     * @param int $bodyId شناسه بدنه پیامک
     * @return int|array
     */
    public function sendByBaseNumber2($text, $to, $bodyId)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'text'     => $text,
                'to'       => $to,
                'bodyId'   => $bodyId
            ];

            $result = $this->client->SendByBaseNumber2($params);
            return $result->SendByBaseNumber2Result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ارسال پیامک از طریق خط خدماتی نسخه 3
     *
     * @param string $text متن پیامک
     * @param string $to شماره گیرنده
     * @return int|array
     */
    public function sendByBaseNumber3($text, $to)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'text'     => $text,
                'to'       => $to
            ];

            $result = $this->client->SendByBaseNumber3($params);
            return $result->SendByBaseNumber3Result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت اعتبار باقی‌مانده حساب
     *
     * @return float|array مبلغ اعتبار به ریال
     */
    public function getCredit()
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password
            ];

            $result = $this->client->GetCredit($params);
            return $result->GetCreditResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت وضعیت تحویل یک پیامک
     *
     * @param int $recId شناسه پیامک
     * @return int|array کد وضعیت تحویل
     *
     * کدهای وضعیت تحویل:
     * 0 : ارسال شده به مخابرات
     * 1 : رسیده به گوشی
     * 2 : نرسیده به گوشی
     * 3 : خطای مخابراتی
     * 5 : خطای نامشخص
     * 8 : رسیده به مخابرات
     * 16 : نرسیده به مخابرات
     * 35 : شماره در لیست سیاه
     */
    public function getDeliveryStatus($recId)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'recId'    => $recId
            ];

            $result = $this->client->GetDelivery($params);
            return $result->GetDeliveryResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت وضعیت تحویل چندین پیامک (نسخه 2)
     *
     * @param int $recId شناسه پیامک
     * @return array|array
     */
    public function getDeliveryStatus2($recId)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'recId'    => $recId
            ];

            $result = $this->client->GetDeliveries2($params);
            return $result->GetDeliveries2Result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت وضعیت تحویل چندین پیامک (نسخه 3)
     *
     * @param array $recIds آرایه شناسه پیامک‌ها
     * @return array|array
     */
    public function getMultipleDeliveryStatus($recIds)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'recIds'   => $recIds
            ];

            $result = $this->client->GetDeliveries($params);
            return $result->GetDeliveriesResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت قیمت هر پیامک (تعرفه پایه)
     *
     * @param int $irancellCount تعداد پیامک ایرانسل
     * @param int $mtnCount تعداد پیامک همراه اول
     * @param string $from شماره فرستنده
     * @param string $text متن پیامک
     * @return float|array
     */
    public function getSmsPrice($irancellCount, $mtnCount, $from, $text)
    {
        try {
            $params = [
                'username'      => $this->username,
                'password'      => $this->password,
                'irancellCount' => $irancellCount,
                'mtnCount'      => $mtnCount,
                'from'          => $from,
                'text'          => $text
            ];

            $result = $this->client->GetSmsPrice($params);
            return $result->GetSmsPriceResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت لیست شماره خطوط (فرستنده‌ها)
     *
     * @return array لیست شماره‌ها
     */
    public function getUserNumbers()
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password
            ];

            $result = $this->client->GetUserNumbers($params);
            return $result->GetUserNumbersResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * بررسی اعتبار نام کاربری و رمز عبور
     *
     * @return bool|array
     */
    public function isAuthenticated()
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password
            ];

            $result = $this->client->IsAuthenticated($params);
            return $result->IsAuthenticatedResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت قیمت پایه
     *
     * @return float|array
     */
    public function getBasePrice()
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password
            ];

            $result = $this->client->GetBasePrice($params);
            return $result->GetBasePriceResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت جزئیات کاربر
     *
     * @return array
     */
    public function getUserDetails()
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password
            ];

            $result = $this->client->GetUserDetails($params);
            return $result->GetUserDetailsResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }
}