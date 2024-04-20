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

class AuthController extends Controller
{
    public function __construct(
        private readonly UserCommandService $userCommandService,
        private readonly UserQueryService $userQueryService
    )
    {
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $loginRequest): JsonResponse
    {
        $data = $loginRequest->checked();
        $user = $this->userQueryService->firstByEmail($data['email']);
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], Response::HTTP_NOT_FOUND);
        }
        $token = $this->userCommandService->attempt($data);
        if (!$token) {
            return response()->json([
                'message' => 'Incorrect login or password'
            ], Response::HTTP_NOT_FOUND);
        }
        $user = $this->userCommandService->update($user, [
            'token' => $token
        ]);
        return response()->json([
            'data' => new UserResource($user)
        ], Response::HTTP_OK);
    }

    /**
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
