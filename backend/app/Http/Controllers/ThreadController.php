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
        $threads = Thread::withCount('posts')->get();

        return view('threads.index', ['threads' => $threads]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $thread = new Thread();
        $post = new Post();

        return view('threads.create', ['thread' => $thread, 'post' => $post]);
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
            if ($request->hasFile('post.image')) {
                $file = $request->file('post.image');

                // updated_atを更新しないようにする
                $post->timestamps = false;
                // 画像ファイルをストレージのpublicディスクに保存。失敗したら例外を返す
                $path = $post->storeImageFile($file);
                // 画像パスをDBに保存。失敗したら例外を返す
                $post->saveImagePath($path);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            report($e);

            return back()->withInput()->with('msg_failure', 'スレッドの作成に失敗しました。しばらく時間をおいてから再度お試しください');
        }

        return redirect()->route('threads.show', $thread)->with('msg_success', 'スレッドの作成が完了しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show(Thread $thread)
    {
        $posts = $thread->posts;

        return view('threads.show', ['thread' => $thread, 'posts' => $posts]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        //
    }
}
