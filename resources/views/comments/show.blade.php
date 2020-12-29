@extends('layouts.layout', ['title' => "Отзыв №$comment->id. $comment->title"])
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h2>{{ $comment->title }}</h2></div>
                <div class="card-body">
                    <div class="card-img card-img__max mb-1" style="background-image: url({{ $comment->img ?? asset('images/default.jpg')}})"></div>
                    <div class="card-descr mb-1"><span class="font-weight-bold">Отзыв:</span> {{ $comment->comment_text }}</div>
                    <div class="card-author mb-1">
                        <span class="font-weight-bold">Автор:</span>
                        @guest()
                            {{ $user->fio }}
                        @endguest
                        @auth()
                            <a class="nav-link d-inline-block author-info" href="#">{{ $user->fio }}</a>
                        @endauth
                    </div>
                    <div class="card-rating mb-1"><span class="font-weight-bold">Рейтинг:</span> {{ $comment->rating }}</div>
                    <div class="card-date mb-1"><span class="font-weight-bold">Отзыв создан:</span> {{ $comment->created_at->diffForHumans() }}</div>
                    <div class="card-btn">
                        <a href="{{ route('/') }}" class="btn btn-outline-primary">На главную</a>
                        @auth
                            @if (Auth::user()->id == $comment->user_id)

                                <a href="{{ route('comment.edit', ['comment'=>$comment->id]) }}" class="btn btn-outline-success">Редактировать</a>

                                {{-- Без Ajax --
                                <form action="{{ route('comment.destroy', ['comment'=>$comment->id]) }}" method="POST" onsubmit="if (confirm('Точно удалить отзыв?')) { return true } else { return false }">
                                    @csrf
                                    @method('DELETE')
                                    <input type="submit" class="btn btn-outline-danger" value="Удалить">
                                </form>
                                --}}

                                <form action="{{ route('comment.destroy', $comment->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="submit" id="delete-comment" class="btn btn-outline-danger delete-comment" data-id="{{ $comment->id }}" value="Удалить">
                                </form>

                                    {{--<form action="{{ route('comment.destroy', $comment->id) }}" method="POST">

                                        <a data-toggle="modal" id="smallButton" data-target="#smallModal"
                                           data-attr="{{ route('comment.show', $comment->id) }}" title="show">
                                            <i class="fas fa-eye text-success  fa-lg"></i>
                                        </a>

                                        <a class="text-secondary" data-toggle="modal" id="mediumButton" data-target="#mediumModal"
                                           data-attr="{{ route('comment.edit', $comment->id) }}">
                                            <i class="fas fa-edit text-gray-300"></i>
                                        </a>
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" title="delete" style="border: none; background-color:transparent;">
                                            <i class="fas fa-trash fa-lg text-danger"></i>
                                        </button>
                                    </form>--}}




                                {{--<!-- small modal -->
                                <div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-sm" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" id="smallBody">
                                                <div>
                                                    <!-- the result to be displayed apply here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>--}}

                                {{--<!-- medium modal -->
                                <div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" id="mediumBody">
                                                <div>
                                                    <!-- the result to be displayed apply here -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>--}}



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
