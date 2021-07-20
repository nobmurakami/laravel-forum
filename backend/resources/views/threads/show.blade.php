@extends('layouts.app')

@section('content')
    @include('shared.thread-header', ['thread' => $thread])

    @if (Auth::check())
        <div class="d-sm-flex justify-content-between align-items-start mt-3">
            {{-- 投稿ボタン --}}
            <div class="mb-2 mb-sm-n2">
                <a href="{{ route('threads.posts.create', $thread) }}" role="button" class="btn btn-primary">投稿する</a>
            </div>

            {{-- スレッドアクションメニュー --}}
            @include('threads.partials.thread-action-menu', ['thread' => $thread])
        </div>
    @endif

    <div class="mt-4 post-index">
        @if ($posts->isNotEmpty())
            @foreach ($posts as $i => $post)
                <a id="p{{ $post->id }}" class="anchor"></a>

                <section class="post-card card shadow-sm mb-2">
                    <div class="d-flex flex-column card-body">
                        @if ($post->trashed())
                            <p class="text-muted mb-0">削除された投稿</p>
                        @else
                            <div class="d-flex justify-content-between">
                                {{-- 投稿ユーザー名 --}}
                                <h5 class="card-title">{{ $post->user->name }}</h5>

                                {{-- 投稿アクションメニュー --}}
                                @include('shared.post-action-menu', ['post' => $post])
                            </div>

                            {{-- 投稿日時 --}}
                            <div class="card-subtitle text-muted">
                                @include('shared.post-created-at', ['post' => $post])
                            </div>

                            {{-- 返信先の投稿 --}}
                            @include('shared.reply-to-text', ['post' => $post])

                            {{-- 本文 --}}
                            <p class="card-text mt-2 mb-0">{{ safeBr($post->content) }}</p>

                            {{-- 画像 --}}
                            @include('shared.post-card-image', ['post' => $post])

                            {{-- コメント --}}
                            <div class="d-flex align-items-center">
                                @if ($post->replies->isNotEmpty())
                                    <button class="btn btn-link text-decoration-none py-0 px-1 mt-3 mr-2" data-toggle="popover" title="この投稿へのコメント（{{ $post->replies->count() }}件）" data-content="@include('threads.partials.replies-popover')"><i class="far fa-comment"></i>&nbsp;{{ $post->replies->count() }}</button>
                                @endif

                                @if (Auth::check())
                                    <a href="{{ route('posts.reply', $post) }}" role="button" class="btn btn-outline-secondary btn-sm mt-3">コメントする</a>
                                @endif
                            </div>
                        @endif

                        {{-- 投稿のURL取得用リンク --}}
                        <div class="post-link">
                            <a href="#p{{ $post->id }}" class="text-muted text-decoration-none">
                                #{{ $i + 1 }}
                            </a>
                        </div>
                    </div>
                </section>
            @endforeach
        @else
            <p>まだ投稿がありません。</p>
        @endif
    </div>

    @if (Auth::check())
        <div class="container post-btn">
            <a href="{{ route('threads.posts.create', $thread) }}" role="button" class="btn btn-primary d-flex flex-column justify-content-center">
                <div class=""><i class="fas fa-pen fa-2x"></i></div>
                <div>投稿</div>
            </a>
        </div>
    @endif

@endsection
