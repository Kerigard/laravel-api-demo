<?php

namespace App\Contracts\Auth;

use App\Models\User;

interface CreatesRefreshToken
{
    public function execute(User $user): string;
}
