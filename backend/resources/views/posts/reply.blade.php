@extends('layouts.app')

@section('content')
    @include('shared.thread-header', ['thread' => $thread])

    <h2 class="h4 font-weight-normal mt-4">{{ $replyTo->user->name }}さんの投稿にコメント</h2>
    <section class="reply-to card shadow-sm mb-4">
        <div class="d-flex flex-column card-body">
            <h6 class="font-weight-normal card-subtitle text-muted">
                @include('shared.post-created-at', ['post' => $replyTo])
            </h6>

            @include('shared.reply-to-text', ['post' => $replyTo])

            <p class="card-text mt-2 mb-0">
                {{ safeBr($replyTo->content) }}
            </p>

            {{-- 画像 --}}
            @include('shared.post-card-image', ['post' => $replyTo])
        </div>
    </section>

    <form action="{{ route('threads.posts.store', $thread) }}" method="post" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="post[reply_to_id]" value="{{ $replyTo->id }}">

        @include('posts.partials.post-form', ['post' => $post])

        <button type="submit" class="btn btn-primary">コメントする</button>
        <a href="{{ route('threads.show', $thread) }}" role="button" class="btn btn-secondary">戻る</a>
    </form>
@endsection
