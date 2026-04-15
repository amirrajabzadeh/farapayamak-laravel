<?php

return [
    /*
    |--------------------------------------------------------------------------
    | تنظیمات فراپیامک (MeliPayamak)
    |--------------------------------------------------------------------------
    |
    | نام کاربری و رمز عبور خود را در فایل env قرار دهید
    | آدرس وب سرویس‌ها نیز قابل تنظیم هستند
    |
    */

    // نام کاربری پنل فراپیامک
    'username' => env('FARAPAYAMAK_USERNAME', ''),

    // رمز عبور پنل فراپیامک
    'password' => env('FARAPAYAMAK_PASSWORD', ''),

    // آدرس وب سرویس ارسال (Send)
    'send_wsdl' => env('FARAPAYAMAK_SEND_WSDL', 'http://api.payamak-panel.com/post/Send.asmx?wsdl'),

    // آدرس وب سرویس دریافت (Receive)
    'receive_wsdl' => env('FARAPAYAMAK_RECEIVE_WSDL', 'http://api.payamak-panel.com/post/receive.asmx?wsdl'),

    // آدرس وب سرویس دفترچه تلفن (Contacts)
    'contacts_wsdl' => env('FARAPAYAMAK_CONTACTS_WSDL', 'http://api.payamak-panel.com/post/contacts.asmx?wsdl'),

    // آدرس وب سرویس زمانبندی (Schedule)
    'schedule_wsdl' => env('FARAPAYAMAK_SCHEDULE_WSDL', 'http://api.payamak-panel.com/post/Schedule.asmx?wsdl'),

    // آدرس وب سرویس اقدامات انبوه (Actions)
    'actions_wsdl' => env('FARAPAYAMAK_ACTIONS_WSDL', 'http://api.payamak-panel.com/post/Actions.asmx?wsdl'),

    // آدرس وب سرویس صوتی (Voice)
    'voice_wsdl' => env('FARAPAYAMAK_VOICE_WSDL', 'http://api.payamak-panel.com/post/Voice.asmx?wsdl'),

    // آدرس وب سرویس کاربران (Users)
    'users_wsdl' => env('FARAPAYAMAK_USERS_WSDL', 'http://api.payamak-panel.com/post/Users.asmx?wsdl'),

    // آدرس وب سرویس تیکت‌ها (Tickets)
    'tickets_wsdl' => env('FARAPAYAMAK_TICKETS_WSDL', 'http://api.payamak-panel.com/post/Tickets.asmx?wsdl'),

    // حالت دیباگ (نمایش درخواست و پاسخ)
    'debug' => env('FARAPAYAMAK_DEBUG', false),

    // زمان خروجی (ثانیه)
    'timeout' => env('FARAPAYAMAK_TIMEOUT', 30),
];