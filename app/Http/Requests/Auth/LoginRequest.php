<?php

namespace App\Http\Requests\Auth;

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
            'email' => 'required|string|email',
            'password' => 'required|string'
        ];
    }

    public function authenticate()
    {
        $this->ensureIsNotRateLimited();
        $user = null;
        if (!($user = Auth::guard('web')->attempt($this->validated()))) {
            RateLimiter::hit($this->throttleKey());
            $this->validator->errors()->add('authentication', 'Invalid credentials provided.');
        }
        $token = Auth::guard('web')->user()->createToken('token')->plainTextToken;
        return $token;
    }

    private function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 3)) return;
        throw ValidationException::withMessages([
            'message' => 'Too many attempts'
        ]);
    }

    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email') . '|' . $this->ip()));
    }
}
