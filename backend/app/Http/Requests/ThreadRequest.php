<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Thread;
use App\Models\Post;
use App\Http\Requests\PostRequest;

class ThreadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = (array) (Thread::$rules ?? []);

        if ($this->routeIs('threads.store')) {
            $postRules = (array) (Post::$rules ?? []);
            $rules = array_merge($rules, $postRules);
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->has('post.content')) {
            $this->merge([
                'post' => [
                    'content' => crlf2lf($this->input('post.content')),
                ],
            ]);
        }
    }

    public function validationData()
    {
        $threadParams = $this->allowed()['thread'];

        $userId = $this->user()->id;
        $threadParams['user_id'] = $userId;

        $postParams = [];
        if ($this->routeIs('threads.store')) {
            $postParams = $this->allowed()['post'];
            $postParams['user_id'] = $userId;
        }

        return [
            'thread' => $threadParams,
            'post' =>  $postParams,
        ];
    }

    public function attributes()
    {
        $threadAttrs = (array) (Thread::$requestAttrs ?? []);
        $postAttrs = (array) (Post::$requestAttrs ?? []);

        return array_merge($threadAttrs, $postAttrs);
    }

    public function messages()
    {
        $threadMsgs = (array) (Thread::$messages ?? []);
        $postMsgs = (array) (Post::$messages ?? []);

        return array_merge($threadMsgs, $postMsgs);
    }

    public function allowed()
    {
        return $this->only(['thread.title', 'post.content', 'post.image']);
    }

    public function hasImage()
    {
        $postRequest = app(PostRequest::class);
        return $postRequest->hasImage();
    }

    public function image()
    {
        $postRequest = app(PostRequest::class);
        return $postRequest->image();
    }
}
