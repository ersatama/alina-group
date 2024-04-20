<?php

namespace App\Http\Resources\Task;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskCollection extends ResourceCollection
{
    public function toArray(Request $request): array|\JsonSerializable|Arrayable
    {
        return $this->collection->map(function ($request) {
            return new TaskResource($request);
        });
    }
}
