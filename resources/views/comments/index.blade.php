@extends('layouts.layout', [compact('title')])

@section('content')

    @if($comments->isEmpty())
        @if(isset($cityName))
            <h2>Нет отзывов по городу "<?=htmlspecialchars($cityName)?>"</h2>
        @endif
        @if(isset($fio))
            <h2>У автора "<?=htmlspecialchars($fio)?> нет отзывов"</h2>
        @endif
    @else
        <div class="row">
            @foreach($comments as $comment)
            <div class="col-6">
                <div class="card">
                    <div class="card-header"><h2>{{ Str::length($comment->title) > 30 ? Str::substr($comment->title, 0, 30) . '...' : $comment->title }}</h2></div>
                    <div class="card-body">
                        <div class="card-img" style="background-image: url({{ $comment->img ?? asset('images/default.jpg') }})"></div>
                        <div class="card-descr"><span>Отзыв:</span> {{ Str::length($comment->comment_text) > 30 ? Str::substr($comment->comment_text, 0, 30) . '...' : $comment->comment_text }}</div>
                        <div class="card-author"><span>Автор:</span> {{ $comment->fio }}</div>
                        <div class="card-rating"><span>Рейтинг:</span> {{ $comment->rating }}</div>
                        <a href="{{ route('comment.show', ['comment'=>$comment->id]) }}" class="btn btn-outline-primary">Посмотреть отзыв</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        {{ $comments->appends(request()->query())->links() }}
    @endif
@endsection
