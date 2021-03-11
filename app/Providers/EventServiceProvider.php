<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\Log;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            Log::create([
                'log_message' => 'Авторизация пользователя' .
                    $event->user->name  . ' (' . $event->user->email . ')'
            ]);
        });

        Event::listen(\Illuminate\Auth\Events\Logout::class, function ($event) {
            Log::create([
                'log_message' => 'Выход пользователя' .
                    $event->user->name  . ' (' . $event->user->email . ')'
            ]);
        });
    }
}
