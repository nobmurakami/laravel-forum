@extends('layouts.app')

@section('content')
    @include('shared.thread-header', ['thread' => $thread])

    @if (Auth::guard('user')->check())
        {{-- スレッドの編集・削除ボタン --}}
        <div class="d-flex">
                <button type="button" onclick="location.href='{{ route("threads.edit", $thread) }}'" class="btn btn-outline-secondary btn-sm mt-3">スレッドタイトルの編集</button>
            <form action="{{ route('threads.destroy', $thread) }}" method="post">
                @csrf
                @method('delete')
                <button type="submit" class="btn btn-outline-secondary btn-sm mt-3 ml-2" onclick='return confirm("削除しますか？");'>スレッドの削除</button>
            </form>
        </div>

        <div class="mt-4">
            <a href="{{ route('threads.posts.create', $thread) }}" role="button" class="btn btn-primary">投稿する</a>
        </div>
    @endif

    <main class="mt-4 post-index">
        @if ($posts->isNotEmpty())
            @foreach ($posts as $post)
                <section class="post-card card shadow-sm mb-2">
                    <div class="d-flex flex-column card-body">
                        <div class="row mb-2">
                            <a id="post_{{ $post->id }}" class="anchor"></a>
                            <h5 class="col card-title font-weight-bold">{{ $post->user->name }}</h5>

                            {{-- 投稿の編集・削除ボタン --}}
                            <div class="col-sm-4 d-flex justify-content-end align-items-start">
                                @if (Auth::guard('user')->check() && Auth::user()->can('update', $post))
                                    <button type="button" onclick='location.href="{{ route('posts.edit', $post) }}"' class="btn btn-outline-secondary btn-sm">編集</button>
                                @endif
                                @if (Auth::guard('user')->check() && Auth::user()->can('delete', $post))
                                    <form action="{{ route('posts.destroy', $post) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-outline-secondary btn-sm ml-2" onclick='return confirm("削除しますか？");'>削除</button>
                                    </form>
                                @endif
                            </div>

                        </div>
                        <h6 class="font-weight-normal card-subtitle mb-2 text-muted align-items-center">
                            {{ $post->created_at }}{{ ($post->updated_at > $post->created_at) ? '（編集済）' : '' }}
                        </h6>

                        {{-- 返信先の投稿 --}}
                        @if (isset($post->reply_to_id))
                            <div class="align-items-center mb-2">
                                <a class="" data-toggle="popover" data-content="@include('threads.partials.reply-to-popover')">{{ $post->replyTo->name ?? '匿名' }}</a><span class="text-muted">さんの投稿へのコメント</span>
                            </div>
                        @endif

                        {{-- 本文 --}}
                        <p class="card-text">
                            {{ safeBr($post->content) }}
                        </p>

                        {{-- 画像 --}}
                        @if (isset($post->image_path))
                            <figure class="figure">
                                <img src="{{ asset('storage/' . $post->image_path) . '?' . uniqid() }}" class="img-thumbnail post-image">
                                {{-- 画像削除リンク --}}
                                @if (Auth::guard('user')->check() && Auth::user()->can('update', $post))
                                    <figcaption class="figure-caption">
                                        <form action="{{ route('posts.image.destroy', $post) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-link text-reset" onclick='return confirm("画像を削除しますか？");'>画像を削除する</button>
                                        </form>
                                    </figcaption>
                                @endif
                            </figure>
                        @endif

                        {{-- コメント --}}
                        <div class="d-flex align-items-center">
                            @if ($post->replies->isNotEmpty())
                                <button class="btn btn-link text-dark" data-toggle="popover" title="この投稿へのコメント（{{ $post->replies->count() }}件）" data-content="@include('threads.partials.replies-popover')"><i class="far fa-comment"></i>&nbsp;{{ $post->replies->count() }}</button>
                            @endif

                            @if (Auth::guard('user')->check())
                                <a href="{{ route('posts.reply', $post) }}" role="button" class="btn btn-outline-dark btn-sm font-weight-bold">コメントする</a>
                            @endif
                        </div>

                    </div>

                    {{-- 投稿のURL取得用リンク --}}
                    <div class="post-link">
                        <a href="#post_{{ $post->id }}" class="text-muted">
                            <i class="fas fa-hashtag fa-lg"></i>
                        </a>
                    </div>
                </section>
            @endforeach
        @else
            <p>まだ投稿がありません。</p>
        @endif
    </main>

    @if (Auth::guard('user')->check())
        <div class="post-btn">
            <a href="{{ route('threads.posts.create', $thread) }}" role="button" class="btn btn-primary d-flex flex-column justify-content-center">
                <div class=""><i class="fas fa-pen fa-2x"></i></div>
                <div>投稿</div>
            </a>
        </div>
    @endif

@endsection
