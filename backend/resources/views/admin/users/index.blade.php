@extends('adminlte::page')

@section('content_header')
<h1>ユーザー管理</h1>
@stop

@section('content')
    @if ($users->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">ユーザー一覧</h3>
                <div class="card-tools">
                    <form action='#' method="get">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="user_search" class="form-control float-right" placeholder="ユーザーを検索">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">名前</th>
                            <th scope="col">メールアドレス</th>
                            <th scope="col">作成日時</th>
                            <th scope="col">更新日時</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <th scope="row">{{ $user->id }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td>{{ $user->updated_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                {{ $users->links('shared.pagination') }}
            </div>
        </div>

    @else
        <p>まだユーザーが存在しません。</p>
    @endif
@endsection
