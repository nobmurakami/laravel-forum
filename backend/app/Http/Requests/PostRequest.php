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
        $rules = (array) (Post::$rules ?? []);

        if ($this->routeIs('threads.posts.store')) {
            $rules['post.thread_id'] = 'required';
            $rules['post.user_id'] = 'required';
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
        $postParams = $this->allowed()['post'];

        $postParams['user_id'] = $this->user()->id;

        if ($this->routeIs('threads.posts.store')) {
            $postParams['thread_id'] = $this->route()->parameter('thread')->id;
        }

        return ['post' => $postParams];
    }

    public function attributes()
    {
        return (array) (Post::$requestAttrs ?? []);
    }

    public function messages()
    {
        return (array) (Post::$messages ?? []);
    }

    public function validated()
    {
        return $this->validator->validated()['post'];
    }

    public function allowed()
    {
        return $this->only(['post.content', 'post.image']);
    }

    public function hasImage()
    {
        return $this->hasFile('post.image');
    }

    public function image()
    {
        return $this->file('post.image');
    }
}
