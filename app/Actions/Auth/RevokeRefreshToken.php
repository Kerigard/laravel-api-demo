<?php

namespace App\Actions\Auth;

use App\Contracts\Auth\RevokesRefreshToken;
use App\Models\RefreshToken;
use SensitiveParameter;

class RevokeRefreshToken implements RevokesRefreshToken
{
    public function execute(#[SensitiveParameter] string $token): void
    {
        RefreshToken::byToken($token)->delete();
    }
}
