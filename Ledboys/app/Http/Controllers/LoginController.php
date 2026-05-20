<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador de autenticación para el panel de administración (Filament).
 * Gestiona el login y logout web con sesiones, a diferencia del AuthController
 * que usa tokens Sanctum para la API.
 */
class LoginController extends Controller
{
    /**
     * Muestra el formulario de login del panel de administración.
     * 
     * @route GET /login
     * @return View vista login.blade.php
     */
    public function mostraLogin()
    {
        return view('login');
    }

    /**
     * Procesa el formulario de login y redirige al dashboard si las credenciales son correctas.
     * 
     * Valida los campos, intenta autenticar con Auth::attempt y regenera
     * la sesión para prevenir ataques de fijación de sesión.
     * Si las credenciales son incorrectas, vuelve al formulario con el error.
     * 
     * @route POST /login
     * @param Request $request — email, password
     * @return RedirectResponse /dashboard si éxito | /login con error si falla
     */
    public function login(Request $request)
    {
        // Validar que los campos sean correctos antes de intentar el login
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentar autenticar contra la base de datos
        if (Auth::attempt($credentials)) {
            // Regenerar el ID de sesión para evitar ataques de fijación de sesión
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Si las credenciales no coinciden, volver al login con el error
        // onlyInput('email') conserva el email escrito para no tener que repetirlo
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario y redirige al login.
     * 
     * Invalida la sesión actual y regenera el token CSRF
     * para evitar reutilización de sesiones cerradas.
     * 
     * @route POST /logout
     * @param Request $request
     * @return RedirectResponse /login
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidar la sesión y regenerar el token CSRF por seguridad
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}