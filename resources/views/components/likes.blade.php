<div class="position-relative m-1">
    <div class="shadow rounded-1 p-1" style="min-height: 150px">
        <p>Понравившиеся</p>
        <i class="bi bi-like p-3"></i>
        <span class="badge text-bg-secondary position-absolute rounded-circle" id="countLike">
            @if(session()->exists('ozon_id'))
                {{count(session()->get('ozon_id'))}}
            @else
                {{0}}
            @endif
        </span>
        <p>
            <a href="#">Посмотреть</a>
        </p>

    </div>
</div>
