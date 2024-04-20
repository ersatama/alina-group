<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class GetRequest extends FormRequest
{
    use Request;

    public function rules(): array
    {
        return [
            'title' => 'nullable',
            'description' => 'nullable',
            'priority' => 'nullable',
            'status' => 'nullable',
            'expired_at' => 'nullable|datetime'
        ];
    }
}
