@extends('layouts.app')

@section('content')
    @include('shared.thread-header', ['thread' => $thread])

    @if (Auth::guard('user')->check())
        <div class="d-sm-flex justify-content-between align-items-start mt-3">
            {{-- 投稿ボタン --}}
            <div class="mb-2 mb-sm-n2">
                <a href="{{ route('threads.posts.create', $thread) }}" role="button" class="btn btn-primary">投稿する</a>
            </div>

            {{-- スレッドアクション --}}
            <div class="d-flex">
                {{-- スレッドタイトル編集ボタン --}}
                @can('update', $thread)
                    <form action="{{ route('threads.edit', $thread) }}" method="get">
                        <button type="submit" class="btn btn-outline-secondary btn-sm">スレッドタイトルの編集</button>
                    </form>
                @endcan

                {{-- スレッド削除ボタン --}}
                @can('delete', $thread)
                    <button type="button" class="btn btn-outline-secondary btn-sm ml-2" data-toggle="modal" data-target="#deleteThread">
                        スレッドの削除
                    </button>
                    @include('shared.delete-modal', ['id' => 'deleteThread', 'title' => '確認', 'body' => 'スレッドを削除しますか？', 'routeName' => 'threads.destroy', 'model' => $thread])
                @endcan
            </div>
        </div>
    @endif

    <div class="mt-4 post-index">
        @if ($posts->isNotEmpty())
            @foreach ($posts as $i => $post)
                <a id="post_{{ $post->id }}" class="anchor"></a>

                <section class="post-card card shadow-sm mb-2">
                    <div class="d-flex flex-column card-body">
                        @if ($post->trashed())
                            <p class="text-muted mb-0">削除された投稿</p>
                        @else

                            <div class="d-flex justify-content-between">
                                <h5 class="card-title">{{ $post->user->name }}</h5>

                                {{-- 投稿アクションメニュー --}}
                                @canany(['update', 'delete'], $post)
                                    <div class="dropdown float-right post-action ml-2">
                                        <button class="btn btn-link text-muted" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            @can('update', $post)
                                                <form action="{{ route('posts.edit', $post) }}" method="get">
                                                    <button type="submit" class="dropdown-item">編集</button>
                                                </form>
                                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#deleteImage_{{ $post->id }}">
                                                    画像の削除
                                                </button>
                                            @endcan

                                            @can('delete', $post)
                                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#deletePost_{{ $post->id }}">
                                                    削除
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                    @include('shared.delete-modal', ['id' => 'deleteImage_' . $post->id, 'title' => '確認', 'body' => '画像を削除しますか？', 'routeName' => 'posts.image.destroy', 'model' => $post])
                                    @include('shared.delete-modal', ['id' => 'deletePost_' . $post->id, 'title' => '確認', 'body' => '投稿を削除しますか？', 'routeName' => 'posts.destroy', 'model' => $post])
                                @endcanany
                            </div>

                            <h6 class="font-weight-normal card-subtitle text-muted align-items-center">
                                {{ $post->created_at }}{{ ($post->updated_at > $post->created_at) ? '（編集済）' : '' }}
                            </h6>

                            {{-- 返信先の投稿 --}}
                            @if (isset($post->replyTo))
                                @if ($post->replyTo->trashed())
                                    <span class="text-muted">削除された投稿へのコメント</span>
                                @else
                                    <div class="align-items-center">
                                        <a href="#post_{{ $post->replyTo->id }}" role="button" data-toggle="popover" data-content="@include('threads.partials.reply-to-popover')">
                                            {{ $post->replyTo->user->name }}
                                        </a>
                                        <span class="text-muted">さんの投稿へのコメント</span>
                                    </div>
                                @endif
                            @endif

                            {{-- 本文 --}}
                            <p class="card-text mt-2 mb-0">
                                {{ safeBr($post->content) }}
                            </p>

                            {{-- 画像 --}}
                            @if (isset($post->image_path))
                                <figure class="figure mt-2 mb-0">
                                    <img src="{{ asset('storage/' . $post->image_path) }}" class="img-thumbnail post-image">
                                </figure>
                            @endif

                            {{-- コメント --}}
                            <div class="d-flex align-items-center">
                                @if ($post->replies->isNotEmpty())
                                    <button class="btn btn-link text-decoration-none py-0 px-1 mt-3 mr-2" data-toggle="popover" title="この投稿へのコメント（{{ $post->replies->count() }}件）" data-content="@include('threads.partials.replies-popover')"><i class="far fa-comment"></i>&nbsp;{{ $post->replies->count() }}</button>
                                @endif

                                @if (Auth::guard('user')->check())
                                    <a href="{{ route('posts.reply', $post) }}" role="button" class="btn btn-outline-secondary btn-sm mt-3">コメントする</a>
                                @endif
                            </div>
                        @endif

                        {{-- 投稿のURL取得用リンク --}}
                        <div class="post-link">
                            <a href="#post_{{ $post->id }}" class="text-muted text-decoration-none">
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

    @if (Auth::guard('user')->check())
        <div class="container post-btn">
            <a href="{{ route('threads.posts.create', $thread) }}" role="button" class="btn btn-primary d-flex flex-column justify-content-center">
                <div class=""><i class="fas fa-pen fa-2x"></i></div>
                <div>投稿</div>
            </a>
        </div>
    @endif

@endsection
