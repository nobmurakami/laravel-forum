<a href='#post_{{ $post->replyTo->id }}' class='text-body'>
    <div class='font-weight-bold text-truncate'>{{ $post->replyTo->user->name }}</div>
    <div class='text-muted'>{{ $post->replyTo->created_at }}</div>
    <p class='mb-2'>{{ safeBr($post->replyTo->content) }}</p>

    {{-- 画像 --}}
    @if (isset($post->replyTo->image_path))
        <img src='{{ asset("storage/" . $post->replyTo->image_path) }}' class='img-thumbnail popover-image'>
    @endif
</a>
