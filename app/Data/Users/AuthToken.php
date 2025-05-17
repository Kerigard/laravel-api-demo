<?php

namespace App\Data\Users;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @implements Arrayable<string, mixed>
 */
readonly class AuthToken implements Arrayable
{
    public function __construct(
        public string $accessToken,
        public ?string $refreshToken = null,
        public string $tokenType = 'Bearer',
        public ?int $expiresIn = null
    ) {
        //
    }

    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
        ];
    }
}
