<script type="text/javascript">
    const clickButton = document.querySelector('.anime');

    function showMessage(){
        let animation = anime({
            targets: '.anime',
            translateX: 20,
            endDelay: 200,
            direction: 'alternate'
        });
    }

    let animation = anime({
        targets: '.staggering-easing-demo .el',
        translateY: [30, 0],
        delay: anime.stagger(200, {easing: 'easeOutQuad'}),
    });

</script>
<div class="row position-relative d-flex flex-nowrap">
    <div class="col-4 d-flex justify-content-center" >
        <div>
            <div>
                <a href="{{url('/my/like')}}">Посмотреть</a>
            </div>
            <i class="bi bi-like p-3"></i>
            @if(session()->exists('ozon_id'))
                <span class="badge text-bg-secondary position-absolute rounded-circle" id="countLike">
                {{count(session()->get('ozon_id'))}}
            </span>
            @else
                <span class="badge text-bg-secondary position-absolute rounded-circle" id="countLike">
                {{0}}
            </span>
            @endif
        </div>
    </div>
    <div class="container col-4 d-flex justify-content-center flex-wrap">
        <div class="col-12">
            <a href="{{url('/actions/')}}">
                Товары по акции:
            </a>
        </div>
        <div class="col-12">
            <i class="bi-percent p-3" style="color: #6610f2; font-size: 2em"></i>
            <span class="badge text-bg-secondary position-absolute rounded-circle">
                {{session()->has('1')?  session()->all()['1'] : '0'}}
            </span>
        </div>
    </div>
    <div class="container col-4 d-flex justify-content-center flex-wrap">
        <div class="col-12">
            <a href="{{url('/user/get/cart')}}">
                Мои заказы:
            </a>
        </div>
        <div class="col-12">
            <i class="bi-bag-heart p-3" style="color: #6610f2; font-size: 2em"></i>
            <span class="badge text-bg-secondary position-absolute rounded-circle">
                0
            </span>
        </div>
    </div>
</div>
