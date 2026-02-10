<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // 1️⃣ Ambil user langsung dari database (AMAN)
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        // 2️⃣ Cek password manual (Laravel native)
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        // 3️⃣ Cek ACTIVE (100% FIX)
        if (!$user->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak aktif',
            ], 403);
        }

        // 4️⃣ Generate JWT (resmi tymon)
        $token = Auth::guard('api')->login($user);

        // 5️⃣ Simpan token ke cookie
        $cookie = cookie(
            'access_token',
            $token,
            config('jwt.ttl') * 60,
            '/',
            null,
            false,
            true,
            false,
            'lax'
        );

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'user' => $user,
        ])->cookie($cookie);
    }

    public function me()
    {
        return response()->json([
            'success' => true,
            'user' => Auth::guard('api')->user(),
        ]);
    }


    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ])->cookie(Cookie::forget('access_token'));
    }

    public function refresh(Request $request)
    {
        try {
            // 1️⃣ Ambil token dari cookie
            $token = $request->cookie('access_token');

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak ditemukan',
                ], 401);
            }

            // 2️⃣ Set token ke JWT
            JWTAuth::setToken($token);

            // 3️⃣ Refresh token
            $newToken = JWTAuth::refresh();

            // 4️⃣ Kirim ulang ke cookie
            return response()->json([
                'success' => true,
                'token' => $newToken,
            ])->cookie(
                cookie(
                    'access_token',
                    $newToken,
                    config('jwt.ttl') * 60,
                    '/',
                    null,
                    false,
                    true,
                    false,
                    'lax'
                )
            );
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau expired',
            ], 401);
        }
    }
}
