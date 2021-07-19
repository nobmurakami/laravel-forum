@extends('layouts.app')

@section('content')
    @include('shared.thread-header', ['thread' => $thread])

    <h2 class="h4 font-weight-normal mt-4 mb-4">スレッドに投稿</h2>

    <form action="{{ route('threads.posts.store', $thread) }}" method="post" enctype="multipart/form-data">
        @csrf

        @include('posts.partials.post-form', ['post' => $post])

        <button type="submit" class="btn btn-primary">投稿する</button>
        <a href="{{ route('threads.show', $thread) }}" role="button" class="btn btn-secondary">戻る</a>
    </form>
@endsection
