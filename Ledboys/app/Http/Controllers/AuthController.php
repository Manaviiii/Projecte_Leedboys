<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cliente;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | POST /api/login
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | POST /api/registro
    |
    | Crea el User y automáticamente su Cliente asociado.
    |--------------------------------------------------------------------------
    */
    public function registro(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // necesita password_confirmation
            'telefono' => 'nullable|string|max:20',
        ]);

        // Crear el User
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        // Crear el Cliente vinculado automáticamente
        Cliente::create([
            'user_id'  => $user->id,
            'nombre'   => $request->name,
            'email'    => $request->email,
            'telefono' => $request->telefono,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | POST /api/logout
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    /*
    |--------------------------------------------------------------------------
    | GET /api/me
    |--------------------------------------------------------------------------
    */
    public function me(Request $request)
    {
        $user = $request->user()->load('cliente');

        return response()->json($user);
    }
}