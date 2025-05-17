<?php

namespace App\Actions\Auth;

use App\Contracts\Auth\CreatesAuthToken;
use App\Contracts\Auth\CreatesRefreshToken;
use App\Data\Users\AuthToken;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class CreateJWTToken implements CreatesAuthToken
{
    public function __construct(protected JWTAuth $jwt, protected CreatesRefreshToken $createRefreshToken)
    {
        //
    }

    public function execute(User $user, bool $withRefreshToken = false): AuthToken
    {
        $accessToken = $this->jwt->fromUser($user);

        $refreshToken = $withRefreshToken ? $this->createRefreshToken->execute($user) : null;

        return new AuthToken(
            accessToken: $accessToken,
            refreshToken: $refreshToken,
            expiresIn: $this->jwt->factory()->getTTL() * 60
        );
    }
}
