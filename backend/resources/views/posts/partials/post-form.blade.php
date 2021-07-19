<div class="form-group">
    <label for="content">内容</label>
    <textarea name="post[content]" id="content" rows="10" class="form-control">{{ old('post.content') ?? $post->content }}</textarea>
</div>
<div class="form-group">
    <label for="image">画像</label>
    <div class="mb-2">
        @if (isset($post->image_path))
            <img id="preview" src="{{ asset('storage/' . $post->image_path) }}" class="img-thumbnail img-preview">
        @else
            <img id="preview" src="{{ asset('images/image_placeholder.png') }}" class="img-thumbnail img-preview">
        @endif
    </div>
    <div class="input-group">
        <div class="custom-file">
            <input type="file" accept="image/jpeg,image/png,image/gif" name="post[image]" class="custom-file-input" id="image" data-img-tag-id="#preview" >
            <label class="custom-file-label" for="image" data-browse="参照">パソコンからファイルを選択</label>
        </div>
    </div>
</div>
