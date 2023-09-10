<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\StatGetOzon;
use App\Models\Offers;
use App\Models\OzonShop;
use App\Models\saleitems;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class SaleItemsController extends Controller
{
    public function index()
    {
        $date = new \DateTime('now');
        $yesterdayDate = new \DateTime('- 1 day');
        $weekThisYear = new \DateTime('monday this week');
        $weekLastYear = new \DateTime('- 1 year this week');
        $lastYearDate =  new \DateTime('- 1 year');

        $today = $date->format('Y-m-d');
        $todayThisYearRes = $this->query($today);
        $yesterday = $yesterdayDate->format('Y-m-d');
        $yesterdayRes = $this->query($yesterday);

        $monthThisYearRes = $this->queryBetween($date->format('Y-m-01'), $today );
        $monthLastYearRes = $this->queryBetween($lastYearDate->format('Y-m-01'), $lastYearDate);

        $weekThisYearRes = $this->queryBetween($weekThisYear, $today);
        $weekLastYearRes = $this->queryBetween($weekLastYear, $lastYearDate);

        $thisYearRes = $this->queryBetween($date->format('Y-01-01'), $today );
        $lastYearRes = $this->queryBetween($lastYearDate->format('Y-01-01'), $lastYearDate );
        return view('admin/adminDashBoard', ['stat'=>[
            'todayThisYear'=>$today,
            'todayThisYearRes'=>$todayThisYearRes,
            'yesterday'=>$yesterday,
            'yesterdayRes'=>$yesterdayRes,
            'monthThisYearRes'=>$monthThisYearRes,
            'monthLastYearRes'=>$monthLastYearRes,
            'weekThisYearRes'=>[$weekThisYear->format('d-m-Y'), $date->format('d-m-Y'), $weekThisYearRes],
            'weekLastYearRes'=>[$weekLastYear->format('d-m-Y'), $lastYearDate->format('d-m-Y'), $weekLastYearRes],
            'thisYearRes'=>$thisYearRes,
            'lastYearRes'=>$lastYearRes,
                                                    ]]);
    }

    protected function query($query)
    {
        $result = DB::table('saleitems')
            ->where('date_sale', '=', $query)
            ->select(DB::raw('SUM(count_items*sale_price) as done'))
            ->get();
        if (is_null($result[0]->done))
        {
            return 0;

        }else{

            return $result[0]->done ;
        }

    }

    protected function queryBetween($dateStart, $dateStop)
    {
        $result = DB::table('saleitems')
            ->where('date_sale','>=', $dateStart)
            ->where('date_sale','<=', $dateStop)
            ->select(DB::raw('SUM(count_items*sale_price) as done'))
            ->get();
        if (is_null($result[0]->done))
        {
            return 0;

        }else{

            return $result[0]->done ;
        }
    }

    protected function allSalesQueryBetween($dateStart, $dateStop)
    {
        $result = DB::table('saleitems')
            ->where('date_sale','>=', $dateStart)
            ->where('date_sale','<=', $dateStop)
            ->select('saleitems.*')
            ->get();
        if (is_null($result))
        {
            return 0;

        }else{
            return $result;
        }
    }

    public function showAllSaleItems()
    {
        $items = saleitems::paginate(30)->withQueryString();
        return view('admin/itemsView', ['items'=>$items]);

    }

    public function showDateBetween()
    {
        return view('admin/saleItemsBetween', ['sum'=>'',
            'date_start'=>'',
            'date_stop'=>'',
        ]);
    }

    public function sumDateBetween(Request $request)
    {
        try {
            $validated = $request->validate([
                'date_start'=>'required|date_format:Y-m-d',
                'date_stop'=>'required|date_format:Y-m-d',
            ]);
        }catch (ValidationException $e){
            die($e->getMessage());
        }

        $result = $this->queryBetween($request['date_start'], $request['date_stop']);
        $allSales= $this->allSalesQueryBetween($request['date_start'], $request['date_stop']);

        return view('admin/saleItemsBetween', ['sum'=>$result,
            'date_start'=>$request['date_start'],
            'date_stop'=>$request['date_stop'],
            'allSales'=>$allSales,
        ]);
    }

    public function maxLike()
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

        $offers=[];
        /**
         * 8229 - Бант для волос
         * 4180 - заголовок
         * 4191 - описание
         * 9725 - сезон
         * 22336 - ключевые слова
         * 9024 - ид товара b-01-01
         * 10096 - цвета
         * 5309 - материал
         * 13214 - от скольки возрстная категория
         * 13215 - до скольки лет
         */
        //return $arrOzonItems['result'];
        foreach ($arrOzonItems['result'] as $off){
            if(session()->has('ozon_id') && array_search($off['id'], session()->get('ozon_id')) !== false)
            {
                $like = OzonShop::where('like_count', '>', 0)
                    ->orderBy('like_count', 'desc')
                    ->get();
                $like =$like[0]->like_count;
                $offers[] = new Offers([
                    'name'=>$off['name'],
                    'images'=>$off['images'],
                    //'attributes'=>$off['attributes'][0]['attribute_id'],
                    'attributes'=>[
                        'id'=>$off['id'],
                        'category'=> $off['category_id'],
                        'type'=>StatGetOzon::attributeFilter($off['attributes'], 8229),
                        'header'=>StatGetOzon::attributeFilter($off['attributes'], 4180),
                        'description'=>StatGetOzon::attributeFilter($off['attributes'], 4191),
                        'colors'=>StatGetOzon::attributeFilter($off['attributes'], 10096),
                        'like'=>$like,
                    ],
                    'price'=>''
                ]);
            }
        }

        return view('main.index', ['data'=>[$offers]]);
    }

}
