@php
if (!isset($items)) {
    $items = [];
}

if (!isset($show_search)) {
    $show_search = 0;
}

//Можно выводить разные brand на разных размерах экрана
if (!isset($brand)) {
    $brand = '';
}

if (!isset($brand_xs)) {
    $brand_xs = $brand;
}

if (!isset($brand_sm)) {
    $brand_sm = $brand;
}

if (!isset($brand_md)) {
    $brand_md = $brand;
}

if (!isset($brand_lg)) {
    $brand_lg = $brand;
}

if (!isset($brand_xl)) {
    $brand_xl = $brand;
}
//показывать ли секцию brand, не будет показана если не указан brand и brand_logo
if (!isset($show_brand)) {
    $show_brand = 1;
}
//логотип. По умолчанию пиктограма, похожая на герб
if (!isset($brand_logo)) {
    $brand_logo = '';
}
//Когда нечего выводить в секцию brand - убираем ее
if (empty($brand) && empty($brand_logo)) {
    $show_brand = 0;
}
//имя поля поиска, как наывается параметр поисковой строки при проведении поиска
if (!isset($search_param)) {
    $search_param = 'search';
}
//Подсказка для поискового запроса
if (!isset($search_placeholder)) {
    $search_placeholder = 'введите запрос';
}
//ссылка на поиск
if (!isset($search_url)) {
    $search_url = '/search';
}
//Класс для тега nav
if (!isset($nav_class)) {
    $nav_class = 'navbar-light bg-light';
}
//navbar id
if (!isset($nav_id)) {
    $nav_id = 'mainNavbar';
}

if (!isset($logo_width) && !isset($logo_height)) {
    $logo_width = '30px';
    $logo_height = 'auto';
}

if (isset($logo_width) && !isset($logo_height)) {
    $logo_height = 'auto';
}

if (!isset($logo_width) && isset($logo_height)) {
    $logo_width = 'auto';
}

if (!isset($id)) {
    $id = 'nav';
}
@endphp

<nav id="{{ $id }}" class="navbar navbar-expand-lg {{ $nav_class }}">
    @if ($show_brand)
        <a class="navbar-brand" href="/">
            @if ($brand_logo)
                <img src="{{ $brand_logo }}" style="height:{{ $logo_height }}; width:{{ $logo_width }};"
                    class="d-inline-block align-left mr-2" alt="{{ $brand }}">
            @endif

            @if (isset($brand_xs) && !empty($brand_xs))
                <span class="d-sm-none d-inline">
                    {{ $brand_xs }}
                </span>
            @endif

            @if (isset($brand_sm) && !empty($brand_sm))
                <span class="d-none d-sm-inline d-md-none">
                    {{ $brand_sm }}
                </span>
            @endif

            @if (isset($brand_md) && !empty($brand_md))
                <span class="d-none d-md-inline d-lg-none">
                    {{ $brand_md }}
                </span>
            @endif

            @if (isset($brand_lg) && !empty($brand_lg))
                <span class="d-none d-lg-inline d-xl-none" style="font-size: auto;">
                    {{ $brand_lg }}
                </span>
            @endif

            @if (isset($brand_xl) && !empty($brand_xl))
                <span class="d-none d-xl-inline">
                    {{ $brand_xl }}
                </span>
            @endif
        </a>
    @endif
    @if (sizeof($items) > 0)
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#{{ $nav_id }}"
            aria-controls="{{ $nav_id }}" aria-expanded="false" aria-label="Переключить меню">
            <span class="navbar-toggler-icon">
            </span>
        </button>
    @endif

    <div class="collapse navbar-collapse" id="{{ $nav_id }}">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            @component('component.navitem', ['items' => $items])
            @endcomponent
        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                @if (Route::has('login'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                @endif

                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/profile">
                            {{ __('Profile') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>

        @if ($show_search)
            <form class="form-inline my-2 my-lg-0" method="GET" action="{{ $search_url }}">
                <input class="form-control mr-sm-2" type="text" name="{{ $search_param }}"
                    placeholder="{{ $search_placeholder }}">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Найти</button>
            </form>
        @endif
    </div>
</nav>
