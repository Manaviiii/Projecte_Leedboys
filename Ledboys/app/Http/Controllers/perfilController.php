<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GET /api/perfil
    |
    | Devuelve los datos del usuario autenticado y su cliente asociado.
    |--------------------------------------------------------------------------
    */
    public function ver(Request $request)
    {
        $user = $request->user()->load('cliente');

        return response()->json([
            'id'       => $user->id,
            'name'     => $user->name,
            'email'    => $user->email,
            'role'     => $user->role,
            'cliente'  => $user->cliente ? [
                'nombre'   => $user->cliente->nombre,
                'email'    => $user->cliente->email,
                'telefono' => $user->cliente->telefono,
            ] : null,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PUT /api/perfil
    |
    | Actualiza nombre y teléfono del usuario y su cliente asociado.
    |--------------------------------------------------------------------------
    */
    public function actualizar(Request $request)
    {
        $request->validate([
            'name'     => 'sometimes|string|max:255',
            'telefono' => 'sometimes|nullable|string|max:20',
        ]);

        $user = $request->user();

        if ($request->has('name')) {
            $user->update(['name' => $request->name]);

            // Sincronizar nombre en el cliente también
            $user->cliente?->update(['nombre' => $request->name]);
        }

        if ($request->has('telefono')) {
            $user->cliente?->update(['telefono' => $request->telefono]);
        }

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user'    => $user->fresh()->load('cliente'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PUT /api/perfil/email
    |
    | Cambia el email del usuario. Requiere la contraseña actual.
    |--------------------------------------------------------------------------
    */
    public function cambiarEmail(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        $user = $request->user();

        // Verificar contraseña actual antes de cambiar el email
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'La contraseña no es correcta',
            ], 422);
        }

        $user->update(['email' => $request->email]);

        // Sincronizar email en el cliente también
        $user->cliente?->update(['email' => $request->email]);

        return response()->json([
            'message' => 'Email actualizado correctamente',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PUT /api/perfil/password
    |
    | Cambia la contraseña. Requiere la contraseña actual.
    |--------------------------------------------------------------------------
    */
    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual'  => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
            // necesita password_confirmation en el body
        ]);

        $user = $request->user();

        if (!Hash::check($request->password_actual, $user->password)) {
            return response()->json([
                'message' => 'La contraseña actual no es correcta',
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada correctamente',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE /api/perfil
    |
    | Elimina la cuenta del usuario. Requiere la contraseña actual.
    | También elimina el cliente asociado (por cascade o manualmente).
    |--------------------------------------------------------------------------
    */
    public function eliminar(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'La contraseña no es correcta',
            ], 422);
        }

        // Revocar todos los tokens de Sanctum
        $user->tokens()->delete();

        // Eliminar cliente asociado si existe
        $user->cliente?->delete();

        // Eliminar el usuario
        $user->delete();

        return response()->json([
            'message' => 'Cuenta eliminada correctamente',
        ]);
    }
}