@extends('layouts.app')

@section('content')
    <h2 class="mb-4">新しいスレッドを作成</h2>

    <form action="{{ route('threads.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        @include('threads.partials.thread-form', ['thread' => $thread])
        @include('posts.partials.post-form', ['post' => $post])

        <button type="submit" class="btn btn-primary">作成する</button>
        <a href="{{ route('threads.index') }}" role="button" class="btn btn-secondary">戻る</a>
    </form>
@endsection
