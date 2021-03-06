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
        'post.reply_to_id' => '',
        'post.user_id' => 'required',
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
        return $this->hasMany('App\Models\Post', 'reply_to_id')->withTrashed();
    }

    public function replyTo()
    {
        return $this->belongsTo('App\Models\Post', 'reply_to_id')->withTrashed();
    }

    public function uploadImage(UploadedFile $file)
    {
        try {
            $path = $this->storeImageFile($file);
            if (!$path) {
                throw new \Exception('failed to storeImageFile().');
            }

            $result = $this->update(['image_path' => $path]);
        } catch (\Exception $e) {
            Log::error('failed to ' . __CLASS__ . '::' .  __FUNCTION__);

            throw $e;
        }

        return $result;
    }

    public function deleteImage()
    {
        try {
            $path = $this->image_path;

            Storage::disk('public')->delete($path);
            // ファイルが削除されているか確認し、まだあれば例外を投げる
            if (Storage::exists('public/' . $path)) {
                throw new \Exception('failed to delete image file');
            }

            $result = $this->update(['image_path' => null]);
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
