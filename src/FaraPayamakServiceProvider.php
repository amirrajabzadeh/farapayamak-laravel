<?php

namespace Farapayamak\Laravel;

use Illuminate\Support\ServiceProvider;
use Farapayamak\Laravel\Services\SendService;
use Farapayamak\Laravel\Services\ReceiveService;
use Farapayamak\Laravel\Services\ContactsService;
use Farapayamak\Laravel\Services\ScheduleService;
use Farapayamak\Laravel\Services\ActionsService;
use Farapayamak\Laravel\Services\VoiceService;
use Farapayamak\Laravel\Services\UsersService;
use Farapayamak\Laravel\Services\TicketsService;

class FaraPayamakServiceProvider extends ServiceProvider
{
    /**
     * ثبت سرویس‌ها در کانتینر لاراول
     */
    public function register()
    {
        // ادغام فایل کانفیگ
        $this->mergeConfigFrom(
            __DIR__ . '/../config/farapayamak.php',
            'farapayamak'
        );

        // ثبت سرویس Send
        $this->app->singleton('farapayamak.send', function ($app) {
            return new SendService(
                config('farapayamak.username'),
                config('farapayamak.password'),
                config('farapayamak.send_wsdl'),
                config('farapayamak.debug', false),
                config('farapayamak.timeout', 30)
            );
        });

        // ثبت سرویس Receive
        $this->app->singleton('farapayamak.receive', function ($app) {
            return new ReceiveService(
                config('farapayamak.username'),
                config('farapayamak.password'),
                config('farapayamak.receive_wsdl'),
                config('farapayamak.debug', false),
                config('farapayamak.timeout', 30)
            );
        });

        // ثبت سرویس Contacts
        $this->app->singleton('farapayamak.contacts', function ($app) {
            return new ContactsService(
                config('farapayamak.username'),
                config('farapayamak.password'),
                config('farapayamak.contacts_wsdl'),
                config('farapayamak.debug', false),
                config('farapayamak.timeout', 30)
            );
        });

        // ثبت سرویس Schedule
        $this->app->singleton('farapayamak.schedule', function ($app) {
            return new ScheduleService(
                config('farapayamak.username'),
                config('farapayamak.password'),
                config('farapayamak.schedule_wsdl'),
                config('farapayamak.debug', false),
                config('farapayamak.timeout', 30)
            );
        });

        // ثبت سرویس Actions
        $this->app->singleton('farapayamak.actions', function ($app) {
            return new ActionsService(
                config('farapayamak.username'),
                config('farapayamak.password'),
                config('farapayamak.actions_wsdl'),
                config('farapayamak.debug', false),
                config('farapayamak.timeout', 30)
            );
        });

        // ثبت سرویس Voice
        $this->app->singleton('farapayamak.voice', function ($app) {
            return new VoiceService(
                config('farapayamak.username'),
                config('farapayamak.password'),
                config('farapayamak.voice_wsdl'),
                config('farapayamak.debug', false),
                config('farapayamak.timeout', 30)
            );
        });

        // ثبت سرویس Users
        $this->app->singleton('farapayamak.users', function ($app) {
            return new UsersService(
                config('farapayamak.username'),
                config('farapayamak.password'),
                config('farapayamak.users_wsdl'),
                config('farapayamak.debug', false),
                config('farapayamak.timeout', 30)
            );
        });

        // ثبت سرویس Tickets
        $this->app->singleton('farapayamak.tickets', function ($app) {
            return new TicketsService(
                config('farapayamak.username'),
                config('farapayamak.password'),
                config('farapayamak.tickets_wsdl'),
                config('farapayamak.debug', false),
                config('farapayamak.timeout', 30)
            );
        });

        // ثبت فasad اصلی
        $this->app->alias('farapayamak.send', 'FaraPayamak');
    }

    /**
     * بوت استرپ پکیج
     */
    public function boot()
    {
        // انتشار فایل کانفیگ
        $this->publishes([
            __DIR__ . '/../config/farapayamak.php' => config_path('farapayamak.php'),
        ], 'farapayamak-config');
    }
}