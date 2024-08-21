<?php

namespace App\Charts;
use App\Console\Commands\getOzonData;
use App\Models\financeOzon;
use App\Models\saleitems;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class MonthlSellerChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($year): \ArielMejiaDev\LarapexCharts\LineChart
    {

        return $this->chart->lineChart()
            ->setTitle('В '. $year)
            ->setSubtitle('Продажи на озон')
            ->addData('Продано ozon', [
                $this->monthSum('1',$year),
                $this->monthSum('2',$year),
                $this->monthSum('3',$year),
                $this->monthSum('4',$year),
                $this->monthSum('5',$year),
                $this->monthSum('6',$year),
                $this->monthSum('7',$year),
                $this->monthSum('8',$year),
                $this->monthSum('9',$year),
                $this->monthSum('10',$year),
                $this->monthSum('11',$year),
                $this->monthSum('12',$year),
                ])
            ->addData('Продано ярмарки', [
                $this->monthSumSales('01',$year),
                $this->monthSumSales('02',$year),
                $this->monthSumSales('03',$year),
                $this->monthSumSales('04',$year),
                $this->monthSumSales('05',$year),
                $this->monthSumSales('06',$year),
                $this->monthSumSales('07',$year),
                $this->monthSumSales('08',$year),
                $this->monthSumSales('09',$year),
                $this->monthSumSales('10',$year),
                $this->monthSumSales('11',$year),
                $this->monthSumSales('12',$year),
            ])
            ->addData('Продано всего', [
                $this->monthSum('1',$year) + $this->monthSumSales('01',$year),
                $this->monthSum('2',$year) + $this->monthSumSales('02',$year),
                $this->monthSum('3',$year) + $this->monthSumSales('03',$year),
                $this->monthSum('4',$year) + $this->monthSumSales('04',$year),
                $this->monthSum('5',$year) + $this->monthSumSales('05',$year),
                $this->monthSum('6',$year) + $this->monthSumSales('06',$year),
                $this->monthSum('7',$year) + $this->monthSumSales('07',$year),
                $this->monthSum('8',$year) + $this->monthSumSales('08',$year),
                $this->monthSum('9',$year) + $this->monthSumSales('09',$year),
                $this->monthSum('10',$year) + $this->monthSumSales('10',$year),
                $this->monthSum('11',$year) + $this->monthSumSales('11',$year),
                $this->monthSum('12',$year) + $this->monthSumSales('12',$year),
            ])
            ->setXAxis(['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']);
    }

    private function monthSum($month, $year){
       // $month = financeOzon::select('sale_price')
       //     ->where('month','=',$month)
       //     ->where('year', '=', $year)
       //     ->sum('sale_price');
       
       $data = '{
        "month": "'. $month .'",
        "year": "'. $year .'"
        }';
        //return var_dump($data);
        $method = '/v2/finance/realization';
        $financeReport = getOzonData::getResponseOzon($data,$method, '');
        if(isset(json_decode($financeReport,true)['result'])){
            
            $month = intval(json_decode($financeReport,true)['result']['header']['doc_amount']);
            //return  (json_decode($financeReport,true)['result']['header']['doc_amount']);
            
        }else
        {
           return 0;//$financeReport;
        }
       
        return $month;
    }

    private function monthSumSales($month, $year)
    {
        $sum = 0;
        $arr = saleitems::selectRaw('count_items * sale_price AS total')
            ->whereMonth('date_sale', $month)
            ->whereYear('date_sale', $year)
            ->get();

        foreach ($arr as $i)
        {
            $sum+= $i['total'];
        }
        return $sum;
    }
}
