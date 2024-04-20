<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Services\Service;

class TaskCommandService extends Service
{
    public function __construct(Task $task)
    {
        $this->model = $task;
    }
}
