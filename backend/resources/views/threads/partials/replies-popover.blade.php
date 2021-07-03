@foreach ($post->replies as $i => $reply)
    @if ($i !== 0)
        <div class='border-top mb-2'></div>
    @endif
    <a href='#post_{{ $reply->id }}' class='text-body'>
        <div class='font-weight-bold text-truncate'>{{ $reply->user->name }}</div>
        <div class='text-muted'>{{ $reply->created_at }}</div>
        <p class='mb-2'>{{ safeBr($reply->content) }}</p>
    </a>
@endforeach
