<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Controlador base de la aplicación.
 * Todos los controladores extienden de esta clase.
 * 
 * Incluye tres traits de Laravel:
 * - AuthorizesRequests: permite usar políticas de autorización con $this->authorize()
 * - DispatchesJobs: permite despachar trabajos a la cola con $this->dispatch()
 * - ValidatesRequests: permite validar peticiones con $this->validate()
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}