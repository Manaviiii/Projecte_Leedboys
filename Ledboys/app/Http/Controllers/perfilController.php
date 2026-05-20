<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Controlador del perfil del usuario autenticado.
 * Permite ver y modificar los datos personales, cambiar email y contraseña,
 * y eliminar la cuenta. Todos los endpoints requieren autenticación.
 * Los cambios en el usuario se sincronizan también en el cliente asociado.
 */
class PerfilController extends Controller
{
    /**
     * Devuelve los datos del usuario autenticado y su perfil de cliente.
     * 
     * @route GET /api/perfil
     * @param Request $request
     * @return JsonResponse datos del usuario y cliente asociado
     */
    public function ver(Request $request)
    {
        // Cargar el usuario con su cliente en una sola consulta
        $user = $request->user()->load('cliente');

        return response()->json([
            'id'      => $user->id,
            'name'    => $user->name,
            'email'   => $user->email,
            'role'    => $user->role,
            'cliente' => $user->cliente ? [
                'nombre'   => $user->cliente->nombre,
                'email'    => $user->cliente->email,
                'telefono' => $user->cliente->telefono,
            ] : null,
        ]);
    }

    /**
     * Actualiza el nombre y/o teléfono del usuario.
     * 
     * Usa 'sometimes' para que cada campo sea opcional individualmente —
     * si no se manda un campo, no se toca.
     * Los cambios se sincronizan también en el perfil de cliente asociado.
     * 
     * @route PUT /api/perfil
     * @param Request $request — name (opcional), telefono (opcional)
     * @return JsonResponse mensaje de confirmación + datos actualizados
     */
    public function actualizar(Request $request)
    {
        $request->validate([
            'name'     => 'sometimes|string|max:255',
            'telefono' => 'sometimes|nullable|string|max:20',
        ]);

        $user = $request->user();

        // Actualizar el nombre en el usuario y sincronizar en el cliente
        if ($request->has('name')) {
            $user->update(['name' => $request->name]);
            $user->cliente?->update(['nombre' => $request->name]);
        }

        // Actualizar el teléfono solo en el cliente (el usuario no tiene este campo)
        if ($request->has('telefono')) {
            $user->cliente?->update(['telefono' => $request->telefono]);
        }

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user'    => $user->fresh()->load('cliente'), // recargar para devolver datos actualizados
        ]);
    }

    /**
     * Cambia el email del usuario verificando la contraseña actual.
     * 
     * Se exige la contraseña como medida de seguridad para evitar
     * que alguien con la sesión abierta pueda cambiar el email sin saberla.
     * El nuevo email se sincroniza también en el cliente asociado.
     * 
     * @route PUT /api/perfil/email
     * @param Request $request — email (nuevo, único), password (actual)
     * @return JsonResponse mensaje de confirmación | 422 si la contraseña es incorrecta
     */
    public function cambiarEmail(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|unique:users,email', // el email debe ser único en la tabla users
            'password' => 'required',
        ]);

        $user = $request->user();

        // Verificar que la contraseña actual es correcta antes de cambiar el email
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'La contraseña no es correcta'], 422);
        }

        $user->update(['email' => $request->email]);

        // Sincronizar el nuevo email en el cliente asociado
        $user->cliente?->update(['email' => $request->email]);

        return response()->json(['message' => 'Email actualizado correctamente']);
    }

    /**
     * Cambia la contraseña del usuario verificando la contraseña actual.
     * 
     * Requiere la contraseña actual para confirmar que es el propio usuario.
     * La nueva contraseña debe confirmarse enviando también 'password_confirmation'.
     * 
     * @route PUT /api/perfil/password
     * @param Request $request — password_actual, password (nueva), password_confirmation
     * @return JsonResponse mensaje de confirmación | 422 si la contraseña actual es incorrecta
     */
    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required',
            'password'        => ['required', 'confirmed', Password::min(8)], // mínimo 8 caracteres y debe venir password_confirmation
        ]);

        $user = $request->user();

        // Verificar que la contraseña actual es correcta
        if (!Hash::check($request->password_actual, $user->password)) {
            return response()->json(['message' => 'La contraseña actual no es correcta'], 422);
        }

        // Guardar la nueva contraseña encriptada
        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Contraseña actualizada correctamente']);
    }

    /**
     * Elimina la cuenta del usuario verificando la contraseña actual.
     * 
     * Antes de eliminar el usuario revoca todos sus tokens de Sanctum
     * y elimina el perfil de cliente asociado.
     * Esta operación es irreversible.
     * 
     * @route DELETE /api/perfil
     * @param Request $request — password (actual para confirmar)
     * @return JsonResponse mensaje de confirmación | 422 si la contraseña es incorrecta
     */
    public function eliminar(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = $request->user();

        // Verificar la contraseña antes de permitir la eliminación
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'La contraseña no es correcta'], 422);
        }

        // Revocar todos los tokens de Sanctum del usuario
        $user->tokens()->delete();

        // Eliminar el perfil de cliente asociado
        $user->cliente?->delete();

        // Eliminar el usuario
        $user->delete();

        return response()->json(['message' => 'Cuenta eliminada correctamente']);
    }
}