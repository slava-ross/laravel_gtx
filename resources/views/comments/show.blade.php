@extends('layouts.layout', ['title' => "Отзыв №$comment->id . $comment->title"])
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h2>{{ $comment->title }}</h2></div>
                <div class="card-body">
                    <div class="card-img card-img__max" style="background-image: url({{ $comment->img ?? asset('images/default.jpg')}})"></div>
                    <div class="card-descr"><b>Текст отзыва:</b> {{ $comment->comment_text }}</div>
                    <div class="card-author"><b>Автор:</b> {{ $comment->fio }}</div>
                    <div class="card-date"><b>Отзыв создан:</b> {{ $comment->created_at->diffForHumans() }}</div>
                    <div class="card-btn">
                        <a href="{{ route('comment.index') }}" class="btn btn-outline-primary">На главную</a>
                        <a href="#" class="btn btn-outline-success">Редактировать</a>
                        <form action="#" method="post" onsubmit="if (confirm('Точно удалить отзыв?')) { return true } else { return false }">
                            @csrf
                            @method('DELETE')
                            <input type="submit" class="btn btn-outline-danger" value="Удалить">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
