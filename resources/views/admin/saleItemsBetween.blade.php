@extends('admin.layouts.adminHome')
@section('content')
<form method="post" name="date_setUp" action="{{route('sum.date.between')}}">
    @csrf
    <div class="input-group mb-3">
        <label for="date_start">Продажи с</label>
        <input type = "date" name = "date_start" value="{{$date_start}}">
    </div>
    <div class="input-group mb-3">
        <label for="date_start">Продажи по</label>
        <input type = "date" name = "date_stop" value="{{$date_stop}}">
    </div>
    <input type="submit" value="Применить">
</form>
    <div>
        <h2>За выбранный период продано на сумму: {{$sum}}</h2>
    </div>
    <div>

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Дата продажи</th>
                    <th scope="col">Количество</th>
                    <th scope="col">Сумма продажи</th>
                    <th scope="col">Итого</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($allSales))
                    @foreach($allSales as $sales)
                    <tr>
                        <th scope="row">{{$sales->id}}</th>
                        <td>{{$sales->date_sale}}</td>
                        <td>{{$sales->count_items}}</td>
                        <td>{{$sales->sale_price}}</td>
                        <td>{{$sales->count_items * $sales->sale_price}}</td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>

    </div>
@endsection
