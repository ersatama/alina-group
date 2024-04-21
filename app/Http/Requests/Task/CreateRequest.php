<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateRequest extends FormRequest
{
    use Request;

    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'priority' => 'required',
            'status' => 'required',
            'expired_at' => 'required|date_format:Y-m-d H:i:s'
        ];
    }
}
