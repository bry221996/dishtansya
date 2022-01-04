<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()
                ->json([
                    'message' => __('messages.auth.login.invalid_credentials')
                ], 401);
        }

        return response()->json(['access_token' => $token], 201);
    }

    /**
     * Register new user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3'
        ]);

        if (User::where('email', $request->email)->exists()) {
            return response()
                ->json([
                    'message' => __('messages.auth.register.email_already_taken')
                ], 400);
        }

        User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()
            ->json(['message' => __('messages.auth.register.success')], 201);
    }
}
