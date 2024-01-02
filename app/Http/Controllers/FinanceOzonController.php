<?php

namespace App\Http\Controllers;

use App\Charts\MonthlSellerChart;
use App\Models\financeOzon;
use App\Models\saleitems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FinanceOzonController extends Controller
{
    public function readCsv()
    {
        $datas = [];
        if (($open = fopen(storage_path() . '\app\csv\test5.csv', "r")) !== FALSE) {

            while (($data = fgetcsv($open, 1000, ";")) !== FALSE) {
                $datas[] = $data;
            }

            fclose($open);
        }
        //return $datas;
        foreach ($datas as $rows)
        {
            financeOzon::create([
                'name'=>$rows[0],
                'article'=>$rows[1],
                'month'=>$rows[2],
                'year'=>$rows[3],
                'item'=>$rows[4],
                'sale_price'=>$rows[5]
            ]);

            echo '<p>'. $rows[0] .' - '.$rows[1].' - '.$rows[2].' - '.$rows[3].' - '.$rows[4]. ' - '.$rows[5].'</p>' ;
        }

    }

    public function showFinanceReport(MonthlSellerChart $chart, Request $request)
    {
        $get_year = substr($request->dat, 0, 4);
        $today = new \DateTime('now');

        $dat = strlen($get_year)>0?$get_year : $today->format("Y");
        $yearOzon = financeOzon::select('sale_price')
            ->where('year','=',$dat)
            ->sum('sale_price');

        $sum = 0;
        $arr = saleitems::selectRaw('count_items * sale_price AS total')
            ->whereYear('date_sale', $dat)
            ->get();

        foreach ($arr as $i)
        {
            $sum+= $i['total'];
        }

        return view('admin.charts.main', ['chart' => $chart->build($dat),
                                                'ozonYear'=>$yearOzon,
                                                'salesYear'=>$sum]);
    }
}
