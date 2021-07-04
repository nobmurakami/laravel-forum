<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = ['content', 'image_path', 'user_id', 'thread_id', 'reply_to_id'];

    public static $requestAttrs = [
        'post.content' => '内容',
        'post.image' => '画像',
    ];

    public static $rules = [
        'post.content' => 'required|max:1000',
        'post.image' => 'file|image|mimes:jpeg,png,gif|max:5120',
        'post.user_id' => 'required',
        'post.reply_to_id' => '',
    ];

    public static $messages = [
        'post.image.max' => '画像には5MB以下のファイルを指定してください。',
    ];

    public function thread()
    {
        return $this->belongsTo('App\Models\Thread');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function replies()
    {
        return $this->hasMany('App\Models\Post', 'reply_to_id');
    }

    public function replyTo()
    {
        return $this->belongsTo('App\Models\Post', 'reply_to_id');
    }

    public function saveImagePath(string $imagePath)
    {
        try {
            $this->fill(['image_path' => $imagePath]);
            $result = $this->save();

            // 例外が発生せずにfalseが返ってきたら例外を投げる
            if (!$result) {
                throw new \Exception('failed to save post');
            }
        } catch (\Exception $e) {
            Log::error('failed to ' . __CLASS__ . '::' .  __FUNCTION__);

            throw $e;
        }

        return $result;
    }

    public function storeImageFile(UploadedFile $file): string
    {
        try {
            $path = $file->store('post_images', 'public');
            // 画像が保存されていなければ例外を投げる
            if (Storage::missing('public/' . $path)) {
                throw new \Exception('failed to store image file');
            }
        } catch (\Exception $e) {
            Log::error('failed to ' . __CLASS__ . '::' .  __FUNCTION__);

            throw $e;
        }

        return $path;
    }
}
