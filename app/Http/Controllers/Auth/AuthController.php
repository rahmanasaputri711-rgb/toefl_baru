<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Register berhasil',
            'user' => $user
        ]);
    }

    // LOGIN (PAKAI SESSION)
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        auth()->login($user); // ✅ INI PENTING

        return response()->json([
            'message' => 'Login berhasil',
            'user' => $user
        ]);
    }

    // LOGOUT (SESSION)
    public function logout(Request $request)
    {
        auth()->logout(); // ✅ INI

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}