@extends('layouts.layout', ['title' => "Отзывы по городу $cityName"])

@section('content')
    @if(isset($city))
        @if(count($comments) == 0)
            <h2>Нет отзывов городу "<?=htmlspecialchars($city)?>".</h2>
        @endif
    @endif
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
    @if(!isset($city))
        {{ $comments->links() }}
    @endif
@endsection
