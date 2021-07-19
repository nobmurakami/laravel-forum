@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <a href="{{ route('threads.create') }}" role="button" class="btn btn-primary">スレッドを作成する</a>
    @endif

    <div class="mt-4">
        @if ($threads->isNotEmpty())
            @foreach ($threads as $thread)
                <section class="card shadow-sm mb-2">
                    <a href="{{ route('threads.show', $thread) }}" class="text-body text-decoration-none">
                        <h5 class="card-body mb-0">
                            {{ $thread->title }}
                            <small class="text-muted font-weight-normal">({{ $thread->posts_count }})</small>
                        </h5>
                    </a>
                </section>
            @endforeach
        @else
            <p>まだスレッドがありません。</p>
        @endif
    </div>
@endsection
