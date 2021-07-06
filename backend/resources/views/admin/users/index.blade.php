@extends('adminlte::page')

@section('content_header')
    <h1>ユーザー管理</h1>
@stop

@section('content')
    @if ($users->isNotEmpty())
        <table class="table table-sm">
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

        {{ $users->links() }}
    @else
        <p>まだユーザーが存在しません。</p>
    @endif
@endsection
