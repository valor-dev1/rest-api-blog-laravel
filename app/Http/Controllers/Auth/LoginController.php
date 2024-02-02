<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    
    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->only('email', 'password'), true)) {
            RateLimiter::hit($request->throttleKey());
    
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
        // 2|4ZbduZqvb5dXRD7ULxO6rQZHvI6pAEso0Q0qEgxRe839f664
        return [
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('user_token')
        ];
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        auth()->user()->currentAccessToken()->delete();
        Auth::guard('web')->logout();

        // $request->session()->invalidate();

        // $request->session()->regenerateToken();

        return response(['message' => __('Successfully logged out')]);
    }
}
