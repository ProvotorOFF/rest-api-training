<?php

namespace App\Http\Requests\V1\Auth;

use App\DTO\V1\LoginData;
use App\Http\Requests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => 'required|string|email',
            'pass' => 'required|string'
        ];
    }

    public function authenticate()
    {
        $this->ensureIsNotRateLimited();
        $user = null;
        $data = LoginData::from($this->validated());
        if (!($user = Auth::guard('web')->attempt($data->all()))) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'Invalid credentials provided'
            ]);
        }
        $token = Auth::guard('web')->user()->createToken('token')->plainTextToken;
        return $token;
    }

    private function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 3)) return;
        throw ValidationException::withMessages([
            'Too many attempts'
        ]);
    }

    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email') . '|' . $this->ip()));
    }
}
