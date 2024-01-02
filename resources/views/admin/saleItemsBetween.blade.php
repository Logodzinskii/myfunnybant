@extends('admin.layouts.adminHome')
@section('content')
<form method="post" name="date_setUp" action="{{route('sum.date.between')}}">
    @csrf
    <div class="container d-flex justify-content-start mt-2 mb-2 bg-light">
        <div class="m-2">
            <label for="date_start"> Продажи с </label>
            <input type = "date" name = "date_start" value="{{$date_start}}">
        </div>
        <div class="m-2">
            <label for="date_start">Продажи по </label>
            <input type = "date" name = "date_stop" value="{{$date_stop}}">
        </div>
        <input type="submit" value="Применить">
    </div>
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
                            <div>
                                <div class="db_date" data-db-id="{{$sales->id}}">
                                    {{date_format(new \DateTime($sales->date_sale), 'd.m.Y')}}
                                </div>
                                <div class="hidden-form-edit">
                                    <input type="date" name="date" data-id="{{$sales->id}}"/>
                                    <i class="bi-x-square"></i>
                                </div>
                                <i class="bi-pencil-square"></i>
                            </div>
                        </td>
                        <td>{{$sales->count_items}} * {{$sales->sale_price}} = {{$sales->count_items * $sales->sale_price}}</td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
    </div>
<script type="text/javascript">
    $(document).ready(function () {
        $('.hidden-form-edit').css('display','none');
        $('.bi-pencil-square').on('click', function () {
            let form = $(this).parent().find('.hidden-form-edit');
            form.css('display','block');
        });
        $('.bi-x-square').on('click', function (){
            $(this).parent().css('display','none');
        });
        $('input[name="date"]').on('focusout', function () {
            let id = $(this).data('id');
            let date = $(this).val();
            $.ajax(
                {   url:'/admin/sale/edit/date',
                    type:'post',
                    data: {
                        date:date,
                        id:id,
                        "_token": $('meta[name="csrf-token"]').attr('content')},
                    success: function(data){
                        let date = $('body').find("[data-db-id='" + id + "']");
                        date.text(data);
                        let hidden = $('body').find("[data-id='" + id + "']");
                        hidden.parent().css('display','none');
                    },
                    error:function (error) {
                        console.log(error);
                    }
                })
        })
    })
</script>
@endsection

