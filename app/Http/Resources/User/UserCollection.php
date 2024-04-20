<?php

namespace App\Http\Resources\User;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    public function toArray(Request $request): array|\JsonSerializable|Arrayable
    {
        return $this->collection->map(function ($request) {
            return new UserResource($request);
        });
    }
}
