<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cliente;

/**
 * Controlador de autenticación.
 * Gestiona el registro, login, logout y datos del usuario autenticado.
 */
class AuthController extends Controller
{
    /**
     * Inicia sesión con email y contraseña.
     *
     * Valida las credenciales contra la base de datos.
     * Si son correctas, genera un token de acceso con Sanctum
     * y lo devuelve junto con los datos básicos del usuario.
     *
     * @route POST /api/login
     * @param Request $request — email, password
     * @return JsonResponse token + datos del usuario | 401 si las credenciales son incorrectas
     */
    public function login(Request $request)
    {
        // Validar que llegan los campos necesarios
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Intentar autenticar con las credenciales recibidas
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Obtener el usuario autenticado y generar su token de API
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

    /**
     * Registra un nuevo usuario y crea su perfil de cliente asociado.
     *
     * Crea el User para la autenticación y automáticamente
     * crea un Cliente vinculado con los mismos datos,
     * que se usará para eventos, residencias y pagos.
     *
     * @route POST /api/registro
     * @param Request $request — name, email, password, password_confirmation, telefono (opcional)
     * @return JsonResponse token + datos del usuario | 422 si hay errores de validación
     */
    public function registro(Request $request)
    {
        // Validar los datos del formulario de registro
        // 'confirmed' exige que llegue también password_confirmation con el mismo valor
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
        ]);

        // Crear el usuario con la contraseña encriptada y rol 'user' por defecto
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        // Crear el perfil de cliente vinculado al usuario recién creado
        // Este perfil se usará para gestionar eventos, residencias y pagos
        Cliente::create([
            'user_id'  => $user->id,
            'nombre'   => $request->name,
            'email'    => $request->email,
            'telefono' => $request->telefono,
        ]);

        // Generar token de acceso para que el usuario quede autenticado directamente
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

    /**
     * Cierra la sesión del usuario autenticado.
     *
     * Elimina únicamente el token con el que se está haciendo
     * la petición actual, no todos los tokens del usuario.
     *
     * @route POST /api/logout
     * @param Request $request
     * @return JsonResponse mensaje de confirmación
     */
    public function logout(Request $request)
    {
        // Revocar solo el token actual (no afecta a otras sesiones abiertas)
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    /**
     * Devuelve los datos del usuario autenticado junto con su perfil de cliente.
     *
     * Útil para que el frontend pueda mostrar la información
     * del usuario sin tener que hacer dos peticiones separadas.
     *
     * @route GET /api/me
     * @param Request $request
     * @return JsonResponse datos del usuario + cliente asociado
     */
    public function me(Request $request)
    {
        // Cargar el usuario con su cliente asociado en una sola consulta
        $user = $request->user()->load('cliente');

        return response()->json($user);
    }
}