<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        if (! $this->user()) {
            return Response::deny(__('messages.posts.not_allowed_create'));
        }

        return $this->user()->isAdmin() || $this->user()->isEditor()
                ? Response::allow()
                : Response::deny(__('messages.posts.not_allowed_create'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'regex:/^[A-Za-z0-9\s]+$/i'],
            'slug'  => ['sometimes', 'unique:App\Models\Post,slug'],
            'content'  => ['required', 'min:10'],
            'status'    => Rule::in([Post::DRAFT, Post::PENDING, Post::PUBLISH]),
            'allow_comments'    => ['boolean']
        ];
    }
}
