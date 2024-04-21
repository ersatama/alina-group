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
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskCommandService $taskCommandService,
        private readonly TaskQueryService $taskQueryService
    )
    {
        $this->middleware('auth:api');
    }
    /**
     * @OA\Post(
     *     path="/api/v1/task/get",
     *     operationId="task.list",
     *     summary="Task list",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *       name="page",
     *       description="pagination",
     *       in="query",
     *       required=false,
     *       @OA\Schema(
     *           type="integer",
     *           example=1
     *       )
     *     ),
     *     @OA\Parameter(
     *        name="take",
     *        description="limit",
     *        in="query",
     *        required=false,
     *        @OA\Schema(
     *            type="integer",
     *            example=20
     *        )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Filter",
     *          @OA\JsonContent(
     *              @OA\Property(property="title", type="string", format="string", example="title #1"),
     *              @OA\Property(property="description", type="string", format="string", example="description #1"),
     *              @OA\Property(property="priority", type="string", format="string", example="low"),
     *              @OA\Property(property="status", type="string", format="string", example="active"),
     *              @OA\Property(property="expired_at", type="string", format="string", example="2024-04-20 17:42:16"),
     *          )
     *      ),
     *     @OA\Response(
     *        response=401,
     *        description="Unauthenticated",
     *        @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               example={
                         "message": "Unauthenticated"
     *               }
     *           )
     *        )
     *      ),
     *     @OA\Response(
     *       response=200,
     *       description="ok",
     *       @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              example={
     *                  "data": "..."
     *              }
     *          )
     *       )
     *     )
     * )
     * @throws ValidationException
     */
    public function get(GetRequest $getRequest): JsonResponse
    {
        return response()->json([
            'data' => new TaskCollection($this->taskQueryService->get($getRequest->checked()))
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/task/getById/{id}",
     *     operationId="task.getById",
     *     summary="Task getById",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *        name="id",
     *        description="task id",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *            type="integer",
     *            example=1
     *        )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                example={
     * "message": "Unauthenticated"
     *                }
     *            )
     *         )
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Task not found",
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *  "message": "Task not found"
     *                 }
     *             )
     *          )
     *      ),
     *     @OA\Response(
     *        response=200,
     *        description="ok",
     *        @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               example={
     *                   "data": {
     * "id": 1,
     * "title": "title #1",
     * "description": "description #1",
     * "priority": "low",
     * "status": "active",
     * "expired_at": "2024-04-20 17:42:16",
     * "created_at": null,
     * "updated_at": null
     * }
     *               }
     *           )
     *        )
     *      )
     * )
     */
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
     * @OA\Post(
     *     path="/api/v1/task/create",
     *     operationId="task.create",
     *     summary="Task create",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *           @OA\RequestBody(
     *           required=true,
     *           description="Create parameters [prority: low, meduim, high]",
     *           @OA\JsonContent(
     *               required={"title", "description", "priority", "status", "expired_at"},
     *               @OA\Property(property="title", type="string", format="string", example="title #1"),
     *               @OA\Property(property="description", type="string", format="string", example="description #1"),
     *               @OA\Property(property="priority", type="string", format="string", example="high"),
     *               @OA\Property(property="status", type="string", format="string", example="active"),
     *               @OA\Property(property="expired_at", type="string", format="string", example="2024-04-20 17:42:16"),
     *           )
     *       ),
     *     @OA\Response(
     *        response=200,
     *        description="ok",
     *        @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               example={
     *                   "data": {
     * "id": 100,
     * "title": "title #1",
     * "description": "description #1",
     * "priority": "high",
     * "status": "active",
     * "expired_at": "2024-04-20 17:42:16",
     * "created_at": "2024-04-21T08:23:55.000000Z",
     * "updated_at": "2024-04-21T08:23:55.000000Z"
     * }
     *               }
     *           )
     *        )
     *      ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                example={
     * "message": "Unauthenticated"
     *                }
     *            )
     *         )
     *       )
     * )
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
     * @OA\Put(
     *     path="/api/v1/task/update/{id}",
     *     operationId="task.update",
     *     summary="Task update",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="task id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *      ),
     *     @OA\RequestBody(
     *           required=true,
     *           description="Update task data [prority: low, meduim, high]",
     *           @OA\JsonContent(
     *               @OA\Property(property="title", type="string", format="string", example="title #1"),
     *               @OA\Property(property="description", type="string", format="string", example="description #1"),
     *               @OA\Property(property="priority", type="string", format="string", example="low"),
     *               @OA\Property(property="status", type="string", format="string", example="active"),
     *               @OA\Property(property="expired_at", type="string", format="string", example="2024-04-20 17:42:16"),
     *           )
     *       ),
     *     @OA\Response(
     *         response=200,
     *         description="ok",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                example={
     *                    "data": {
     *  "id": 100,
     *  "title": "title #1",
     *  "description": "description #1",
     *  "priority": "high",
     *  "status": "active",
     *  "expired_at": "2024-04-20 17:42:16",
     *  "created_at": "2024-04-21T08:23:55.000000Z",
     *  "updated_at": "2024-04-21T08:23:55.000000Z"
     *  }
     *                }
     *            )
     *         )
     *       ),
     *      @OA\Response(
     *           response=404,
     *           description="Task not found",
     *           @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  example={
     *   "message": "Task not found"
     *                  }
     *              )
     *           )
     *       ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example={
     *  "message": "Unauthenticated"
     *                 }
     *             )
     *          )
     *    )
     * )
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

    /**
     * @OA\Delete(
     *     path="/api/v1/task/delete/{id}",
     *     operationId="task.delete",
     *     summary="Task delete",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="task id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              example=1
     *          )
     *       ),
     *     @OA\Response(
     *            response=200,
     *            description="ok",
     *            @OA\MediaType(
     *               mediaType="application/json",
     *               @OA\Schema(
     *                   example={
     *    "message": "Task deleted"
     *                   }
     *               )
     *            )
     *      ),
     *     @OA\Response(
     *           response=401,
     *           description="Unauthenticated",
     *           @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  example={
     *   "message": "Unauthenticated"
     *                  }
     *              )
     *           )
     *     )
     * )
     */
    public function delete($id): JsonResponse
    {
        $this->taskCommandService->deleteById($id);
        return response()->json([
            'message' => 'Task deleted'
        ], Response::HTTP_OK);
    }
}
