<div class="modal fade" id="imageModal_{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
                <img src="{{ asset('storage/' . $post->image_path) }}" class="img-fluid mx-auto d-block">
            </div>
        </div>
    </div>
</div>
