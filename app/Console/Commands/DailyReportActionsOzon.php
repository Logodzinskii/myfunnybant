<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DailyReportActionsOzon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ActionsOzon';

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
        $data = '';

        $method = '/v1/actions';

        $clientId = config('ozon.CLIENT_ID'); //айди шопа
        $apiKey = config('ozon.OZONTOKEN');; // ключ апи
        $url = 'https://api-seller.ozon.ru'.$method;
        $headers = array(
            'Content-Type: application/json',
            'Host: api-seller.ozon.ru',
            'Client-Id: '.$clientId,
            'Api-Key: '.$apiKey
        ) ;
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers
        );

        curl_setopt_array($ch, $options);

        $ResponseJson = curl_exec($ch);

        if(count(json_decode($ResponseJson,true)['result']) > 0)
        {
            $array = json_decode($ResponseJson,true);
            $str = '';
            foreach ($array['result'] as $action)
            {
               $str .=' 📌 ' .$action['title'] . ', 🕐 - ' . date( 'd.m.Y', strtotime( $action['date_start'] ) ) . ' 🕙 - ' . date( 'd.m.Y', strtotime( $action['date_end'] ) ) . ', Количество товаров, доступных для акции - ' . $action['potential_products_count'] . ', Количество товаров, которые участвуют в акции - ' . $action['participating_products_count'];
            }

        }else{
            $str = 'Акций нет';
        }

        $chatId = config('telegram.TELEGRAMADMIN');
        $token = config('telegram.TELEGRAMTOKEN');
        $message = 'Список акций на озон: ' . $str;
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

        return Command::SUCCESS;
    }
}
