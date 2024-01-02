<?php

namespace App\Charts;

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
                $this->monthSum('Январь',$year),
                $this->monthSum('Февраль',$year),
                $this->monthSum('Март',$year),
                $this->monthSum('апрель',$year),
                $this->monthSum('Май',$year),
                $this->monthSum('Июнь',$year),
                $this->monthSum('Июль',$year),
                $this->monthSum('Август',$year),
                $this->monthSum('Сентябрь',$year),
                $this->monthSum('Октябрь',$year),
                $this->monthSum('Ноябрь',$year),
                $this->monthSum('Декабрь',$year),
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
                $this->monthSum('Январь',$year) + $this->monthSumSales('01',$year),
                $this->monthSum('Февраль',$year) + $this->monthSumSales('02',$year),
                $this->monthSum('Март',$year) + $this->monthSumSales('03',$year),
                $this->monthSum('Апрель',$year) + $this->monthSumSales('04',$year),
                $this->monthSum('Май',$year) + $this->monthSumSales('05',$year),
                $this->monthSum('Июнь',$year) + $this->monthSumSales('06',$year),
                $this->monthSum('Июль',$year) + $this->monthSumSales('07',$year),
                $this->monthSum('Август',$year) + $this->monthSumSales('08',$year),
                $this->monthSum('Сентябрь',$year) + $this->monthSumSales('09',$year),
                $this->monthSum('Октябрь',$year) + $this->monthSumSales('10',$year),
                $this->monthSum('Ноябрь',$year) + $this->monthSumSales('11',$year),
                $this->monthSum('Декабрь',$year) + $this->monthSumSales('12',$year),
            ])
            ->setXAxis(['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']);
    }

    private function monthSum($month, $year){
        $month = financeOzon::select('sale_price')
            ->where('month','=',$month)
            ->where('year', '=', $year)
            ->sum('sale_price');
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
