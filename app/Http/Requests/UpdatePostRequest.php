<?php

namespace App\Http\Requests;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
