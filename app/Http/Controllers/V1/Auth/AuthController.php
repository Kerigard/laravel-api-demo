<?php

namespace App\Http\Controllers\V1\Auth;

use App\Contracts\Auth\CreatesAuthToken;
use App\Contracts\Auth\RevokesRefreshToken;
use App\Data\Users\AuthToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutTokenRequest;
use App\Http\Requests\Auth\RefreshTokenRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Users\AuthUserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(LoginRequest $request, CreatesAuthToken $createAuthToken): AuthToken
    {
        return $createAuthToken->execute($request->authenticate(), $request->boolean('remember_me'));
    }

    public function refresh(
        RefreshTokenRequest $request,
        CreatesAuthToken $createAuthToken,
        RevokesRefreshToken $revokeRefreshToken
    ): AuthToken {
        return DB::transaction(function () use ($request, $createAuthToken, $revokeRefreshToken) {
            $user = $request->authenticate();

            $revokeRefreshToken->execute($request->string('refresh_token'));

            return $createAuthToken->execute($user, true);
        });
    }

    public function logout(LogoutTokenRequest $request, RevokesRefreshToken $revokeRefreshToken): Response
    {
        if ($request->isValidToken()) {
            $revokeRefreshToken->execute($request->string('refresh_token'));
        }

        auth()->guard()->logout();

        return response()->noContent();
    }

    public function register(RegisterRequest $request, CreatesAuthToken $createAuthToken): AuthToken
    {
        return DB::transaction(function () use ($request, $createAuthToken) {
            $user = User::query()->create($request->validated());

            return $createAuthToken->execute($user, $request->boolean('remember_me'));
        });
    }

    public function me(Request $request): AuthUserResource
    {
        return new AuthUserResource($request->user());
    }
}
