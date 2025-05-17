<?php

namespace App\Http\Requests\Auth;

use App\Models\RefreshToken;
use Illuminate\Foundation\Http\FormRequest;

class LogoutTokenRequest extends FormRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'refresh_token' => ['string', 'max:64'],
        ];
    }

    public function isValidToken(): bool
    {
        if ($this->missing('refresh_token')) {
            return false;
        }

        $token = RefreshToken::byToken($this->string('refresh_token'))->first();

        return $token?->user_id === (int) auth()->guard()->id();
    }
}
