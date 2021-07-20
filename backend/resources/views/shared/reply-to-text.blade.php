@if (isset($post->replyTo))
    @if ($post->replyTo->trashed())
        <span class="text-muted">削除された投稿へのコメント</span>
    @else
        <div class="align-items-center">
            <a href="#p{{ $post->replyTo->id }}" role="button" data-toggle="popover" data-content="@include('threads.partials.reply-to-popover')">
                {{ $post->replyTo->user->name }}
            </a>
            <span class="text-muted">さんの投稿へのコメント</span>
        </div>
    @endif
@endif
