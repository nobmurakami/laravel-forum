@extends('layouts.app')

@section('content')
@include('shared.thread-header', ['thread' => $thread])

@if (Auth::guard('user')->check())
<div class="d-sm-flex justify-content-between align-items-start mt-4">
    {{-- 投稿ボタン --}}
    <div class="mb-2 mb-sm-n2">
        <a href="{{ route('threads.posts.create', $thread) }}" role="button" class="btn btn-primary">投稿する</a>
    </div>

    {{-- スレッドアクション --}}
    <div class="d-flex">
        {{-- スレッドタイトル編集ボタン --}}
        <form action="{{ route('threads.edit', $thread) }}" method="get">
            <button type="submit" class="btn btn-outline-secondary btn-sm">スレッドタイトルの編集</button>
        </form>

        {{-- スレッド削除ボタン --}}
        <form action="{{ route('threads.destroy', $thread) }}" method="post">
            @csrf
            @method('delete')
            <button type="button" class="btn btn-outline-secondary btn-sm ml-2" data-toggle="modal" data-target="#deleteThread">
                スレッドの削除
            </button>
            @include('shared.modal', ['id' => 'deleteThread', 'title' => '確認', 'body' => '削除しますか？'])
        </form>

    </div>
</div>
@endif

<div class="mt-4 post-index">
    @if ($posts->isNotEmpty())
    @foreach ($posts as $post)
    <section class="post-card card shadow-sm mb-2">
        <div class="d-flex flex-column card-body">
            <a id="post_{{ $post->id }}" class="anchor"></a>

            <div class="d-flex justify-content-between">
                <h5 class="card-title">{{ $post->user->name }}</h5>

                {{-- 投稿アクションメニュー --}}
                <div class="dropdown float-right post-action ml-2">
                    <button class="btn btn-outline-secondary btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-caret-down fa-lg"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        @if (Auth::guard('user')->check())
                        <form action="{{ route('posts.edit', $post) }}" method="get">
                            <button type="submit" class="dropdown-item">編集</button>
                        </form>
                        @endif

                        @if (Auth::guard('user')->check())
                        <form action="{{ route('posts.destroy', $post) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="dropdown-item" onclick='return confirm("削除しますか？");'>削除</button>
                        </form>
                        @endif

                        @if (Auth::guard('user')->check())
                        <form action="{{ route('posts.image.destroy', $post) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="dropdown-item" onclick='return confirm("画像を削除しますか？");'>画像の削除</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <h6 class="font-weight-normal card-subtitle text-muted align-items-center">
                {{ $post->created_at }}{{ ($post->updated_at > $post->created_at) ? '（編集済）' : '' }}
            </h6>

            {{-- 返信先の投稿 --}}
            @if (isset($post->reply_to_id))
            <div class="align-items-center">
                <a href="#post_{{ $post->replyTo->id }}" role="button" data-toggle="popover" data-content="@include('threads.partials.reply-to-popover')">{{ $post->replyTo->user->name }}</a><span class="text-muted">さんの投稿へのコメント</span>
            </div>
            @endif

            {{-- 本文 --}}
            <p class="card-text mt-2">
                {{ safeBr($post->content) }}
            </p>

            {{-- 画像 --}}
            @if (isset($post->image_path))
            <figure class="figure">
                <img src="{{ asset('storage/' . $post->image_path) }}" class="img-thumbnail post-image">
            </figure>
            @endif

            {{-- コメント --}}
            <div class="d-flex align-items-center">
                @if ($post->replies->isNotEmpty())
                <button class="btn btn-link text-decoration-none py-0 px-1 mr-2" data-toggle="popover" title="この投稿へのコメント（{{ $post->replies->count() }}件）" data-content="@include('threads.partials.replies-popover')"><i class="far fa-comment"></i>&nbsp;{{ $post->replies->count() }}</button>
                @endif

                @if (Auth::guard('user')->check())
                <a href="{{ route('posts.reply', $post) }}" role="button" class="btn btn-outline-primary btn-sm font-weight-bold comment-btn">コメントする</a>
                @endif
            </div>

            {{-- 投稿のURL取得用リンク --}}
            <div class="post-link">
                <a href="#post_{{ $post->id }}" class="text-muted">
                    <i class="fas fa-hashtag fa-lg"></i>
                </a>
            </div>
        </div>
    </section>
    @endforeach
    @else
    <p>まだ投稿がありません。</p>
    @endif
</div>

@if (Auth::guard('user')->check())
<div class="container post-btn">
    <a href="{{ route('threads.posts.create', $thread) }}" role="button" class="btn btn-primary d-flex flex-column justify-content-center">
        <div class=""><i class="fas fa-pen fa-2x"></i></div>
        <div>投稿</div>
    </a>
</div>
@endif

@endsection
