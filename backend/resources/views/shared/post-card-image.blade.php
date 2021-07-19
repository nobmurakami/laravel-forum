@if (isset($post->image_path))
    <figure class="figure mt-2 mb-0">
        <img src="{{ asset('storage/' . $post->image_path) }}" class="img-thumbnail post-image" data-toggle="modal" data-target="#imageModal_{{ $post->id }}" style="cursor:pointer">
    </figure>
    @include('shared.image-modal', ['post' => $post])
@endif
