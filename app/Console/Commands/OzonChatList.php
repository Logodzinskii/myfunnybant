<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\getOzonData;
class OzonChatList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:OzonChatList';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = '{
                "filter": {
                    "chat_status": "Opened",
                    "unread_only": true
                },
                "limit": 300,
                "offset": 0
            }';
        $method = '/v2/chat/list';
        $request = 'POST';

        $ResponseJson = getOzonData::getResponseOzon($data, $method, $request);

        if(count(json_decode($ResponseJson,true)['chats']) > 0)
        {
            $array = json_decode($ResponseJson,true);
            $str = '';
            foreach ($array['chats'] as $chat)
            {

                if($chat['chat_type'] === 'Buyer_Seller'){

                    $data = '{
                        "chat_id": "'. $chat['chat_id'] .'",
                        "from_message_id": 0,
                        "limit": 1000
                    }';
                    $method = '/v1/chat/history';
                    $request = 'POST';

                    $ResultJson = $this->getResponseOzon($data,$method,$request);
                    $arrayHistory = json_decode($ResultJson,true);
                    $str .= " \n 🕐 " . date( 'd.m.Y', strtotime($chat['created_at'])) ;
                    foreach ($arrayHistory['result'] as $chatHistory)
                    {
                        $text = isset($chatHistory['text']) ?? '';

                        $str .= 'Кто- ' . $chatHistory['user']['type'] . ' text: '. $chatHistory['text'];
                    }

                }else{
                    $str = '';
                }
            }

        }else{
            $str = 'Чатов нет';
        }

        $message = 'Список чатов озон: ' . $str . '; ';

        getOzonData::sendMessageByTelegram($message);

        return Command::SUCCESS;
    }

}
