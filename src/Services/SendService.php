<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Services;

use SoapFault;

/**
 * وب سرویس ارسال پیامک فراپیامک (Send.asmx)
 *
 * این کلاس شامل تمام متدهای مربوط به ارسال پیامک، OTP،
 * دریافت اعتبار، وضعیت تحویل و ... می‌باشد
 *
 * @version 2.1.0
 * @package Amirrajabzadeh\FarapayamakLaravel\Services
 */
class SendService extends BaseService
{
    /**
     * پیام‌های خطا بر اساس کد برگشتی وب سرویس
     */
    protected const ERROR_MESSAGES = [
        0  => 'نام کاربری یا رمز عبور اشتباه است',
        1  => 'درخواست با موفقیت انجام شد',
        2  => 'اعتبار حساب کاربری کافی نیست',
        3  => 'محدودیت ارسال روزانه به پایان رسیده است',
        4  => 'محدودیت حجم ارسال رعایت نشده است',
        5  => 'شماره فرستنده معتبر نیست',
        6  => 'سامانه در حال بروزرسانی است، لطفاً چند دقیقه دیگر تلاش کنید',
        7  => 'متن پیامک حاوی کلمات فیلتر شده است',
        8  => 'ارسال از خطوط عمومی از طریق وب سرویس امکان پذیر نیست',
        9  => 'کاربر مورد نظر فعال نیست',
        10 => 'ارسال انجام نشد',
        11 => 'مدارک کاربر کامل نیست',
        12 => 'متن پیامک حاوی لینک است',
        14 => 'شماره گیرنده ای یافت نشد',
        15 => 'متن پیامک خالی است',
        16 => 'شماره موبایل معتبر نیست',
    ];

    /**
     * پیام‌های وضعیت تحویل پیامک
     */
    protected const DELIVERY_MESSAGES = [
        0   => 'ارسال شده به مخابرات',
        1   => 'رسیده به گوشی',
        2   => 'نرسیده به گوشی',
        3   => 'خطای مخابراتی',
        5   => 'خطای نامشخص',
        8   => 'رسیده به مخابرات',
        16  => 'نرسیده به مخابرات',
        35  => 'شماره در لیست سیاه',
        100 => 'وضعیت نامشخص',
        200 => 'ارسال شده',
        300 => 'فیلتر شده',
        400 => 'در لیست ارسال',
        500 => 'عدم پذیرش',
    ];

    /**
     * متن اضافه شده به انتهای پیام (الزامی برای وب سرویس فراپیامک)
     */
    protected const REQUIRED_SUFFIX = "\nلغو11";

    /**
     * ساخت پاسخ استاندارد برای موفقیت
     *
     * @param mixed $data داده اصلی
     * @param string $message پیام توضیحی
     * @param mixed $raw پاسخ خام وب سرویس
     * @return array
     */
    protected function successResponse($data, string $message, $raw = null): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'code' => null,
            'raw' => $raw
        ];
    }

    /**
     * ساخت پاسخ استاندارد برای خطا
     *
     * @param int $code کد خطا
     * @param string|null $message پیام توضیحی (اگر null باشد از جدول گرفته می‌شود)
     * @param mixed $raw پاسخ خام وب سرویس
     * @return array
     */
    protected function errorResponse(int $code, ?string $message = null, $raw = null): array
    {
        return [
            'success' => false,
            'message' => $message ?? (self::ERROR_MESSAGES[$code] ?? 'خطای ناشناخته رخ داده است'),
            'data' => null,
            'code' => $code,
            'raw' => $raw
        ];
    }

    /**
     * ساخت پاسخ استاندارد برای وضعیت تحویل
     *
     * @param int $code کد وضعیت
     * @param mixed $raw پاسخ خام وب سرویس
     * @return array
     */
    protected function deliveryResponse(int $code, $raw = null): array
    {
        return [
            'success' => true,
            'message' => self::DELIVERY_MESSAGES[$code] ?? 'وضعیت نامشخص',
            'data' => $code,
            'code' => null,
            'raw' => $raw
        ];
    }

    /**
     * اضافه کردن "لغو11" به انتهای متن پیام (در خط جدید)
     *
     * @param string $text متن اصلی پیام
     * @return string متن با افزودن لغو11
     */
    protected function addRequiredSuffix(string $text): string
    {
        // حذف لغو11 قبلی اگر وجود داشته باشد
        $cleanText = preg_replace('/\n?لغو11$/', '', $text);

        // اضافه کردن لغو11 در خط جدید
        return $cleanText . self::REQUIRED_SUFFIX;
    }

    /**
     * ارسال پیامک ساده به یک گیرنده (نسخه 2 - پیشنهادی)
     *
     * طبق مستندات رسمی، در متد SendSimpleSMS2 پارامتر to از نوع String است
     * همچنین به صورت خودکار "لغو11" به انتهای پیام اضافه می‌شود
     *
     * @param string $to شماره گیرنده (فقط یک شماره)
     * @param string $from شماره فرستنده (ثبت شده در پنل)
     * @param string $text متن پیامک
     * @param bool $isFlash ارسال فلش (true/false)
     * @return array آرایه استاندارد شامل success, message, data, code, raw
     */
    public function sendSimpleSms(string $to, string $from, string $text, bool $isFlash = false): array
    {
        try {
            // اضافه کردن "لغو11" به انتهای پیام
            $finalText = $this->addRequiredSuffix($text);

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'to'       => $to,
                'from'     => $from,
                'text'     => $finalText,
                'isflash'  => $isFlash
            ];

            $result = $this->client->SendSimpleSMS2($params);
            $rawResult = $result->SendSimpleSMS2Result;

            // تشخیص موفقیت واقعی:
            // 1. عدد باشد
            // 2. بزرگتر از 1000 باشد (RecId واقعی)
            // 3. در آرایه ERROR_MESSAGES کلید نباشد
            $isRealSuccess = is_numeric($rawResult) && $rawResult > 1000 && !isset(self::ERROR_MESSAGES[(int)$rawResult]);

            if ($isRealSuccess) {
                return $this->successResponse(
                    (int)$rawResult,
                    'پیامک با موفقیت ارسال شد',
                    $rawResult
                );
            }

            // کد 1 معنای خاصی دارد ("درخواست موفق" ولی RecId نیست)
            if ($rawResult == 1) {
                return $this->successResponse(
                    null,
                    'درخواست ارسال با موفقیت ثبت شد (دریافت شناسه پیامک ممکن نیست)',
                    $rawResult
                );
            }

            // خطای وب سرویس
            $code = (int)$rawResult;
            return $this->errorResponse($code, null, $rawResult);

        } catch (SoapFault $e) {
            return $this->errorResponse(0, $e->getMessage(), $e->getMessage());
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
     * @return array آرایه استاندارد
     */
    public function sendSimpleSmsToMultiple(array $to, string $from, string $text, bool $isFlash = false): array
    {
        try {
            // اضافه کردن "لغو11" به انتهای پیام
            $finalText = $this->addRequiredSuffix($text);

            // تبدیل به آرایه از رشته‌ها
            $toArray = array_map('strval', $to);

            // بررسی حداکثر 100 شماره
            if (count($toArray) > 100) {
                return $this->errorResponse(4, 'حداکثر 100 شماره می‌تواند در هر بار فراخوانی ارسال شود', null);
            }

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'to'       => $toArray,
                'from'     => $from,
                'text'     => $finalText,
                'isflash'  => $isFlash
            ];

            $result = $this->client->SendSimpleSMS($params);
            $rawResult = $result->SendSimpleSMSResult;

            $isRealSuccess = is_numeric($rawResult) && $rawResult > 1000 && !isset(self::ERROR_MESSAGES[(int)$rawResult]);

            if ($isRealSuccess) {
                return $this->successResponse(
                    (int)$rawResult,
                    'پیامک با موفقیت ارسال شد',
                    $rawResult
                );
            }

            if ($rawResult == 1) {
                return $this->successResponse(
                    null,
                    'درخواست ارسال با موفقیت ثبت شد (دریافت شناسه پیامک ممکن نیست)',
                    $rawResult
                );
            }

            $code = (int)$rawResult;
            return $this->errorResponse($code, null, $rawResult);

        } catch (SoapFault $e) {
            return $this->errorResponse(0, $e->getMessage(), $e->getMessage());
        }
    }

    /**
     * ارسال OTP (کد تأیید یکبارمصرف) - طبق مستندات رسمی
     *
     * @param string $to شماره گیرنده
     * @param string $from شماره فرستنده (اختیاری - می تواند خالی باشد)
     * @param int $code کد تأیید عددی (مثلاً 123456)
     * @return array آرایه استاندارد
     */
    public function sendOtp(string $to, string $from, int $code): array
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'to'       => $to,
                'from'     => $from,
                'code'     => $code
            ];

            $result = $this->client->SendOtp($params);
            $rawResult = $result->SendOtpResult;

            // OTP معمولاً RecId بزرگ برمی‌گرداند
            $isRealSuccess = is_numeric($rawResult) && $rawResult > 1000 && !isset(self::ERROR_MESSAGES[(int)$rawResult]);

            if ($isRealSuccess) {
                return $this->successResponse(
                    (string)$rawResult,
                    'کد تأیید با موفقیت ارسال شد',
                    $rawResult
                );
            }

            if ($rawResult == 1) {
                return $this->successResponse(
                    null,
                    'درخواست ارسال کد تأیید با موفقیت ثبت شد',
                    $rawResult
                );
            }

            $code = is_numeric($rawResult) ? (int)$rawResult : 0;
            return $this->errorResponse($code, null, $rawResult);

        } catch (SoapFault $e) {
            return $this->errorResponse(0, $e->getMessage(), $e->getMessage());
        }
    }

    /**
     * ارسال پیامک از طریق خط خدماتی (Base Number)
     *
     * @param string $text متن پیامک
     * @param string $to شماره گیرنده
     * @param int $bodyId شناسه بدنه پیامک در پنل
     * @return array آرایه استاندارد
     */
    public function sendByBaseNumber(string $text, string $to, int $bodyId): array
    {
        try {
            // اضافه کردن "لغو11" به انتهای پیام
            $finalText = $this->addRequiredSuffix($text);

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'text'     => $finalText,
                'to'       => $to,
                'bodyId'   => $bodyId
            ];

            $result = $this->client->SendByBaseNumber($params);
            $rawResult = $result->SendByBaseNumberResult;

            $isRealSuccess = is_numeric($rawResult) && $rawResult > 1000 && !isset(self::ERROR_MESSAGES[(int)$rawResult]);

            if ($isRealSuccess) {
                return $this->successResponse(
                    (int)$rawResult,
                    'پیامک با موفقیت ارسال شد',
                    $rawResult
                );
            }

            if ($rawResult == 1) {
                return $this->successResponse(
                    null,
                    'درخواست ارسال با موفقیت ثبت شد',
                    $rawResult
                );
            }

            $code = (int)$rawResult;
            return $this->errorResponse($code, null, $rawResult);

        } catch (SoapFault $e) {
            return $this->errorResponse(0, $e->getMessage(), $e->getMessage());
        }
    }

    /**
     * ارسال پیامک از طریق خط خدماتی نسخه 2
     *
     * @param string $text متن پیامک
     * @param string $to شماره گیرنده
     * @param int $bodyId شناسه بدنه پیامک
     * @return array آرایه استاندارد
     */
    public function sendByBaseNumber2(string $text, string $to, int $bodyId): array
    {
        try {
            // اضافه کردن "لغو11" به انتهای پیام
            $finalText = $this->addRequiredSuffix($text);

            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'text'     => $finalText,
                'to'       => $to,
                'bodyId'   => $bodyId
            ];

            $result = $this->client->SendByBaseNumber2($params);
            $rawResult = $result->SendByBaseNumber2Result;

            $isRealSuccess = is_numeric($rawResult) && $rawResult > 1000 && !isset(self::ERROR_MESSAGES[(int)$rawResult]);

            if ($isRealSuccess) {
                return $this->successResponse(
                    (int)$rawResult,
                    'پیامک با موفقیت ارسال شد',
                    $rawResult
                );
            }

            if ($rawResult == 1) {
                return $this->successResponse(
                    null,
                    'درخواست ارسال با موفقیت ثبت شد',
                    $rawResult
                );
            }

            $code = (int)$rawResult;
            return $this->errorResponse($code, null, $rawResult);

        } catch (SoapFault $e) {
            return $this->errorResponse(0, $e->getMessage(), $e->getMessage());
        }
    }

    /**
     * دریافت اعتبار باقی‌مانده حساب
     *
     * @return array آرایه استاندارد
     */
    public function getCredit(): array
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password
            ];

            $result = $this->client->GetCredit($params);
            $rawResult = $result->GetCreditResult;

            if (is_numeric($rawResult) && $rawResult >= 0) {
                $credit = (float)$rawResult;
                return $this->successResponse(
                    $credit,
                    "اعتبار شما " . number_format($credit) . " ریال است",
                    $rawResult
                );
            }

            $code = (int)$rawResult;
            return $this->errorResponse($code, null, $rawResult);

        } catch (SoapFault $e) {
            return $this->errorResponse(0, $e->getMessage(), $e->getMessage());
        }
    }

    /**
     * دریافت وضعیت تحویل یک پیامک (از اپراتور)
     *
     * @param int $recId شناسه پیامک
     * @return array آرایه استاندارد
     */
    public function getDeliveryStatus(int $recId): array
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'recId'    => $recId
            ];

            $result = $this->client->GetDelivery($params);
            $rawResult = $result->GetDeliveryResult;

            $statusCode = (int)$rawResult;
            return $this->deliveryResponse($statusCode, $rawResult);

        } catch (SoapFault $e) {
            return $this->errorResponse(0, $e->getMessage(), $e->getMessage());
        }
    }

    /**
     * دریافت وضعیت تحویل چندین پیامک
     *
     * @param array $recIds آرایه شناسه پیامک‌ها
     * @return array آرایه استاندارد
     */
    public function getMultipleDeliveryStatus(array $recIds): array
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'recIds'   => $recIds
            ];

            $result = $this->client->GetDeliveries($params);
            $rawResult = $result->GetDeliveriesResult;

            $statuses = [];
            foreach ($rawResult as $index => $statusCode) {
                $statuses[] = [
                    'recId' => $recIds[$index] ?? $index,
                    'status_code' => (int)$statusCode,
                    'status_text' => self::DELIVERY_MESSAGES[(int)$statusCode] ?? 'وضعیت نامشخص'
                ];
            }

            return $this->successResponse(
                $statuses,
                'وضعیت تحویل پیامک‌ها دریافت شد',
                $rawResult
            );

        } catch (SoapFault $e) {
            return $this->errorResponse(0, $e->getMessage(), $e->getMessage());
        }
    }

    /**
     * دریافت قیمت هر پیامک قبل از ارسال (تعرفه پایه)
     *
     * @param int $irancellCount تعداد پیامک ایرانسل
     * @param int $mtnCount تعداد پیامک همراه اول
     * @param string $from شماره فرستنده
     * @param string $text متن پیامک
     * @return array آرایه استاندارد
     */
    public function getSmsPrice(int $irancellCount, int $mtnCount, string $from, string $text): array
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
            $rawResult = $result->GetSmsPriceResult;

            if (is_numeric($rawResult) && $rawResult > 0) {
                return $this->successResponse(
                    (float)$rawResult,
                    "قیمت هر پیامک " . number_format($rawResult) . " ریال است",
                    $rawResult
                );
            }

            $code = (int)$rawResult;
            return $this->errorResponse($code, null, $rawResult);

        } catch (SoapFault $e) {
            return $this->errorResponse(0, $e->getMessage(), $e->getMessage());
        }
    }

    /**
     * بررسی اعتبار نام کاربری و رمز عبور
     *
     * @return array آرایه استاندارد
     */
    public function isAuthenticated(): array
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password
            ];

            $result = $this->client->IsAuthenticated($params);
            $rawResult = $result->IsAuthenticatedResult;

            $isValid = (bool)$rawResult;

            return $this->successResponse(
                $isValid,
                $isValid ? 'نام کاربری و رمز عبور صحیح است' : 'نام کاربری یا رمز عبور اشتباه است',
                $rawResult
            );

        } catch (SoapFault $e) {
            return $this->errorResponse(0, $e->getMessage(), $e->getMessage());
        }
    }

    /**
     * دریافت قیمت پایه
     *
     * @return array آرایه استاندارد
     */
    public function getBasePrice(): array
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password
            ];

            $result = $this->client->GetBasePrice($params);
            $rawResult = $result->GetBasePriceResult;

            if (is_numeric($rawResult)) {
                return $this->successResponse(
                    (float)$rawResult,
                    "قیمت پایه " . number_format($rawResult) . " ریال است",
                    $rawResult
                );
            }

            return $this->errorResponse(0, 'خطا در دریافت قیمت پایه', $rawResult);

        } catch (SoapFault $e) {
            return $this->errorResponse(0, $e->getMessage(), $e->getMessage());
        }
    }

    /**
     * دریافت جزئیات کاربر
     *
     * @return array آرایه استاندارد
     */
    public function getUserDetails(): array
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password
            ];

            $result = $this->client->GetUserDetails($params);
            $rawResult = $result->GetUserDetailsResult;

            if ($rawResult && !is_numeric($rawResult)) {
                return $this->successResponse(
                    $rawResult,
                    'جزئیات کاربر دریافت شد',
                    $rawResult
                );
            }

            $code = is_numeric($rawResult) ? (int)$rawResult : 0;
            return $this->errorResponse($code, null, $rawResult);

        } catch (SoapFault $e) {
            return $this->errorResponse(0, $e->getMessage(), $e->getMessage());
        }
    }

    /**
     * دریافت لیست شماره خطوط (فرستنده‌ها)
     *
     * توجه: این متد در وب سرویس Send.asmx وجود ندارد
     * برای دریافت شماره خطوط از وب سرویس Users.asmx استفاده کنید
     *
     * @return array آرایه استاندارد با پیام خطا
     */
    public function getUserNumbers(): array
    {
        return $this->errorResponse(
            0,
            'این متد در وب سرویس Send.asmx وجود ندارد. لطفا از وب سرویس Users.asmx استفاده کنید.',
            null
        );
    }
}