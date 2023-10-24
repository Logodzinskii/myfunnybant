<?php

namespace App\Listeners;

use App\Events\UserCreateOffer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserCartListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(UserCreateOffer $event)
    {
        $chatId = config('telegram.TELEGRAMADMIN');
        $token = config('telegram.TELEGRAMTOKEN');
        $message = $event->message;
        $response = array(
            'chat_id' => $chatId,
            'text' => $message,
        );

        $ch = curl_init('https://api.telegram.org/bot' . $token . '/sendMessage');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_exec($ch);
        curl_close($ch);
    }
}
