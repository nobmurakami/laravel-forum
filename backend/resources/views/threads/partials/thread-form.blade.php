<div class="form-group">
    <label for="thread_title">タイトル</label>
    <input type="text" name="thread[title]" id="thread_title" class="form-control" value="{{ old('thread.title') ?? $thread->title }}">
</div>
