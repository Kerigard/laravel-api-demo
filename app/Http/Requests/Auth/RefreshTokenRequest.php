<?php

namespace App\Http\Requests\Auth;

use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class RefreshTokenRequest extends FormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'refresh_token' => ['required', 'string', 'max:64'],
        ];
    }

    /**
     * @throws ValidationException
     */
    public function authenticate(): User
    {
        $refreshToken = $this->refreshToken();

        if (! $refreshToken) {
            throw ValidationException::withMessages([
                'refresh_token' => __('validation.exists', ['attribute' => 'refresh_token']),
            ]);
        }

        return $refreshToken->user;
    }

    private function refreshToken(): ?RefreshToken
    {
        return RefreshToken::byToken($this->string('refresh_token'))->where('expires_at', '>', now())->first();
    }
}
