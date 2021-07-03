<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = ['content', 'image_path', 'user_id', 'board_id', 'reply_to_id'];

    public static $requestAttrs = [
        'post.content' => '投稿内容',
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
}
