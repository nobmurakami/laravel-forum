<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ThreadRequest;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('threads.index', [
            'threads' => Thread::withCount('posts')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create', [
            'thread' => new Thread(),
            'post' => new Post(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ThreadRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ThreadRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $thread = Thread::create($validated['thread']);
            $post = $thread->posts()->create($validated['post']);
            // 画像アップロード
            if ($request->hasImage()) {
                // updated_atを更新しないようにする
                $post->timestamps = false;
                // 画像ファイルをストレージのpublicディスクに保存。失敗したら例外を返す
                $path = $post->storeImageFile($request->image());
                // 画像パスをDBに保存。失敗したら例外を返す
                $post->saveImagePath($path);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            report($e);

            return back()->withInput()->withFailure('スレッドの作成に失敗しました。しばらく時間をおいてから再度お試しください');
        }

        return redirect()->route('threads.show', $thread)->withSuccess('スレッドの作成が完了しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Thread $thread)
    {
        return view('threads.show', [
            'thread' => $thread,
            'posts' => $thread->posts()->withTrashed()->with(['user', 'replies', 'replies.user', 'replyTo', 'replyTo.user'])->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        $this->authorize('update', $thread);

        return view('threads.edit', [
            'thread' => $thread,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(ThreadRequest $request, Thread $thread)
    {
        $this->authorize('update', $thread);

        try {
            $thread->fill($request->validated()['thread']);

            if ($thread->isClean()) {
                return redirect()->route('threads.show', $thread)->withInfo('変更はありません');
            }

            $result = $thread->save();
            if (!$result) {
                throw new \Exception('failed to save thread');
            }
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->withFailure('スレッドタイトルの更新に失敗しました。しばらく時間をおいてから再度お試しください');
        }

        return redirect()->route('threads.show', $thread)->withSuccess('スレッドタイトルを更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread);

        try {
            $result = $thread->delete();
            if (!$result) {
                throw new \Exception('failed to delete thread');
            }
        } catch (\Exception $e) {
            report($e);

            return back()->withFailure('スレッドの削除に失敗しました。しばらく時間をおいてから再度お試しください');
        }

        return redirect()->route('threads.index')->withSuccess('スレッドを削除しました');
    }
}
