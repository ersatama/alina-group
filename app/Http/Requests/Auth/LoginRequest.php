<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use Request;

    public function rules(): array
    {
        return [
            'email' =>  'required|email',
            'password' => 'required'
        ];
    }
}
