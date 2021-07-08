@extends('adminlte::page')

@section('content_header')
<h1>ユーザー管理</h1>
@stop

@section('content')
    @if ($users->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">ユーザー一覧</h3>
            </div>
            <div class="card-body table-responsive">
                <table id="users-table" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="text-nowrap">ID</th>
                            <th scope="col" class="text-nowrap">権限</th>
                            <th scope="col" class="text-nowrap">名前</th>
                            <th scope="col" class="text-nowrap">メールアドレス</th>
                            <th scope="col" class="text-nowrap">作成日時</th>
                            <th scope="col" class="text-nowrap">更新日時</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td scope="row">{{ $user->id }}</th>
                                <td class="text-nowrap">{{ $user->role === 1 ? '管理者' : '' }}</td>
                                <td style="min-width: 200px;">{{ $user->name }}</td>
                                <td class="text-nowrap">{{ $user->email }}</td>
                                <td class="text-nowrap">{{ $user->created_at }}</td>
                                <td class="text-nowrap">{{ $user->updated_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p>まだユーザーが存在しません。</p>
    @endif
@endsection

@section('js')
<script>
    $(function () {
        $("#users-table").DataTable( {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Japanese.json'
            }
        });
    });
</script>
@stop
