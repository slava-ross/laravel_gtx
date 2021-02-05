@extends('layouts.layout', [compact('title')])

@section('content')

    @if($comments->isEmpty())
        @if(isset($cityName))
            <h1 class="mx-auto">Нет отзывов по городу "<?=htmlspecialchars($cityName)?>"</h1>
        @endif
    @else
        <h1 class="mx-auto text-center">{{ $title }}</h1>
        <div class="row">
            @foreach($comments as $comment)
            <div class="col-6">
                <div class="card">
                    <div class="card-header"><h2>{{ Str::length($comment->title) > 30 ? Str::substr($comment->title, 0, 30) . '...' : $comment->title }}</h2></div>
                    <div class="card-body">
                        <div class="card-img mb-1 img-fluid" style="background-image: url({{ empty($comment->img) ? asset('images/default.png') : asset($comment->img) }})"></div>
                        @if(isset($fio))
                            <div class="card-city mb-1"><span class="font-weight-bold">Город(а):</span> {{ $comment->city_names }}</div>
                        @endif
                        <div class="card-descr mb-1"><span class="font-weight-bold">Отзыв:</span> {{ Str::length($comment->comment_text) > 30 ? Str::substr($comment->comment_text, 0, 30) . '...' : $comment->comment_text }}</div>
                        <div class="card-author mb-1"><span class="font-weight-bold">Автор:</span> {{ $comment->fio }}</div>
                        <div class="card-rating mb-1"><span class="font-weight-bold">Рейтинг:</span> {{ $comment->rating }}</div>
                        <a href="{{ route('comment.show', ['comment'=>$comment->id]) }}" class="btn btn-outline-primary">Посмотреть отзыв</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row links d-flex justify-content-center">
            {{ $comments->appends(request()->query())->links() }}
        </div>
    @endif
@endsection
