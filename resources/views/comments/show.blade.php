@extends('layouts.layout', ['title' => "Отзыв №$comment->id. $comment->title"])
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header"><h2>{{ $comment->title }}</h2></div>
                <div class="card-body">
                    <div class="card-img card-img__max" style="background-image: url({{ $comment->img ?? asset('images/default.jpg')}})"></div>
                    <div class="card-descr"><span>Отзыв:</span> {{ $comment->comment_text }}</div>
                    <div class="card-author"><span>Автор:</span> {{ $user->fio }}</div>
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
