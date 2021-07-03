@extends('layouts.app')

@section('content')
    <h2 class="mb-4">新しいスレッドを作成</h2>

    <form action="{{ route('threads.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="thread_title">タイトル</label>
            <input type="text" name="thread[title]" id="thread_title" class="form-control" value="{{ old('thread.title') ?? $thread->title }}">
        </div>
        <div class="form-group">
            <label for="content">内容</label>
            <textarea name="post[content]" id="content" rows="10" class="form-control">{{ old('post.content') ?? $post->content }}</textarea>
        </div>
        <div class="form-group">
            <label for="image">画像</label>
            <div class="mb-2">
                <img id="preview" src="{{ asset('images/image_placeholder.png') }}" class="img-thumbnail img-preview">
            </div>
            <input type="file" accept="image/jpeg,image/png,image/gif" name="post[image]" id="image" data-img-tag-id="#preview" class="form-control-file">
        </div>

        <button type="submit" class="btn btn-primary">作成する</button>
        <a href="{{ route('threads.index') }}" role="button" class="btn btn-secondary">戻る</a>
    </form>
@endsection
