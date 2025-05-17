<?php

namespace App\Actions\Auth;

use App\Contracts\Auth\CreatesRefreshToken;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;
use SensitiveParameter;

class CreateRefreshToken implements CreatesRefreshToken
{
    public function execute(User $user): string
    {
        return retry(3, function () use ($user) {
            $plainTextToken = $this->generateTokenString();

            $this->createRefreshToken($user, $plainTextToken);

            return $plainTextToken;
        }, when: fn ($e) => $e instanceof UniqueConstraintViolationException);
    }

    private function generateTokenString(): string
    {
        return str(Str::ulid())->lower()->append(bin2hex(random_bytes(16)));
    }

    private function createRefreshToken(User $user, #[SensitiveParameter] string $plainTextToken): void
    {
        $user->refreshTokens()->create([
            'token' => hash('sha256', $plainTextToken),
            'user_agent' => request()->header('User-Agent'),
            'ip_address' => request()->ip(),
            'expires_at' => now()->addYear(),
        ]);
    }
}
