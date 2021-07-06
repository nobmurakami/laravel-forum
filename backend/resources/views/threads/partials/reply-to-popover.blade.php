<a href='#post_{{ $post->replyTo->id }}' class='text-body text-decoration-none'>
    <div class='font-weight-bold text-truncate'>{{ $post->replyTo->user->name }}</div>
    <div class='text-muted'>
        @include('shared.post-created-at', ['post' => $post->replyTo])
    </div>
    <p class='mb-2'>{{ safeBr($post->replyTo->content) }}</p>

    {{-- 画像 --}}
    @if (isset($post->replyTo->image_path))
        <img src='{{ asset("storage/" . $post->replyTo->image_path) }}' class='img-thumbnail post-image'>
    @endif
</a>
