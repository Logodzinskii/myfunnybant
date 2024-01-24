<style type="text/css">
    .mynav a{
        color: #6610f2;
    }
    .mynav a:hover{
        color: rebeccapurple;
    }
</style>
<nav class="navbar navbar-expand-lg bg-body-tertiary mynav">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Навбар</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Переключатель навигации">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/home">Dash Board</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Продажи
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/admin/show/all/items/">Все продажи</a></li>
                        <li><a class="dropdown-item" href="/admin/total/year/">Статистика</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/admin/sale/date/">За период</a></li>
                        <li><a class="dropdown-item" href="/admin/finance/show">Сравнительные графики</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Ozon
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/admin/finance/ozon">Озон финансовые отчеты</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Заказы
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/admin/view/offers">Все заказы</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Like
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/admin/maxlike">Все</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Товары магазина
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/admin/show/all/products">Все</a></li>
                        <li><a class="dropdown-item" href="/admin/edit/price">Управление ценами</a></li>
                        <li><a class="dropdown-item" href="/admin/createShop/">Обновить товары с озон</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Блоги
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/admin/blog/maker">Главная</a></li>
                    </ul>
                </li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Поиск" aria-label="Поиск">
                <button class="btn btn-outline-success" type="submit">Поиск</button>
            </form>
        </div>
    </div>
</nav>
