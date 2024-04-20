<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Services\Service;

class TaskQueryService extends Service
{
    public function __construct(Task $task)
    {
        $this->model = $task;
    }
}
