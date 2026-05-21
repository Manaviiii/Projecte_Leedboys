<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrajeController;
use App\Http\Controllers\AccesorioController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\ResidenciaController;
use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\FotoController;
use App\Http\Controllers\ReservaController;


// Auth (sin middleware)
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/registro', [AuthController::class, 'registro']);

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Perfil del usuario autenticado
    Route::get('/perfil',             [PerfilController::class, 'ver']);
    Route::put('/perfil',             [PerfilController::class, 'actualizar']);
    Route::put('/perfil/email',       [PerfilController::class, 'cambiarEmail']);
    Route::put('/perfil/password',    [PerfilController::class, 'cambiarPassword']);
    Route::delete('/perfil',          [PerfilController::class, 'eliminar']);

    // Clientes
    Route::apiResource('/clientes', ClienteController::class);

    // Eventos
    Route::apiResource('eventos', EventoController::class);

    // Items
    Route::apiResource('/items', ItemController::class)->except(['update']);
    Route::put('items/{item}',   [ItemController::class, 'update']);
    Route::patch('items/{item}', [ItemController::class, 'update']);

    // Residencias
    Route::apiResource('residencias', ResidenciaController::class);

    // Pagos (no tiene destroy)
    Route::apiResource('pagos', PagoController::class)->except(['destroy']);

    Route::get('/reservas',      [ReservaController::class, 'index']);
    Route::get('/reservas/{id}', [ReservaController::class, 'detalle']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

#region TRAJES
Route::get('/trajes',                  [TrajeController::class, 'index']);
Route::get('/trajes/buscar',           [TrajeController::class, 'buscar']);
Route::get('/trajes/filtrar/{genero}', [TrajeController::class, 'filtrarPorGenero']);
Route::get('/trajes/{id}',             [TrajeController::class, 'mostrarTraje']);
#endregion

#region ACCESORIOS
Route::get('/accesorios',        [AccesorioController::class, 'index']);
Route::get('/accesorios/buscar', [AccesorioController::class, 'buscar']);
Route::get('/accesorios/{id}',   [AccesorioController::class, 'mostrar']);
#endregion

#region PACKS
Route::get('/packs',        [PackController::class, 'index']);
Route::get('/packs/buscar', [PackController::class, 'buscar']);
Route::get('/packs/{id}',   [PackController::class, 'mostrar']);
#endregion

#region PAYMENTS
Route::middleware('auth:sanctum')->prefix('pagos')->group(function () {
    Route::post('/crear-intento',  [PaymentController::class, 'crearIntento']);
    Route::post('/{id}/confirmar', [PaymentController::class, 'confirmarPago']);
    Route::get('/',                [PaymentController::class, 'historial']);
    Route::get('/{id}',            [PaymentController::class, 'detalle']);
    Route::post('/{id}/reembolso', [PaymentController::class, 'reembolso']);
});

// Webhook de Stripe — SIN autenticación
Route::post('/stripe/webhook', [WebhookController::class, 'handle']);
#endregion

Route::get('/fotos',             [FotoController::class, 'principales']);
Route::get('/fotos/traje/{id}',  [FotoController::class, 'porTraje']);
