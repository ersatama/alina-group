<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    use Request;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:2',
            'surname' => 'required|string|max:255|min:2',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed'
        ];
    }
}
