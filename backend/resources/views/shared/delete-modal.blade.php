<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ $body }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">いいえ</button>
                <form action="{{ route($routeName, $model) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">はい</button>
                </form>
            </div>
        </div>
    </div>
</div>
