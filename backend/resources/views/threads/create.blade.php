@extends('layouts.app')

@section('content')
    <h2 class="mb-4">新しいスレッドを作成</h2>

    <form action="{{ route('threads.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="thread_title">タイトル</label>
            <input type="text" name="thread[title]" id="thread_title" class="form-control" value="{{ old('thread.title') ?? $thread->title }}">
        </div>

        @include('shared.post-form', ['post' => $post])

        <button type="submit" class="btn btn-primary">作成する</button>
        <a href="{{ route('threads.index') }}" role="button" class="btn btn-secondary">戻る</a>
    </form>
@endsection
