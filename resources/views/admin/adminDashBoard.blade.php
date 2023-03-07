@extends('admin.layouts.adminHome')
@section('content')
<div class="col-12 d-flex justify-content-start flex-wrap">
    <div class="card col-12 col-sm-3">
        <div class="card-header">Продажи за сегодня</div>
        <div class="card-body">
            <p>{{$stat['todayThisYear']}} - {{$stat['todayThisYearRes']}}</p>
            <p>{{$stat['yesterday']}} - {{$stat['yesterdayRes']}}</p>
        </div>
    </div>
    <div class="card col-12 col-sm-3">
        <div class="card-header">Продажи за неделю</div>
        <div class="card-body">
            <p>c {{$stat['weekThisYearRes'][0]}} по {{$stat['weekThisYearRes'][1]}} {{$stat['weekThisYearRes'][2]}}</p>
            <p>с {{$stat['weekLastYearRes'][0]}} по {{$stat['weekLastYearRes'][1]}} {{$stat['weekLastYearRes'][2]}}</p>
        </div>
    </div>
    <div class="card col-12 col-sm-3">
        <div class="card-header">Продажи за месяц</div>
        <div class="card-body">
            <p>В этом месяце - {{$stat['monthThisYearRes']}}</p>
            <p>В этом месяце за прошлый год - {{$stat['monthLastYearRes']}}</p>
        </div>
    </div>

</div>
@endsection
