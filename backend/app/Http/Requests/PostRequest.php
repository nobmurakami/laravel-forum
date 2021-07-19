<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Post;

class PostRequest extends FormRequest
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
        $rules = Post::$rules;

        $route = $this->route()->getName();
        if ($route === 'threads.posts.store') {
            $rules['post.thread_id'] = 'required';
        }

        return $rules;
    }

    public function validationData()
    {
        $postParams = $this->all()['post'];
        if (isset($postParams['content'])) {
            $postParams['content'] = crlf2lf($postParams['content']);
        }

        $postParams['user_id'] = $this->user()->id;

        $route = $this->route()->getName();
        if ($route === 'threads.posts.store') {
            $postParams['thread_id'] = $this->route()->parameter('thread')->id;
        }

        return ['post' => $postParams];
    }

    public function attributes()
    {
        $attributes = [];
        if (property_exists('App\Models\Post', 'requestAttrs')) {
            $attributes = (array) Post::$requestAttrs;
        }

        return $attributes;
    }

    public function messages()
    {
        $messages = [];
        if (property_exists('App\Models\Post', 'messages')) {
            $messages = (array) Post::$messages;
        }

        return $messages;
    }

    public function validated()
    {
        return $this->validator->validated()['post'];
    }
}
