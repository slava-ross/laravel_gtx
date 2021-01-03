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
    <link href="{{ asset('images/favicon.png') }}" rel="shortcut icon" type="image/x-icon">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="col-6 navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/">Главная</a>
            </li>
            <li class="nav-item active offset-3">
                <a class="nav-link create-comment" href="#">Создать отзыв</a>
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

<div class="container container-main">
    @include('layouts.parts.flashes')
    @yield('content')
</div>

@yield('modal')

{{-- Модальное окно создания нового отзыва --}}

<!-- button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Large modal</button -->

<div class="modal fade" id="create-comment-modal" tabindex="-1" role="dialog" aria-labelledby="createCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="create-comment-modal-label">Новый отзыв</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="create-comment-modal-body">
                {{-- Заголовок отзыва --}}
                <div class="form-group">
                    <label for="title">Заголовок:</label>
                    <input name="title" id="title" type="text" class="form-control" required">
                </div>
                {{-- Текст отзыва --}}
                <div class="form-group">
                    <label for="comment_text">Текст отзыва:</label>
                    <textarea name="comment_text" id="comment_text" rows="10" class="form-control" required></textarea>
                </div>
                {{-- Города/города отзыва --}}
                <div class="form-group city-holder">
                    <ul id="city-shell" class="city-shell"></ul>
                </div>
                <div class="form-group">
                    <label for="cities-data">Выберите один или несколько городов (Нет выбора - все города):</label>
                    <input id="cities-data" class="city-multiple form-control" type="search" placeholder="Начните писать ваш город ...">
                    <select id="city-select" class="city-select" name="cities[]" multiple hidden>
                    </select>
                </div>
                {{-- Рейтинг --}}
                <div class="form-group">
                    <label for="rating">Рейтинг (1-5):</label>
                    <input name="rating" id="rating" type="number" min="1" max="5" class="form-control" required">
                </div>
                {{-- Изображение --}}
                <div class="form-group">
                    <label for="image">Прикрепите изображение (по желанию):</label><br>
                    <input name="img" id="image" type="file">
                </div>
            </div>
            <div class="modal-footer">
                @csrf
                <button type="button" id="new-comment-create" class="btn btn-outline-success">Создать отзыв</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/js/jquery.suggestions.min.js"></script>
</body>
</html>
