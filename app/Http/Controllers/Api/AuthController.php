<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Services\User\UserCommandService;
use App\Services\User\UserQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Openapi\Annotations as OA;
class AuthController extends Controller
{
    public function __construct(
        private readonly UserCommandService $userCommandService,
        private readonly UserQueryService $userQueryService
    )
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     operationId="login",
     *     summary="Authorization",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass user credentials",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="string", example="email@test.com"),
     *             @OA\Property(property="password", type="string", format="string", example="password"),
     *         )
     *     ),
     *     @OA\Response(
     *      response=200,
     *      description="ok",
     *      @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             example={
     *              "data": {
     *                  "id": 1,
     *                  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2FwaS92MS9sb2dpbiIsImlhdCI6MTcxMzYzNjQ0NywiZXhwIjoxNzEzNjQwMDQ3LCJuYmYiOjE3MTM2MzY0NDcsImp0aSI6Inh1VnhEalVUb1Y3c043R2kiLCJzdWIiOiIxIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.WzOOaWiaT-mDTzth9TiXvOpudUaEArqMy1fadbpkk6A",
     *                  "name": "name",
     *                  "surname": "surname",
     *                  "email": "email@test.com",
     *                  "updated_at": "2024-04-20T18:07:27.000000Z",
     *                  "updated_at": "2024-04-20T18:07:27.000000Z"
     *              }
     *            }
     *         )
     *      )
     *     ),
     *     @OA\Response(
     *       response=400,
     *       description="ok",
     *       @OA\MediaType(
     *        mediaType="application/json",
     *        @OA\Schema(
     *           example={
     *               "message": "Incorrect login or password"
     *           }
     *        )
     *       )
     *      ),
     *      @OA\Response(
     *        response=404,
     *        description="ok",
     *        @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *            example={
     *                "message": "User not found"
     *            }
     *         )
     *        )
     *       )
     *     )
     * )
     * @throws ValidationException
     */
    public function login(LoginRequest $loginRequest): JsonResponse
    {
        $data = $loginRequest->validated();
        $user = $this->userQueryService->firstByEmail($data['email']);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }
        $token = $this->userCommandService->attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ]);
        if (!$token) {
            return response()->json([
                'message' => 'Incorrect login or password'
            ], Response::HTTP_BAD_REQUEST);
        }
        $user = $this->userCommandService->update($user, [
            'token' => $token
        ]);
        return response()->json([
            'data' => new UserResource($user)
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     operationId="register",
     *     summary="Registration",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass user credentials",
     *         @OA\JsonContent(
     *             required={"name", "surname", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", format="string", example="Name"),
     *             @OA\Property(property="surname", type="string", format="string", example="Surname"),
     *             @OA\Property(property="email", type="string", format="string", example="email1@test.com"),
     *             @OA\Property(property="password", type="string", format="string", example="password"),
     *             @OA\Property(property="password_confirmation", type="string", format="string", example="password"),
     *         )
     *     ),
     *     @OA\Response(
     *      response=200,
     *      description="ok",
     *      @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *             example={
     *              "data": {
     *                  "id": 1,
     *                  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2FwaS92MS9sb2dpbiIsImlhdCI6MTcxMzYzNjQ0NywiZXhwIjoxNzEzNjQwMDQ3LCJuYmYiOjE3MTM2MzY0NDcsImp0aSI6Inh1VnhEalVUb1Y3c043R2kiLCJzdWIiOiIxIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.WzOOaWiaT-mDTzth9TiXvOpudUaEArqMy1fadbpkk6A",
     *                  "name": "name",
     *                  "surname": "surname",
     *                  "email": "email@test.com",
     *                  "updated_at": "2024-04-20T18:07:27.000000Z",
     *                  "updated_at": "2024-04-20T18:07:27.000000Z"
     *              }
     *            }
     *         )
     *      )
     *     ),
     *     @OA\Response(
     *       response=400,
     *       description="ok",
     *       @OA\MediaType(
     *        mediaType="application/json",
     *        @OA\Schema(
     *           example={
     *               "message": "Incorrect login or password"
     *           }
     *        )
     *       )
     *      ),
     *      @OA\Response(
     *        response=404,
     *        description="ok",
     *        @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(
     *            example={
     *                "message": "User not found"
     *            }
     *         )
     *        )
     *       )
     *     )
     * )
     * @throws ValidationException
     */
    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        $data = $registerRequest->checked();
        $user = $this->userCommandService->create($data);
        if (!$user) {
            return response()->json([
                'message' => 'something goes wrong'
            ], Response::HTTP_BAD_REQUEST);
        }
        $token = $this->userCommandService->attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ]);
        $user = $this->userCommandService->update($user, [
            'token' => $token
        ]);
        return response()->json([
            'data' => new UserResource($user)
        ], Response::HTTP_CREATED);
    }
}
