<?php

namespace App\Http\Requests;


use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:3'
        ];
    }

    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        $token = auth()->attempt($this->only('email', 'password'));

        if (!$token) {
            RateLimiter::hit($this->throttleKey(), 300);

            abort(401, __('messages.auth.login.failed'));
        }

        RateLimiter::clear($this->throttleKey());

        return $token;
    }

    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        abort(401, __('messages.auth.login.throttle', [
            'seconds' => $seconds,
        ]));
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')) . '|' . $this->ip();
    }
}
