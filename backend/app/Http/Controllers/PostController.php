<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        return view('posts.create', [
            'thread' => $thread,
            'post' => new Post(),
        ]);
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
        try {
            DB::beginTransaction();

            $post = $thread->posts()->create($request->validated());

            // 画像アップロード
            if ($request->hasImage()) {
                $result = $post->uploadImage($request->image());
                if (!$result) {
                    throw new \Exception('failed to Post::uploadImage().');
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            report($e);

            return back()->withInput()->withFailure('投稿の保存に失敗しました。しばらく時間をおいてから再度お試しください');
        }

        return redirect()->route('threads.show', $thread)->withSuccess('投稿が完了しました');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('posts.edit', [
            'thread' => $post->thread,
            'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        try {
            DB::beginTransaction();

            $post->fill($request->validated());

            // 内容に変更がなく、画像がアップロードされていなければ処理を終了
            if ($post->isClean() && ! $request->hasImage()) {
                return redirect()->route('threads.show', $post->thread)->withInfo('変更はありません');
            }

            $result = $post->save();
            if (!$result) {
                throw new \Exception('failed to save post');
            }

            // 画像アップロード
            if ($request->hasImage()) {
                $result = $post->uploadImage($request->image());
                if (!$result) {
                    throw new \Exception('failed to Post::uploadImage().');
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            report($e);

            return back()->withInput()->withFailure('投稿の更新に失敗しました。しばらく時間をおいてから再度お試しください');
        }

        return redirect()->route('threads.show', $post->thread)->withSuccess('投稿を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        try {
            $result = $post->delete();
            if (!$result) {
                throw new \Exception('failed to delete post');
            }
        } catch (\Exception $e) {
            report($e);

            return back()->withFailure('投稿の削除に失敗しました。しばらく時間をおいてから再度お試しください');
        }

        return back()->withSuccess('投稿を削除しました');
    }

    public function destroyImage(Post $post)
    {
        $this->authorize('update', $post);

        try {
            $result = $post->deleteImage();
            if (!$result) {
                throw new \Exception('failed to Post::deleteImage()');
            }
        } catch (\Exception $e) {
            report($e);

            return back()->withFailure('画像の削除に失敗しました。しばらく時間をおいてから再度お試しください');
        }

        return back()->withSuccess('画像を削除しました');
    }

    public function reply(Post $replyTo)
    {
        return view('posts.reply', [
            'thread' => $replyTo->thread,
            'post' => new Post(),
            'replyTo' => $replyTo,
        ]);
    }
}
