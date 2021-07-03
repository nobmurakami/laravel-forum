<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Thread;
use App\Models\Post;

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
        $rules = (array) Thread::$rules;

        $route =  $this->route()->getName();
        if ($route === 'threads.store') {
            $rules = array_merge($rules, (array) Post::$rules);
        }

        return $rules;
    }

    public function validationData()
    {
        $threadParams = $this->all()['thread'];
        $postParams = [];

        $userId = $this->user()->id;
        $threadParams['user_id'] = $userId;

        $route =  $this->route()->getName();
        if ($route === 'threads.store') {
            $postParams = $this->all()['post'];
            $postParams['user_id'] = $userId;

            if (isset($postParams['content'])) {
                $postParams['content'] = crlf2lf($postParams['content']);
            }
        }

        return [
            'thread' => $threadParams,
            'post' =>  $postParams,
        ];
    }

    public function attributes()
    {
        $threadAttrs = [];
        if (property_exists('App\Models\Thread', 'requestAttrs')) {
            $threadAttrs = (array) Thread::$requestAttrs;
        }

        $postAttrs = [];
        if (property_exists('App\Models\Post', 'requestAttrs')) {
            $postAttrs = (array) Post::$requestAttrs;
        }

        return array_merge($threadAttrs, $postAttrs);
    }

    public function messages()
    {
        $threadMsgs = [];
        if (property_exists('App\Models\Thread', 'messages')) {
            $threadMsgs = (array) Thread::$messages;
        }

        $postMsgs = [];
        if (property_exists('App\Models\Post', 'messages')) {
            $postMsgs = (array) Post::$messages;
        }

        return array_merge($threadMsgs, $postMsgs);
    }
}
