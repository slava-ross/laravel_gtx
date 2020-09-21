<div class="form-group">
    <input name="title" type="text" class="form-control" required value="{{ old('title') ?? $comment->title ?? '' }}">
</div>
<div class="form-group">
    <textarea name="comment_text" rows="10" class="form-control" required>{{ old('descr') ?? $comment->comment_text ?? '' }}</textarea>
</div>
<div class="form-group">
    <input name="img" type="file">
</div>
