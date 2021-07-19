@extends('adminlte::page')

@section('content_header')
    <h1>ダッシュボード</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <a href="{{ route('admin.users.index') }}" class="text-reset">
                <div class="info-box">
                    <span class="info-box-icon bg-primary">
                        <i class="fas fa-users"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">
                            ユーザー管理
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection
