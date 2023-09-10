<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\OzonChatList;

class analyticsOzonReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:analyticOzonReport';

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
        $toDay = date('Y-m-d');
        $data = '{
            "date_from": "'.$toDay.'",
            "date_to": "'.$toDay.'",
            "metrics": [
                "hits_view_search"
            ],
            "dimension": [
                "sku",
                "day"
            ],
            "filters": [],
            "sort": [
                {
                    "key": "hits_view_search",
                    "order": "DESC"
                }
            ],
            "limit": 5,
            "offset": 0
        }';
        $method = '/v1/analytics/data';
        $request = 'POST';

        $ResponseJson = getOzonData::getResponseOzon($data, $method, $request);

        if(count(json_decode($ResponseJson,true)['result']) > 0) {
            $array = json_decode($ResponseJson, true);
            $str = 'ТОП-5 просмотренных товаров за сегодня:'. "\n";
            foreach ($array['result']['data'] as $data) {
                $str .= $data['dimensions'][0]['name'] . ' - ' . $data['metrics'][0] . "\n";
            }

            getOzonData::sendMessageByTelegram($str);
        }
        return Command::SUCCESS;
    }
}
