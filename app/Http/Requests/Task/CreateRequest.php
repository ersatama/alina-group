<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    use Request;

    public function rules(): array
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'priority' => 'required',
            'status' => 'required',
            'expired_at' => 'required|datetime'
        ];
    }
}
