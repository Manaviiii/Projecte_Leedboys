<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrajeController;
use App\Http\Controllers\AccesorioController;
use App\Http\Controllers\PackController;

/*
IMPORTANTE:

Las urls de las rutas que contengan el parámetro {id} deben 
estar por debajo de las rutas que no contienen el parámetro {id}.
Sino ocurrirá un error en la ruta que contiene el parámetro {id}.
*/

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


