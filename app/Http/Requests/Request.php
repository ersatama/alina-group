<?php

namespace App\Http\Requests;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

trait Request
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @throws ValidationException
     */
    public function checked(): array
    {
        return $this->validator->validated();
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'        =>  'failure',
            'status_code'   =>  400,
            'message'       =>  'Bad Request',
            'errors'        =>  $validator->errors(),
        ], 400));
    }
}
