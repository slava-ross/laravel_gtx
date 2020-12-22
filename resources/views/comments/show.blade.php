@extends('layouts.layout', ['title' => "Отзыв №$comment->id. $comment->title"])
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header"><h2>{{ $comment->title }}</h2></div>
                <div class="card-body">
                    <div class="card-img card-img__max" style="background-image: url({{ $comment->img ?? asset('images/default.jpg')}})"></div>
                    <div class="card-descr"><span>Отзыв:</span> {{ $comment->comment_text }}</div>
                    @guest()
                        <div class="card-author"><span>Автор:</span> {{ $user->fio }}</div>
                    @endguest
                    @auth()
                        <div class="card-author">
                            <span>Автор:</span>
                            <a class="nav-link d-inline-block author-info" href="#">{{ $user->fio }}</a>
                        </div>
                    @endauth
                    <div class="card-rating"><span>Рейтинг:</span> {{ $comment->rating }}</div>
                    <div class="card-date"><span>Отзыв создан:</span> {{ $comment->created_at->diffForHumans() }}</div>
                    <div class="card-btn">
                        <a href="{{ route('/') }}" class="btn btn-outline-primary">На главную</a>
                        @auth
                            @if (Auth::user()->id == $comment->user_id)
                                <a href="{{ route('comment.edit', ['comment'=>$comment->id]) }}" class="btn btn-outline-success">Редактировать</a>
                                <form action="{{ route('comment.destroy', ['comment'=>$comment->id]) }}" method="post" onsubmit="if (confirm('Точно удалить отзыв?')) { return true } else { return false }">
                                    @csrf
                                    @method('DELETE')
                                    <input type="submit" class="btn btn-outline-danger" value="Удалить">
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Модальное окно информации об авторе --}}
@section('modal')
    {{--
        <div id="cityModal" class="modal fade">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        Ваш город: <span>{{ $city_name }}</span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="city-confirm" class="btn btn-primary">Да</button>
                        <button type="button" id="city-another" class="btn btn-secondary" data-dismiss="modal">Выбрать другой</button>
                    </div>
                </div>
            </div>
        </div>
    --}}
    @auth()
        <div class="modal fade" id="authorModal" tabindex="-1" role="dialog" aria-labelledby="authorModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>Автор: {{ $user->fio }}</div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="author-modal-body">
                        <div class="card-body">
                            <div class="card-email"><span>E-mail:</span> {{ $user->email }}</div>
                            <div class="card-phone"><span>Phone:</span> {{ $user->phone }}</div>
                            <a class="nav-link author-comments" href="{{ route('comment.author', ['id'=>$user->id]) }}">Посмотреть все отзывы автора</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth
@endsection
