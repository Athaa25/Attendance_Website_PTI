<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = str_contains($credentials['email'], '@') ? 'email' : 'username';
        $attemptData = [
            $loginField => $credentials['email'],
            'password' => $credentials['password'],
        ];

        if (! Auth::validate($attemptData)) {
            return response()->json([
                'success' => false,
                'message' => 'Username/email atau password salah.',
            ], 401);
        }

        $user = User::query()
            ->with('role')
            ->where($loginField, $credentials['email'])
            ->first();

        if (! $user || ! $user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini tidak memiliki akses admin.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'role' => $user->role?->slug,
            ],
        ]);
    }
}
