@foreach ($post->replies as $i => $reply)
    @if ($i !== 0)
        <div class='border-top my-2'></div>
    @endif
    @if ($reply->trashed())
        <p class='text-muted mb-0'>削除された投稿</p>
    @else
        <a href='#post_{{ $reply->id }}' class='text-body text-decoration-none'>
            <div class='font-weight-bold text-truncate'>{{ $reply->user->name }}</div>
            <div class='text-muted'>{{ $reply->created_at }}</div>
            <p class='mb-0'>{{ safeBr($reply->content) }}</p>

            {{-- 画像 --}}
            @if (isset($reply->image_path))
                <img src='{{ asset("storage/" . $reply->image_path) }}' class='img-thumbnail popover-image'>
            @endif
        </a>
    @endif
@endforeach
