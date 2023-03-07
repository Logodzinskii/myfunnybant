@extends('admin.layouts.adminHome')
@section('content')
<div class="col-12 d-flex justify-content-start flex-wrap">
    <div class="card col-12 col-sm-3">
        <div class="card-header">Продажи за сегодня</div>
        <div class="card-body">
            <div class="text-success">
                <div class="fs-4">{{$stat['todayThisYearRes']}} Р.</div>
                <div class="fs-6">{{$stat['todayThisYear']}}</div>
            </div>
            <div class="text-secondary">
                <div class="fs-4">{{$stat['yesterdayRes']}} Р.</div>
                <div class="fs-6">{{$stat['yesterday']}}</div>
            </div>
            @if(intval($stat['todayThisYearRes']) - intval($stat['yesterdayRes']) >= 0)
                <div class="text-success">
                    <div class="fs-4">{{intval($stat['todayThisYearRes']) - intval($stat['yesterdayRes'])}} Р.</div>
                </div>
            @else
                <div class="text-danger">
                    <div class="fs-4">{{intval($stat['todayThisYearRes']) - intval($stat['yesterdayRes'])}} Р.</div>
                </div>
            @endif
        </div>
    </div>
    <div class="card col-12 col-sm-3">
        <div class="card-header">Продажи за неделю</div>
        <div class="card-body">
            <div class="text-success">
                <div class="fs-4"> {{$stat['weekThisYearRes'][2]}} Р.</div>
                <div class="fs-6">c {{$stat['weekThisYearRes'][0]}} по {{$stat['weekThisYearRes'][1]}}</div>
            </div>
            <div class="text-secondary">
                <div class="fs-4">{{$stat['weekLastYearRes'][2]}} Р.</div>
                <div class="fs-6">с {{$stat['weekLastYearRes'][0]}} по {{$stat['weekLastYearRes'][1]}}</div>
            </div>
            @if(intval($stat['weekThisYearRes'][2]) - intval($stat['weekLastYearRes'][2]) >= 0)
                <div class="text-success">
                    <div class="fs-4">{{intval($stat['weekThisYearRes'][2]) - intval($stat['weekLastYearRes'][2])}} Р.</div>
                </div>
            @else
                <div class="text-danger">
                    <div class="fs-4">{{intval($stat['weekThisYearRes'][2]) - intval($stat['weekLastYearRes'][2])}} Р.</div>
                </div>
            @endif
        </div>
    </div>
    <div class="card col-12 col-sm-3">
        <div class="card-header">Продажи за месяц</div>
        <div class="card-body">
            <div class="card-body">
                <div class="text-success">
                    <div class="fs-4"> {{$stat['monthThisYearRes']}} Р.</div>
                    <div class="fs-6">В этом месяце</div>
                </div>
                <div class="text-secondary">
                    <div class="fs-4">{{$stat['monthLastYearRes']}} Р.</div>
                    <div class="fs-6">В этом месяце за прошлый год</div>
                </div>
                @if(intval($stat['monthThisYearRes']) - intval($stat['monthLastYearRes']) >= 0)
                    <div class="text-success">
                        <div class="fs-4">{{intval($stat['monthThisYearRes']) - intval($stat['monthLastYearRes'])}} Р.</div>
                    </div>
                @else
                    <div class="text-danger">
                        <div class="fs-4">{{intval($stat['monthThisYearRes']) - intval($stat['monthLastYearRes'])}} Р.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card col-12 col-sm-3">
        <div class="card-header">С начала года</div>
        <div class="card-body">
            <div class="card-body">
                <div class="text-success">
                    <div class="fs-4"> {{$stat['thisYearRes']}} Р.</div>
                    <div class="fs-6">С начала года по текущую дату</div>
                </div>
                <div class="text-secondary">
                    <div class="fs-4">{{$stat['lastYearRes']}} Р.</div>
                    <div class="fs-6">Аналогичный период прошлого года</div>
                </div>
                @if(intval($stat['thisYearRes']) - intval($stat['lastYearRes']) >= 0)
                    <div class="text-success">
                        <div class="fs-4">{{intval($stat['thisYearRes']) - intval($stat['lastYearRes'])}} Р.</div>
                    </div>
                @else
                    <div class="text-danger">
                        <div class="fs-4">{{intval($stat['thisYearRes']) - intval($stat['lastYearRes'])}} Р.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

