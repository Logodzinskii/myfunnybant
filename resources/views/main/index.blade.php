@extends('layouts.app')
@section('content')
    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function (){
            $(".eli").on('mouseover',function (){
                let el = $(this);
                let animation = anime({
                    targets: [this],
                    translateY: 5,
                    delay: anime.stagger(70) // increase delay by 100ms for each elements.
                });
                var animateBackground = anime({
                    targets: [this],
                    backgroundColor: 'grey'
                });
            })
            $(".eli").on('mouseout',function (){
                let el = $(this);
                let animation = anime({
                    targets: [this],
                    translateY: 0,
                    delay: anime.stagger(100) // increase delay by 100ms for each elements.
                });
                var animateBackground = anime({
                    targets: [this],
                    backgroundColor: '#fff'
                });
            })
            const height = $(window).height();
            anime({
                targets: '.down',
                translateY: height-50,
                delay: anime.stagger(1000, {start: 3000}) // increase delay by 100ms for each elements.
            });
            anime({
                targets: '.down',
                delay: 6000,
                backgroundColor: 'grey',
                color: '#fff',
                border: '#fff',
            });

        })
    </script>
<section class="col-lg-12 ">
    <div class="position-fixed btn btn-outline-primary top-0 down" style="z-index: 999; "><i class="bi-send"></i></div>
    <div class="position-fixed btn btn-outline-primary down" style="z-index: 999; top: -50px;"><i class="bi-envelope"></i></div>
    @foreach($data as $key=>$items)
        <div class="container  ">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-md-3 g-3 basic-staggering-demo">
                @foreach($items as $item)
                    <x-card header="{{$item->name}}" img="{{json_decode($item->images, true)[0]['file_name']}}" key="{{$key}}" ozonid="{{$item->ozon_id}}"></x-card>
                @endforeach
            </div>
        </div>
    @endforeach
    <div class="container mt-5 mb-5 d-flex justify-content-center flex-wrap">
        {{$data[0]->links()}}
    </div>
</section>
@endsection

