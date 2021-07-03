@extends('layouts.app')

@section('content')
    @if (Auth::guard('user')->check())
        <a href="{{ route('threads.create') }}" role="button" class="btn btn-primary mb-4">スレッドを作成する</a>
    @endif

    <main>
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
    </main>
@endsection
