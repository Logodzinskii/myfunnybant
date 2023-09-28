<script type="text/javascript">
    const clickButton = document.querySelector('.anime');
    clickButton.addEventListener('mouseout', showMessage, {
        capture: false,
        once: false,
        passive: false,
    })
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
        translateX: [50, 0],
        delay: anime.stagger(300, {easing: 'easeOutQuad'}),
    });



</script>
<div class="row row-col-2 position-relative d-flex flex-nowrap">
    <div class="col-6 d-flex justify-content-center" >
        <div>
            <i class="bi bi-like p-3"></i>
            @if(session()->exists('ozon_id'))
                <span class="badge text-bg-secondary position-absolute rounded-circle" id="countLike">
                {{count(session()->get('ozon_id'))}}
            </span>
                <p>
                    <a href="{{url('/my/like')}}">Посмотреть</a>
                </p>
            @else
                <span class="badge text-bg-secondary position-absolute rounded-circle" id="countLike">
                {{0}}
            </span>
            @endif
        </div>
    </div>
    <div class="col-6 d-flex justify-content-center">
        <i class="bi bi-box-seam-fill"></i>
        <a href="{{url('/actions/')}}" class="list-unstyled">

                <span class="badge text-bg-secondary rounded-circle">
                {{session()->has('1')?  session()->all()['1'] : ''}}
                </span>
        </a>
    </div>
</div>
