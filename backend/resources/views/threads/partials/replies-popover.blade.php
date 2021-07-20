@foreach ($post->replies as $i => $reply)
    @if ($i !== 0)
        <div class='border-top my-2'></div>
    @endif
    <a href='#p{{ $reply->id }}' class='text-body text-decoration-none'>
        @if ($reply->trashed())
            <p class='text-muted mb-0'>削除された投稿</p>
        @else
            <div class='font-weight-bold text-truncate'>{{ $reply->user->name }}</div>
            <div class='text-muted'>
                @include('shared.post-created-at', ['post' => $reply])
            </div>
            <p class='mb-0'>{{ safeBr($reply->content) }}</p>

            {{-- 画像 --}}
            @if (isset($reply->image_path))
                <img src='{{ asset("storage/" . $reply->image_path) }}' class='img-thumbnail post-image'>
            @endif
        @endif
    </a>
@endforeach
