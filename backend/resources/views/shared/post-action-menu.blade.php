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
