<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return collect(
            [
                'id' => $this->id,
                'token' => $this->token,
                'name' => $this->name,
                'surname' => $this->surname,
                'email' => $this->email,
                'updated_at' => $this->updated_at,
                'created_at' => $this->created_at
            ]
        )->filter()->toArray();
    }
}
