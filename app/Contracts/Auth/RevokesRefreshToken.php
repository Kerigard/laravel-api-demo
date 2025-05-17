<?php

namespace App\Contracts\Auth;

use SensitiveParameter;

interface RevokesRefreshToken
{
    public function execute(#[SensitiveParameter] string $token): void;
}
