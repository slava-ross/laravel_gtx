@extends('layouts.layout', ['title' => "Отзыв №$comment->id. $comment->title"])
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h3 id="title">{{ $comment->title }}</h3></div>
                <div class="card-body">
                    <div class="card-img card-img__max mb-1 img-fluid" style="background-image: url({{ empty($comment->img) ? asset('images/default.jpg') : asset($comment->img) }})"></div>
                    <div class="card-descr mb-1"><span class="font-weight-bold">Отзыв: </span>{{ $comment->comment_text }}</div>
                    <div class="card-author mb-1">
                        <span class="font-weight-bold">Автор:</span>
                        @guest()
                            {{ $user->fio }}
                        @endguest
                        @auth()
                            <a class="nav-link d-inline-block author-info" href="#">{{ $user->fio }}</a>
                        @endauth
                    </div>
                    <div class="card-rating mb-1"><span class="font-weight-bold">Рейтинг: </span>{{ $comment->rating }}</div>
                    <div class="card-date mb-1"><span class="font-weight-bold">Отзыв создан: </span>{{ $comment->created_at->diffForHumans() }}</div>
                    <div class="card-btn">
                        <a href="{{ route('/') }}" class="btn btn-outline-primary">Назад</a>
                        @auth
                            @if (Auth::user()->id == $comment->user_id)

                                <a id="edit-comment" data-attr="{{ route('comment.edit', ['comment'=>$comment->id]) }}" class="btn btn-outline-success">Редактировать</a>
                                @csrf
                                <input type="submit" id="delete-comment" class="btn btn-outline-danger delete-comment" data-id="{{ $comment->id }}" value="Удалить">
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
    @auth()
        <div class="modal fade" id="author-modal" tabindex="-1" role="dialog" aria-labelledby="authorModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="author-modal-label">Автор: {{ $user->fio }}</h5>
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
