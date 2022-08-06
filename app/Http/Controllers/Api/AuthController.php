<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login', 'register']);
    }

    /**
     * Login a user.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::getUserBy($request->email, $request->password);

        if ($user === \null) {
            return $this->error(
                'Email or password does not match.',
                JsonResponse::HTTP_UNAUTHORIZED,
            );
        }

        return $this->authResponse($user);
    }

    /**
     * Register a user.
     *
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        $user->refresh();

        return $this->authResponse($user);
    }

    /**
     * Get a logged in user.
     *
     * @param \Illuminate\Auth\AuthManager $auth
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(AuthManager $auth): JsonResponse
    {
        return $this->ok($auth->user());
    }

    /**
     * Get authentication response with "user" and "token" properties.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function authResponse(User $user): JsonResponse
    {
        $token = $user->apiTokens()->create([
            'value' => Str::orderedUuid(),
        ]);

        return $this->ok([
            'user'  => $user,
            'token' => $token->value,
        ]);
    }
}
