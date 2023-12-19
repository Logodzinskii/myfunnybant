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

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {

        return $this->chart->lineChart()
            ->setTitle('В 2023.')
            ->setSubtitle('Продажи на озон')
            ->addData('Продано ozon', [
                $this->monthSum('Январь'),
                $this->monthSum('Февраль'),
                $this->monthSum('Март'),
                $this->monthSum('Апрель'),
                $this->monthSum('Май'),
                $this->monthSum('Июнь'),
                $this->monthSum('Июль'),
                $this->monthSum('Август'),
                $this->monthSum('Сентябрь'),
                $this->monthSum('Октябрь'),
                $this->monthSum('Ноябрь'),
                $this->monthSum('Декабрь'),
                ])
            ->addData('Продано ярмарки', [
                $this->monthSumSales('01'),
                $this->monthSumSales('02'),
                $this->monthSumSales('03'),
                $this->monthSumSales('04'),
                $this->monthSumSales('05'),
                $this->monthSumSales('06'),
                $this->monthSumSales('07'),
                $this->monthSumSales('08'),
                $this->monthSumSales('09'),
                $this->monthSumSales('10'),
                $this->monthSumSales('11'),
                $this->monthSumSales('12'),
            ])
            ->addData('Продано всего', [
                $this->monthSum('Январь') + $this->monthSumSales('01'),
                $this->monthSum('Февраль') + $this->monthSumSales('02'),
                $this->monthSum('Март') + $this->monthSumSales('03'),
                $this->monthSum('Апрель') + $this->monthSumSales('04'),
                $this->monthSum('Май') + $this->monthSumSales('05'),
                $this->monthSum('Июнь') + $this->monthSumSales('06'),
                $this->monthSum('Июль') + $this->monthSumSales('07'),
                $this->monthSum('Август') + $this->monthSumSales('08'),
                $this->monthSum('Сентябрь') + $this->monthSumSales('09'),
                $this->monthSum('Октябрь') + $this->monthSumSales('10'),
                $this->monthSum('Ноябрь') + $this->monthSumSales('11'),
                $this->monthSum('Декабрь') + $this->monthSumSales('12'),
            ])
            ->setXAxis(['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']);
    }

    private function monthSum($month){
        $month = financeOzon::select('sale_price')
            ->where('month','=',$month)
            ->sum('sale_price');
        return $month;
    }

    private function monthSumSales($month)
    {
        $sum = 0;
        $arr = saleitems::selectRaw('count_items * sale_price AS total')
            ->whereMonth('date_sale', $month)
            ->whereYear('date_sale', '2023')
            ->get();

        foreach ($arr as $i)
        {
            $sum+= $i['total'];
        }
        return $sum;
    }
}
