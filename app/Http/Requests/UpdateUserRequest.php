<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
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
            'name'  => ['required', 'min:3'],
            'email' => ['required', 'email', "unique:users,email,{$this->user->id},id"],
            'password'  => [
                'sometimes', 
                'confirmed', 
                Password::min(12)->mixedCase()->numbers()->symbols()->uncompromised()
            ]
        ];
    }
}
