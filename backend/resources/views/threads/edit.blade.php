@extends('layouts.app')

@section('content')
    <h2 class="mb-4">スレッドタイトルの編集</h2>

    <form action="{{ route('threads.update', $thread) }}" method="post">
        @csrf
        @method('PUT')

        @include('threads.partials.thread-form', ['thread' => $thread])

        <button type="submit" class="btn btn-primary">更新する</button>
        <a href="{{ route('threads.show', $thread) }}" role="button" class="btn btn-secondary">戻る</a>
    </form>
@endsection
