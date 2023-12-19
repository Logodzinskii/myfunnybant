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
        if (($open = fopen(storage_path() . '\app\csv\test1.csv', "r")) !== FALSE) {

            while (($data = fgetcsv($open, 1000, ";")) !== FALSE) {
                $datas[] = $data;
            }

            fclose($open);
        }

        foreach ($datas as $rows)
        {
            financeOzon::create([
                'name'=>$rows[0],
                'article'=>$rows[1],
                'month'=>$rows[2],
                'year'=>'2023',
                'item'=>$rows[3],
                'sale_price'=>$rows[4]
            ]);

            echo '<p>'. $rows[0] .' - '.$rows[1].' - '.$rows[2].' - '.$rows[3].' - '.$rows[4].'</p>' ;
        }

    }

    public function showFinanceReport(MonthlSellerChart $chart)
    {

        $month = financeOzon::select('sale_price')
            ->where('month','=','февраль')
            ->sum('sale_price');

        return view('admin.charts.main', ['chart' => $chart->build()]);
    }
}
