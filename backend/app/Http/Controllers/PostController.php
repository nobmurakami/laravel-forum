<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function create(Thread $thread)
    {
        $post = new Post();

        return view('posts.create', ['thread' => $thread, 'post' => $post]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @param  \App\Models\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request, Thread $thread)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $post = $thread->posts()->create($validated);

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

            return back()->withInput()->with('msg_failure', '投稿の保存に失敗しました。しばらく時間をおいてから再度お試しください');
        }

        return redirect()->route('threads.show', $thread)->with('msg_success', '投稿が完了しました');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }
}
