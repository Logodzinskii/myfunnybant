<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


use App\Http\Controllers\yandex\YandexYmlGenerator;
use App\Http\Controllers\CreateShopController;
use App\Models\Offers;
use App\Models\OzonShop;
use App\Models\OzonShopItem;
use App\Models\StatusPriceShopItems;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\This;

class SendLogInfoVisitors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:VisitorCount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count visitors';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $createshop = new CreateShopController();
        $createshop = $createshop->createShop();
        
        $chatId = config('telegram.TELEGRAMADMIN');
        $token = config('telegram.TELEGRAMTOKEN');
        $message = 'Товары обновлены на сайте';
        $response = array(
            'chat_id' => $chatId,
            'text' => $createshop,
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
    
    protected function create()
    {
        $data = '{
        "filter": {
        "visibility": "IN_SALE"
        },
        "limit": 1000,
        "last_id": "",
        "sort_dir": "DESC"
        }';
        $method = '/v3/products/info/attributes';

        $arrOzonItems = StatGetOzon::getOzonCurlHtml($data, $method);

        $arr = [];
        foreach ($arrOzonItems['result'] as $off){

            if((OzonShop::where('ozon_id', '=', $off['id'])->count() > 0))
            {
                OzonShop::where('ozon_id', '=', $off['id'])
                    ->update([
                        'ozon_id'=>$off['id'],
                        'url_chpu'=>StatGetOzon::chpuGenerator($off['name']),
                        'like_count'=>0,
                    ]);
                $arr[] = ['Update at OzonShop' => $off['id']];
            }else{
                OzonShop::create([
                    'ozon_id'=>$off['id'],
                    'url_chpu'=>StatGetOzon::chpuGenerator($off['name']),
                    'like_count'=>0,
                ]);
                $arr[] = ['Create at OzonShop' => $off['id']];
            }
        }
        return $arr;
    }

    protected function createPrice()
    {
        $data = '{
        "filter": {
        "visibility": "IN_SALE"
        },
        "limit": 1000,
        "last_id": "",
        "sort_dir": "DESC"
        }';
        $method = '/v3/products/info/attributes';

        $arrOzonItems = StatGetOzon::getOzonCurlHtml($data, $method);

        $arr = [];
        foreach ($arrOzonItems['result'] as $off){

            if((StatusPriceShopItems::where('ozon_id', '=', $off['id'])->count() > 0))
            {
                $price = $this->GetPrice($off['id'])[0];
                StatusPriceShopItems::where('ozon_id', '=', $off['id'])->
                update([
                    'ozon_id'=>$off['id'],
                    'status'=>'update',
                    'price'=>$price['price']['old_price'],
                    'action_price'=>$price['price']['marketing_price']+$price['price']['marketing_price']*0.2,
                    'fbs'=>0,
                    'fbo'=>0,
                ]);
                $arr[] = ['Update at Price' => $off['id']];
            }else{
                $price = $this->GetPrice($off['id'])[0];
                StatusPriceShopItems::create([
                    'ozon_id'=>$off['id'],
                    'status'=>'create',
                    'price'=>$price['price']['old_price'],
                    'action_price'=>$price['price']['marketing_price']+$price['price']['marketing_price']*0.2,
                    'fbs'=>0,
                    'fbo'=>0,
                ]);

                $arr[] = ['Create at Price' => $off['id']];
            }
        }
        return $arr;
    }
    protected function GetPrice($product_id)
    {
        $data = '{
            "filter": {

            "product_id": [
                "'.$product_id.'"
            ],
            "visibility": "ALL"
            },
            "last_id": "",
            "limit": 100
        }';
        $method = '/v4/product/info/prices';

        $arrOzonItems = StatGetOzon::getOzonCurlHtml($data, $method);

        return $arrOzonItems['result']['items'];
    }

    public function maxLike()
    {
        $like = OzonShop::where('like_count', '>', 0)
            ->orderBy('like_count', 'desc')
            ->get();
        $offers=[];
        foreach ($like as $item)
        {
            $data = '{
                "filter": {
                    "product_id": [
                        "'.$item->ozon_id.'"
                    ],
                    "visibility": "ALL"
                },
                "limit": 100,
                "last_id": "okVsfA==«",
                "sort_dir": "ASC"
            }';
            $method = '/v3/products/info/attributes';
            $arrOzonItems = StatGetOzon::getOzonCurlHtml($data, $method);
            $offers[] = new Offers([
                'name'=>$arrOzonItems['result'][0]['name'],
                'images'=>$arrOzonItems['result'][0]['images'],
                //'attributes'=>$off['attributes'][0]['attribute_id'],
                'attributes'=>[
                    'id'=>$arrOzonItems['result'][0]['id'],
                    'category'=> $arrOzonItems['result'][0]['category_id'],
                    'type'=>StatGetOzon::attributeFilter($arrOzonItems['result'][0]['attributes'], 8229),
                    'header'=>StatGetOzon::attributeFilter($arrOzonItems['result'][0]['attributes'], 4180),
                    'description'=>StatGetOzon::attributeFilter($arrOzonItems['result'][0]['attributes'], 4191),
                    'colors'=>StatGetOzon::attributeFilter($arrOzonItems['result'][0]['attributes'], 10096),
                    'like'=>$item['like_count'],
                ],
                'price'=>$item['like_count']
            ]);
        }

        return view('admin.adminAllLike', ['data'=>[$offers]]);
    }
}
