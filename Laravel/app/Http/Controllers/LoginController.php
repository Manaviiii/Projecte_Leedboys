<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    
    ///<summary>
    ///Muestra el login
    ///</summary>
        public function mostraLogin()
    {
        return view('login'); // apunta a resources/views/login.blade.php
    }

    ///<summary>
    ///Intenta hacer login
    ///</summary>
    public function login(Request $request)
    {
        // Validación básica
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        //Intenta validar contra la base de datos
        // Si se pudo validar, se redirige a la ruta /dashboard
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); // ruta tras login
        }
        // Si es que no se pudo validar, muestra el formulario de login otra vez
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden.',
        ])->onlyInput('email'); // solo muestra el campo de email que habia escrito el usuario
    }

    ///<summary>
    ///hace logout
    ///</summary>
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
