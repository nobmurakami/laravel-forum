<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Post;
use Illuminate\Http\Request;
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
        $this->authorize('update', $post);

        $thread = $post->thread;

        return view('posts.edit', ['thread' => $thread, 'post' => $post]);
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

        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $post->fill($validated);

            // 内容に変更がなく、画像がアップロードされていなければ処理を終了
            $dirty = $post->getDirty();
            if (count($dirty) === 0 && ! $request->hasFile('post.image')) {
                return redirect()->route('threads.show', $post->thread)->with('msg_info', '変更はありません');
            }

            $result = $post->save();
            // 例外が発生せずにfalseが返ってきたら例外を投げる
            if (!$result) {
                throw new \Exception('failed to save post');
            }

            // 画像アップロード
            if ($request->hasFile('post.image')) {
                $file = $request->file('post.image');

                // 画像ファイルをストレージのpublicディスクに保存。失敗したら例外を返す
                $path = $post->storeImageFile($file);
                // 画像パスをDBに保存。失敗したら例外を返す
                $post->saveImagePath($path);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            report($e);

            return back()->withInput()->with('msg_failure', '投稿の更新に失敗しました。しばらく時間をおいてから再度お試しください');
        }

        return redirect()->route('threads.show', $post->thread)->with('msg_success', '投稿を更新しました');
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
            // 例外が発生せずに失敗したら例外を投げる
            if (!$result) {
                throw new \Exception('failed to delete post');
            }
        } catch (\Exception $e) {
            report($e);

            return back()->with('msg_failure', '投稿の削除に失敗しました。しばらく時間をおいてから再度お試しください');
        }

        return back()->with('msg_success', '投稿を削除しました');
    }

    public function destroyImage(Post $post)
    {
        $this->authorize('update', $post);

        try {
            DB::beginTransaction();

            $filePath = $post->image_path;

            $post->fill(['image_path' => null]);
            $result = $post->save();
            // 例外が発生せずにfalseが返ってきたら例外を投げる
            if (!$result) {
                throw new \Exception('failed to save post');
            }


            Storage::disk('public')->delete($filePath);
            // ファイルが削除されているか確認し、まだあれば例外を投げる
            if (Storage::exists('public/' . $filePath)) {
                throw new \Exception('failed to delete image file');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            report($e);

            return back()->with('msg_failure', '画像の削除に失敗しました。しばらく時間をおいてから再度お試しください');
        }

        return back()->with('msg_success', '画像を削除しました');
    }

    public function reply(Post $replyTo)
    {
        $post = new Post();

        $thread = $replyTo->thread;

        return view('posts.reply', ['thread' => $thread, 'post' => $post, 'replyTo' => $replyTo]);
    }
}
