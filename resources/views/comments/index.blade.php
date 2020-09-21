@extends('layouts.layout')

@section('content')
    <div class="row">
        @foreach($comments as $comment)
        <div class="col-6">
            <div class="card">
                <div class="card-header"><h2>{{ $comment->title }}</h2></div>
                <div class="card-body">
                    <div class="card-img" style="background-image: url({{ $comment->img ?? asset('images/default.jpg') }})"></div>
                    <div class="card-author">Автор: {{ $comment->fio }}</div>
                    <a href="{{ route('comment.show', ['id'=>$comment->id]) }}" class="btn btn-outline-primary">Посмотреть отзыв</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @if(!isset($_GET['var']))
        {{ $comments->links() }}
    @endif
@endsection