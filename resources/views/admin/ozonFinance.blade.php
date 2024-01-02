@extends('admin.layouts.adminHome')
@section('content')
    <script type="text/javascript">
        $(document).ready(function () {
            itogo('sale_qty');
            itogo('sale_amount');
            itogo('return_qty');
            itogo('return_amount');
            function itogo(nameRow){
                var sum = 0;
                $('.'+nameRow).each(function() {
                    sum += Number($(this).text());
                });
                $('.total_'+ nameRow).text(sum)
            }
        })
    </script>
    <div class="container">
        <form method="get" name="finance" action="{{route('admin.finance.ozon')}}">
            @csrf
            <input type="date" name="dat"/>
            <button type="submit">Выбрать</button>
        </form>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Наименование</th>
                <th scope="col">Количество товара, реализованного по цене</th>
                <th scope="col">Реализовано на сумму</th>
                <th scope="col">Количество возвращённого товара</th>
                <th scope="col">Возвращено на сумму</th>
            </tr>
            </thead>
            <tbody class="example-popover">
            @foreach($financeReport['rows'] as $report)
                <tr>
                    <th scope="row">{{$report['row_number']}}</th>
                    <td>
                        - {{$report['product_id']}}</br>
                        - {{$report['product_name']}}</br>
                        - {{$report['offer_id']}}
                    </td>
                    <td class="sale_qty">
                        {{$report['sale_qty']}}
                    </td>
                    <td class="sale_amount">
                        {{$report['sale_amount']}}
                    </td>
                    <td class="return_qty">
                        {{$report['return_qty']}}
                    </td>
                    <td class="return_amount">
                        {{$report['return_amount']}}
                    </td>
                <!--<td>
                        Цена продавца с учётом его скидки - {{$report['price']}}</br>
                        Комиссия за продажу по категории - {{$report['commission_percent']}}</br>
                        Цена реализации — цена, по которой покупатель приобрёл товар.</br>
                        Для реализованных товаров - {{$report['price_sale']}}</br>
                        Количество товара, реализованного по цене - {{$report['sale_qty']}}</br>
                        Реализовано на сумму  - {{$report['sale_amount']}}</br>
                        Доплата за счёт Ozon - {{$report['sale_discount']}}</br>
                        Комиссия за реализованный товар с учётом скидок и наценки - {{$report['sale_commission']}}</br>
                        Итого к начислению за реализованный товар - {{$report['sale_price_seller']}}</br>
                        Цена реализации — цена, по которой покупатель приобрёл товар.</br>
                        Для возвращённых товаров - {{$report['return_sale']}}</br>
                        Количество возвращённого товара - {{$report['return_qty']}}</br>
                        Возвращено на сумму - {{$report['return_amount']}}</br>
                        Доплата за счёт Ozon.
                        Сумма скидки за счёт Ozon по возвращённому товару, которую Ozon компенсирует продавцу, если скидка Ozon больше или равна комиссии за продажу - {{$report['return_discount']}}</br>
                        Итого возвращено - {{$report['return_price_seller']}}
                    </td>-->
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Наименование</th>
                <th scope="col" class="total_sale_qty"></th>
                <th scope="col" class="total_sale_amount">Реализовано на сумму</th>
                <th scope="col" class="total_return_qty">Количество возвращённого товара</th>
                <th scope="col" class="total_return_amount">Возвращено на сумму</th>
            </tr>
            </tfoot>
        </table>
    </div>
@endsection

