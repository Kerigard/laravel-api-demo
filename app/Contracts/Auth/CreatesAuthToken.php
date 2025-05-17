<?php

namespace App\Contracts\Auth;

use App\Data\Users\AuthToken;
use App\Models\User;

interface CreatesAuthToken
{
    public function execute(User $user, bool $withRefreshToken = false): AuthToken;
}
