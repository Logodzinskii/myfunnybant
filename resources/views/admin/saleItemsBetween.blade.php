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
                    <th scope="col">Фото</th>
                    <th scope="col">Количество</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($allSales))
                    @foreach($allSales as $sales)
                    <tr>
                        <th scope="row">{{$sales->id}}</th>
                        <td>
                            @php
                                $re = '/(file_[0-9]{0,10}.jpg)/';

                                preg_match_all($re, $sales->sale_file, $matches, PREG_SET_ORDER, 0);

                            @endphp
                            <img src="{{asset('/images/saleitems/'.$matches[0][0])}}" class="img-thumbnail" style="height: 90px" />
                            {{$sales->date_sale}}
                        </td>
                        <td>{{$sales->count_items}} * {{$sales->sale_price}} = {{$sales->count_items * $sales->sale_price}}</td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>

    </div>
@endsection
