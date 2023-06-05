<?php

namespace App\Listeners;

use App\Events\ClickOzonLink;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AddToDBVisitWebInformation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ClickOzonLink  $event
     * @return void
     */
    public function handle(ClickOzonLink $event)
    {
        $ipVisitor = $event->request->ip();
        $path = $event->request->path();
        $fullUrl = $event->request->fullUrl();
        $header = $event->request->header('X-Header-Name');
        $userAgent = $event->request->server('HTTP_USER_AGENT');
        Log::info('Посетитель сайта' .'; '. $ipVisitor .'; '. $path .'; '. $fullUrl .'; '. $header .'; '. $userAgent);
    }
}
