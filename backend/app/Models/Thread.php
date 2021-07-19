<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'user_id'];

    public static $requestAttrs = [
        'thread.title' => 'タイトル',
    ];

    public static $rules = [
        'thread.title' => 'required|max:128',
        'thread.user_id' => 'required',
    ];

    public static $messages;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(function ($thread) {
            $thread->posts()->delete();
        });
    }
}
