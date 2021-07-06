<div class="d-flex">
    @can('update', $thread)
        <form action="{{ route('threads.edit', $thread) }}" method="get">
            <button type="submit" class="btn btn-outline-secondary btn-sm">スレッドタイトルの編集</button>
        </form>
    @endcan
    @can('delete', $thread)
        <button type="button" class="btn btn-outline-secondary btn-sm ml-2" data-toggle="modal" data-target="#deleteThread">
            スレッドの削除
        </button>
        @include('shared.delete-modal', ['id' => 'deleteThread', 'title' => '確認', 'body' => 'スレッドを削除しますか？', 'routeName' => 'threads.destroy', 'model' => $thread])
    @endcan
</div>
