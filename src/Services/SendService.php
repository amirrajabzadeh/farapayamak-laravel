<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Services;

use SoapFault;

/**
 * وب سرویس ارسال پیامک فراپیامک (Send.asmx)
 *
 * این کلاس شامل تمام متدهای مربوط به ارسال پیامک، OTP،
 * دریافت اعتبار، وضعیت تحویل و ... می‌باشد
 *
 * @package Amirrajabzadeh\FarapayamakLaravel\Services
 */
class SendService extends BaseService
{
    /**
     * ارسال پیامک ساده به یک گیرنده (نسخه 2 - پیشنهادی)
     *
     * طبق مستندات رسمی، در متد SendSimpleSMS2 پارامتر to از نوع String است
     *
     * @param string $to شماره گیرنده (فقط یک شماره)
     * @param string $from شماره فرستنده (ثبت شده در پنل)
     * @param string $text متن پیامک
     * @param bool $isFlash ارسال فلش (true/false)
     * @return int|array در موفقیت: RecId، در خطا: کد خطا یا آرایه خطا
     *
     * کدهای برگشتی:
     * >0 : موفقیت آمیز (RecId)
     * 0  : نام کاربری یا رمز عبور اشتباه
     * 1  : درخواست با موفقیت انجام شد
     * 2  : اعتبار کافی نیست
     * 3  : محدودیت ارسال روزانه
     * 4  : محدودیت حجم ارسال
     * 5  : شماره فرستنده نامعتبر
     * 6  : سامانه در حال بروزرسانی
     * 7  : متن حاوی کلمات فیلتر شده
     * 8  : ارسال از خطوط عمومی امکان پذیر نیست
     * 9  : کاربر مورد نظر فعال نیست
     * 10 : ارسال نشده
     * 11 : مدارک کاربر کامل نیست
     * 12 : متن حاوی لینک است
     * 14 : شماره گیرنده ای یافت نشد
     * 15 : متن پیامک خالی است
     * 16 : شماره موبایل معتبر نیست
     */
    public function sendSimpleSms($to, $from, $text, $isFlash = false)
    {
        try {
            // طبق مستندات: to باید از نوع String باشد (نه آرایه)
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'to'       => (string)$to,
                'from'     => (string)$from,
                'text'     => (string)$text,
                'isflash'  => (bool)$isFlash
            ];

            $result = $this->client->SendSimpleSMS2($params);

            if (property_exists($result, 'SendSimpleSMS2Result')) {
                return $result->SendSimpleSMS2Result;
            }

            return $result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ارسال پیامک به چند گیرنده (نسخه 1)
     *
     * طبق مستندات رسمی، در متد SendSimpleSMS پارامتر to از نوع آرایه است
     * حداکثر 100 شماره در هر بار فراخوانی
     *
     * @param array $to آرایه شماره گیرندگان (حداکثر 100 عدد)
     * @param string $from شماره فرستنده (ثبت شده در پنل)
     * @param string $text متن پیامک
     * @param bool $isFlash ارسال فلش (true/false)
     * @return int|array در موفقیت: RecId، در خطا: کد خطا یا آرایه خطا
     */
    public function sendSimpleSmsToMultiple($to, $from, $text, $isFlash = false)
    {
        try {
            // تبدیل به آرایه از رشته‌ها
            $toArray = is_array($to) ? array_map('strval', $to) : [(string)$to];

            // بررسی حداکثر 100 شماره
            if (count($toArray) > 100) {
                return [
                    'error' => true,
                    'message' => 'حداکثر 100 شماره می‌تواند در هر بار فراخوانی ارسال شود',
                    'code' => 4
                ];
            }

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'to'       => $toArray,
                'from'     => (string)$from,
                'text'     => (string)$text,
                'isflash'  => (bool)$isFlash
            ];

            $result = $this->client->SendSimpleSMS($params);

            if (property_exists($result, 'SendSimpleSMSResult')) {
                return $result->SendSimpleSMSResult;
            }

            return $result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ارسال پیامک ساده (نسخه 1 - نگهداری برای سازگاری با نسخه‌های قدیمی)
     *
     * @param string|array $to شماره گیرنده (رشته یا آرایه)
     * @param string $from شماره فرستنده
     * @param string $text متن پیامک
     * @param bool $isFlash ارسال فلش
     * @return int|array
     * @deprecated از sendSimpleSms برای یک گیرنده و sendSimpleSmsToMultiple برای چند گیرنده استفاده کنید
     */
    public function sendSimpleSmsV1($to, $from, $text, $isFlash = false)
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

            $result = $this->client->SendSimpleSMS($params);

            if (property_exists($result, 'SendSimpleSMSResult')) {
                return $result->SendSimpleSMSResult;
            }

            return $result;

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
                'from'     => (string)$from,
                'text'     => (string)$text,
                'isflash'  => (bool)$isFlash,
                'udh'      => (string)$udh,
                'recId'    => $recId,
                'status'   => $status
            ];

            $result = $this->client->SendSms($params);

            if (property_exists($result, 'SendSmsResult')) {
                return $result->SendSmsResult;
            }

            return $result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ارسال پیامک پیشرفته نسخه 2 (با قابلیت فیلتر)
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
                'from'     => (string)$from,
                'text'     => (string)$text,
                'isflash'  => (bool)$isFlash,
                'udh'      => (string)$udh,
                'recId'    => $recId,
                'status'   => $status,
                'filterId' => (int)$filterId
            ];

            $result = $this->client->SendSms2($params);

            if (property_exists($result, 'SendSms2Result')) {
                return $result->SendSms2Result;
            }

            return $result;

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
                'from'     => (string)$from,
                'text'     => $text,
                'isflash'  => (bool)$isFlash,
                'udh'      => (string)$udh,
                'recId'    => $recId
            ];

            $result = $this->client->SendMultipleSMS($params);

            if (property_exists($result, 'SendMultipleSMSResult')) {
                return $result->SendMultipleSMSResult;
            }

            return $result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ارسال پیامک چندتایی نسخه 2 (ارسال با متن متفاوت به هر گیرنده)
     *
     * @param array $to آرایه شماره گیرندگان
     * @param array $from آرایه شماره فرستنده‌ها
     * @param array $text آرایه متن پیامک‌ها
     * @param bool $isFlash ارسال فلش
     * @param string $udh هدر کاربر داده
     * @param array $recId آرایه خالی برای دریافت شناسه پیامک‌ها
     * @param array $status آرایه خالی برای دریافت وضعیت‌ها
     * @return array|array
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
                'isflash'  => (bool)$isFlash,
                'udh'      => (string)$udh,
                'recId'    => $recId,
                'status'   => $status
            ];

            $result = $this->client->SendMultipleSMS2($params);

            if (property_exists($result, 'SendMultipleSMS2Result')) {
                return $result->SendMultipleSMS2Result;
            }

            return $result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ⭐ ارسال OTP (کد تأیید یکبارمصرف) - طبق مستندات رسمی
     *
     * @param string $to شماره گیرنده
     * @param string $from شماره فرستنده (اختیاری - می تواند خالی باشد)
     * @param int $code کد تأیید عددی (مثلاً 123456)
     * @return string|array نتیجه ارسال (RecId در صورت موفقیت)
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
                'to'       => (string)$to,
                'from'     => (string)$from,
                'code'     => (int)$code
            ];

            $result = $this->client->SendOtp($params);

            if (property_exists($result, 'SendOtpResult')) {
                return $result->SendOtpResult;
            }

            return $result;

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
                'text'     => (string)$text,
                'to'       => (string)$to,
                'bodyId'   => (int)$bodyId
            ];

            $result = $this->client->SendByBaseNumber($params);

            if (property_exists($result, 'SendByBaseNumberResult')) {
                return $result->SendByBaseNumberResult;
            }

            return $result;

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
                'text'     => (string)$text,
                'to'       => (string)$to,
                'bodyId'   => (int)$bodyId
            ];

            $result = $this->client->SendByBaseNumber2($params);

            if (property_exists($result, 'SendByBaseNumber2Result')) {
                return $result->SendByBaseNumber2Result;
            }

            return $result;

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
                'text'     => (string)$text,
                'to'       => (string)$to
            ];

            $result = $this->client->SendByBaseNumber3($params);

            if (property_exists($result, 'SendByBaseNumber3Result')) {
                return $result->SendByBaseNumber3Result;
            }

            return $result;

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

            if (property_exists($result, 'GetCreditResult')) {
                return (float)$result->GetCreditResult;
            }

            return $result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت وضعیت تحویل یک پیامک (از اپراتور)
     *
     * @param int $recId شناسه پیامک
     * @return int|array کد وضعیت تحویل
     *
     * کدهای وضعیت تحویل طبق مستندات:
     * 0 : ارسال شده به مخابرات
     * 1 : رسیده به گوشی
     * 2 : نرسیده به گوشی
     * 3 : خطای مخابراتی
     * 5 : خطای نامشخص
     * 8 : رسیده به مخابرات
     * 16 : نرسیده به مخابرات
     * 35 : شماره در لیست سیاه
     * 100 : نامشخص
     * 200 : ارسال شده
     * 300 : فیلتر شده
     * 400 : در لیست ارسال
     * 500 : عدم پذیرش
     */
    public function getDeliveryStatus($recId)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'recId'    => (int)$recId
            ];

            $result = $this->client->GetDelivery($params);

            if (property_exists($result, 'GetDeliveryResult')) {
                return (int)$result->GetDeliveryResult;
            }

            return $result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت وضعیت تحویل پیامک (نسخه 2 - وضعیت پنل)
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
                'recId'    => (int)$recId
            ];

            $result = $this->client->GetDeliveries2($params);

            if (property_exists($result, 'GetDeliveries2Result')) {
                return $result->GetDeliveries2Result;
            }

            return $result;

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

            if (property_exists($result, 'GetDeliveriesResult')) {
                return $result->GetDeliveriesResult;
            }

            return $result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت قیمت هر پیامک قبل از ارسال (تعرفه پایه)
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
                'irancellCount' => (int)$irancellCount,
                'mtnCount'      => (int)$mtnCount,
                'from'          => (string)$from,
                'text'          => (string)$text
            ];

            $result = $this->client->GetSmsPrice($params);

            if (property_exists($result, 'GetSmsPriceResult')) {
                return (float)$result->GetSmsPriceResult;
            }

            return $result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت لیست شماره خطوط (فرستنده‌ها)
     *
     * توجه: این متد در وب سرویس Send.asmx وجود ندارد
     * برای دریافت شماره خطوط از وب سرویس Users.asmx استفاده کنید
     *
     * @return array لیست شماره‌ها
     */
    public function getUserNumbers()
    {
        return [
            'error' => true,
            'message' => 'این متد در وب سرویس Send.asmx وجود ندارد. لطفا از وب سرویس Users.asmx استفاده کنید.',
            'code' => 0
        ];
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

            if (property_exists($result, 'IsAuthenticatedResult')) {
                return (bool)$result->IsAuthenticatedResult;
            }

            return $result;

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

            if (property_exists($result, 'GetBasePriceResult')) {
                return (float)$result->GetBasePriceResult;
            }

            return $result;

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

            if (property_exists($result, 'GetUserDetailsResult')) {
                return $result->GetUserDetailsResult;
            }

            return $result;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }
}