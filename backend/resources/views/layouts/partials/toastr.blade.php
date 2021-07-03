{{-- トースト用メッセージの出力 --}}

{{--成功時--}}
@if (session('msg_success'))
    <input type="hidden" data-toastr="success" value="{{ session('msg_success') }}">
@endif

{{--失敗時--}}
@if (session('msg_failure'))
    <input type="hidden" data-toastr="error" value="{{ session('msg_failure') }}">
@endif

{{--バリデーションエラー--}}
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <input type="hidden" data-toastr="error" value="{{ $error }}">
    @endforeach
@endif

{{--その他--}}
@if (session('msg_info'))
    <input type="hidden" data-toastr="info" value="{{ session('msg_info') }}">
@endif
