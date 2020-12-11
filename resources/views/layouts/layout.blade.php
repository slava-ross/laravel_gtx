<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css">
    <!--link href="{{ asset('css/autocomplete.css') }}" rel="stylesheet" type="text/css"-->
    <!--link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"-->
    <!--link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/css/suggestions.min.css" rel="stylesheet" /-->
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('images/favicon.png') }}" rel="shortcut icon" type="image/x-icon">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="col-6 navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/">Главная<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active offset-3">
                <a class="nav-link" href="{{ route('comment.create') }}">Создать отзыв<span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Войти') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Зарегистрироваться') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->fio }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Выход') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
</nav>

<div class="container">
    @include('layouts.parts.flashes')
    @yield('content')
</div>

@yield('modal')

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
<!--script src="{{ asset('js/autocomplete.js') }}"></script-->
<!--script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script-->
<!--script src="{{ asset('js/select2.min.js') }}"></script-->
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script-->
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/js/jquery.suggestions.min.js"></script>
</body>
</html>
