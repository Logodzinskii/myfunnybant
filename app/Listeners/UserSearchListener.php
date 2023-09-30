<?php

namespace App\Listeners;

use App\Events\UserSearch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserSearchListener
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


    public function handle(UserSearch $event)
    {
        $chatId = config('telegram.TELEGRAMADMIN');
        $token = config('telegram.TELEGRAMTOKEN');
        $message = $event->search;
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
