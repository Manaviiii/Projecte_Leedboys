<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'name' => $user->name,
                'role' => $user->role
            ]);
        }

        return response()->json(['success' => false], 401);
    }
}
