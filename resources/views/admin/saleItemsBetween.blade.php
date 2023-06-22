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
@endsection
