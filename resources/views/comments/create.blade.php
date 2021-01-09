<form id="create-comment-form" data-attr="{{ route('comment.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    @include('comments.parts.form')
</form>

