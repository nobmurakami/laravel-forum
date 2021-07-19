{{ $post->created_at }}{{ ($post->updated_at > $post->created_at) ? '（編集済）' : '' }}
