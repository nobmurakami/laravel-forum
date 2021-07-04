@extends('layouts.app')

@section('content')
    @include('shared.thread-header', ['thread' => $thread])

    <h2 class="h4 font-weight-normal mt-4">{{ $replyTo->user->name }}さんの投稿にコメント</h2>
    <section class="card shadow-sm mb-4">
        <div class="d-flex flex-column card-body">
            <h6 class="font-weight-normal card-subtitle mb-2 text-muted">
                {{ $replyTo->created_at }}{{ ($replyTo->updated_at > $replyTo->created_at) ? '（編集済）' : '' }}
            </h6>
            <p class="card-text mt-2">
                {{ safeBr($replyTo->content) }}
            </p>

            {{-- 画像 --}}
            @if (isset($replyTo->image_path))
                <figure class="figure">
                    <img src="{{ asset('storage/' . $replyTo->image_path) }}" class="img-thumbnail reply-to-image">
                </figure>
            @endif

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
