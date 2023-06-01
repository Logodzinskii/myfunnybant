<?php

namespace App\Listeners;

use App\Events\ClickOzonLink;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendInformationOnClick
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
        $chatId = config('telegram.TELEGRAMADMIN');
        $token = config('telegram.TELEGRAMTOKEN');
        $message = 'Переход по ссылке: https://myfunnybant.ru/category/' . $event->offerId;
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
