<div class="position-relative m-1">
    <div class="shadow rounded-1 p-1" style="min-height: 150px">
        <p>Понравившиеся</p>
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
