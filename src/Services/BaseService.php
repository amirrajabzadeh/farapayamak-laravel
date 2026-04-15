<?php

namespace Farapayamak\Laravel\Services;

use SoapClient;
use SoapFault;
use Exception;

/**
 * کلاس پایه برای تمام سرویس‌های فراپیامک
 *
 * @package Farapayamak\Laravel\Services
 */
abstract class BaseService
{
    /**
     * @var SoapClient|null کلاینت SOAP
     */
    protected $client;

    /**
     * @var string نام کاربری
     */
    protected $username;

    /**
     * @var string رمز عبور
     */
    protected $password;

    /**
     * @var bool حالت دیباگ
     */
    protected $debug;

    /**
     * @var int زمان خروجی
     */
    protected $timeout;

    /**
     * سازنده کلاس پایه
     *
     * @param string $username نام کاربری
     * @param string $password رمز عبور
     * @param string $wsdlUrl آدرس WSDL
     * @param bool $debug حالت دیباگ
     * @param int $timeout زمان خروجی
     * @throws Exception در صورت خطا در اتصال
     */
    public function __construct($username, $password, $wsdlUrl, $debug = false, $timeout = 30)
    {
        $this->username = $username;
        $this->password = $password;
        $this->debug = $debug;
        $this->timeout = $timeout;

        $options = [
            'encoding' => 'UTF-8',
            'trace' => $debug,
            'exceptions' => true,
            'connection_timeout' => $timeout,
            'cache_wsdl' => WSDL_CACHE_NONE
        ];

        try {
            $this->client = new SoapClient($wsdlUrl, $options);
        } catch (SoapFault $e) {
            throw new Exception("خطا در اتصال به وب سرویس فراپیامک: " . $e->getMessage());
        }
    }

    /**
     * دریافت آخرین درخواست ارسال شده (برای دیباگ)
     *
     * @return string|null
     */
    public function getLastRequest()
    {
        return $this->client ? $this->client->__getLastRequest() : null;
    }

    /**
     * دریافت آخرین پاسخ دریافت شده (برای دیباگ)
     *
     * @return string|null
     */
    public function getLastResponse()
    {
        return $this->client ? $this->client->__getLastResponse() : null;
    }

    /**
     * دریافت لیست توابع موجود در وب سرویس
     *
     * @return array|null
     */
    public function getAvailableFunctions()
    {
        return $this->client ? $this->client->__getFunctions() : null;
    }

    /**
     * پردازش نتیجه و تبدیل به آرایه در صورت خطا
     *
     * @param mixed $result نتیجه از وب سرویس
     * @param string $resultProperty نام خاصیت نتیجه
     * @return mixed
     */
    protected function processResult($result, $resultProperty)
    {
        if ($result && property_exists($result, $resultProperty)) {
            return $result->{$resultProperty};
        }

        return $result;
    }

    /**
     * مدیریت خطاهای SOAP
     *
     * @param SoapFault $e
     * @return array
     */
    protected function handleSoapFault(SoapFault $e)
    {
        return [
            'error' => true,
            'message' => $e->getMessage(),
            'code' => $e->getCode()
        ];
    }
}