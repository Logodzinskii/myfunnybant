<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\saleitems;
use App\Models\User;
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
        if ($result[0]->done = '')
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
        return $result[0]->done;
    }

    public function showAllSaleItems()
    {
        $items = saleitems::paginate(30)->withQueryString();
        return view('admin/itemsView', ['items'=>$items]);
    }


}
