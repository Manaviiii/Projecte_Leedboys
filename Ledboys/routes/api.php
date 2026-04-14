<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrajeController;
use App\Http\Controllers\AccesorioController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\ResidenciaController;
use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;

/*
IMPORTANTE:

Las urls de las rutas que contengan el parámetro {id} deben 
estar por debajo de las rutas que no contienen el parámetro {id}.
Sino ocurrirá un error en la ruta que contiene el parámetro {id}.
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Clientes
    Route::apiResource('clientes', ClienteController::class);

    // Eventos
    Route::apiResource('eventos', EventoController::class);

    // Items (trajes + accesorios + packs unificados)

    //API RESOURCE:
    //Api resource unifica 
    // Método       URL             Acción (Método en Controlador)      Propósito
    // GET          /items,         index           Listar todos los ítems
    // POST         /items,         store           Crear un nuevo ítem
    // GET          /items/{item}   show            Ver un ítem específico
    // PUT/PATCH    /items/{item}   update          Actualizar un ítem
    // DELETE       /items/{item}   destroy,        Eliminar un ítem
    Route::apiResource('items', ItemController::class)->except(['update']);
    Route::put('items/{item}',   [ItemController::class, 'update']);
    Route::patch('items/{item}', [ItemController::class, 'update']);

    // Residencias
    Route::apiResource('residencias', ResidenciaController::class);

    // Pagos (no tiene destroy — los pagos no se borran, solo se actualizan)
    Route::apiResource('pagos', PagoController::class)->except(['destroy']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

#region TRAJES

//Mostrar todos los trajes (catalogo)
Route::get('/trajes', TrajeController::class . '@index');

//Buscar un traje por nombre / descripcion
Route::get('/trajes/buscar', TrajeController::class . '@buscar');

//Mostrar un traje
Route::get('/trajes/{id}', TrajeController::class . '@mostrarTraje');

//Buscar un traje por genero
Route::get('/trajes/filtrar/{genero}', TrajeController::class . '@filtrarPorGenero');

#endregion

#region ACCESORIOS

//Mostrar todos los accesorios (catalogo)
Route::get('/accesorios', AccesorioController::class . '@index');

//Buscar un accesorio por nombre / descripcion
Route::get('/accesorios/buscar', AccesorioController::class . '@buscar');

//Mostrar un accesorio
Route::get('/accesorios/{id}', AccesorioController::class . '@mostrar');

#endregion

#region PACKS

//Mostrar todos los packs (catalogo)
Route::get('/packs', PackController::class . '@index');

//Buscar un pack por nombre / descripcion
Route::get('/packs/buscar', PackController::class . '@buscar');

//Mostrar un pack
Route::get('/packs/{id}', PackController::class . '@mostrar');

#endregion

#region PAYMENTS

Route::middleware('auth:sanctum')->prefix('pagos')->group(function () {

    // Crear intento de pago
    Route::post('/crear-intento', [PaymentController::class, 'crearIntento']);

    // Confirmar pago tras completarse en el frontend
    Route::post('/{id}/confirmar', [PaymentController::class, 'confirmarPago']);

    // Historial de pagos del usuario autenticado
    Route::get('/', [PaymentController::class, 'historial']);

    // Detalle de un pago concreto
    Route::get('/{id}', [PaymentController::class, 'detalle']);

    // Solicitar reembolso
    Route::post('/{id}/reembolso', [PaymentController::class, 'reembolso']);
});

/*
|--------------------------------------------------------------------------
| Webhook de Stripe — SIN autenticación Bearer (usa firma HMAC propia)
| IMPORTANTE: excluir de CSRF en bootstrap/app.php o VerifyCsrfToken
|--------------------------------------------------------------------------
*/
Route::post('/stripe/webhook', [WebhookController::class, 'handle']);

#endregion


