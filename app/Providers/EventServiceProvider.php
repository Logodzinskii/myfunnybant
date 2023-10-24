<?php

namespace App\Providers;

use App\Events\CartConfirmEvent;
use App\Events\ClickOzonLink;
use App\Events\UserCreateOffer;
use App\Listeners\AddToDBVisitWebInformation;
use App\Listeners\CartReportUser;
use App\Listeners\SendInformationOnClick;
use App\Listeners\UserCartListener;
use App\Listeners\UserSearchListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ClickOzonLink::class=>[
            //AddToDBVisitWebInformation::class,
            SendInformationOnClick::class,
            UserSearchListener::class,
        ],
        CartConfirmEvent::class =>[
            CartReportUser::class
        ],
        UserCreateOffer::class=>[
            UserCartListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
