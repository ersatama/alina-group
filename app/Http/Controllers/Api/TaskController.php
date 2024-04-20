<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\CreateRequest;
use App\Http\Requests\Task\GetRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Task\TaskCollection;
use App\Http\Resources\Task\TaskResource;
use App\Services\Task\TaskCommandService;
use App\Services\Task\TaskQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskCommandService $taskCommandService,
        private readonly TaskQueryService $taskQueryService
    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function get(GetRequest $getRequest): JsonResponse
    {
        return response()->json([
            'data' => new TaskCollection($this->taskQueryService->get($getRequest->checked()))
        ], Response::HTTP_OK);
    }

    public function getById($id): JsonResponse
    {
        if ($task = $this->taskQueryService->firstById($id)) {
            return response()->json([
                'data' => new TaskResource($task)
            ], Response::HTTP_OK);
        }
        return response()->json([
            'message' => 'Task not found'
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws ValidationException
     */
    public function create(CreateRequest $createRequest): JsonResponse
    {
        $task = $this->taskCommandService->create($createRequest->checked());
        if (!$task) {
            return response()->json([
                'message' => 'Something goes wrong'
            ], Response::HTTP_BAD_REQUEST);
        }
        return response()->json([
            'data' => new TaskResource($task)
        ], Response::HTTP_CREATED);
    }

    /**
     * @throws ValidationException
     */
    public function update($id, UpdateRequest $updateRequest): JsonResponse
    {
        $task = $this->taskQueryService->firstById($id);
        if (!$task) {
            return response()->json([
                'message' => 'Task not found'
            ], Response::HTTP_NOT_FOUND);
        }
        $task = $this->taskCommandService->update($task, $updateRequest->checked());
        return response()->json([
            'data' => new TaskResource($task)
        ], Response::HTTP_OK);
    }

    public function deleteById($id): JsonResponse
    {
        $this->taskCommandService->deleteById($id);
        return response()->json([
            'message' => 'Task deleted'
        ], Response::HTTP_OK);
    }
}
