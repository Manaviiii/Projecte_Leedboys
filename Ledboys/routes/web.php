<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->prefix('dev')->name('pagos.')->group(function () {
    Route::get('/pagos/tester',   fn() => view('pagos.tester'))   ->name('tester');
    Route::get('/pagos/historial', fn() => view('pagos.historial'))->name('historial-view');
});

Route::get('/login', fn() => view('login'))->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dev/pagos/tester');
    }

    return back()
        ->withInput($request->only('email'))
        ->with('error', 'Credenciales incorrectas.');

})->name('login.post');

// ── Logout ─────────────────────────────────────────────────────────────────
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
