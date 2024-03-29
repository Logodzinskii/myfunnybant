<?php

namespace App\Http\Controllers\Admin;

use App\Console\Commands\getOzonData;
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
use Illuminate\Support\Facades\Redirect;
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

    public function ozonFinance(Request $request)
    {

        $get_month = substr($request->dat, 0, 7);
        $today = new \DateTime('now');
        $today = $today->modify('-1 month');
        $dat = strlen($get_month)>0?$get_month : $today->format("Y-m");

        $data = '{
        "date": "'.$dat.'"
        }';
        //return var_dump($data);
        $method = '/v1/finance/realization';
        $financeReport = getOzonData::getResponseOzon($data,$method, '');
        if(isset(json_decode($financeReport,true)['result'])){
            return view('admin/ozonFinance', ['financeReport' => (json_decode($financeReport,true)['result'])]);
        }else
        {
            return $financeReport;
        }

    }

    public function editDateSale(Request $request)
    {
        $newDate = $request->date;
        $id = $request->id;
        saleitems::where('id',$id)
                   ->update(['date_sale'=>$newDate]);
        return $newDate;
    }
}
