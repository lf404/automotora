<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class VehiculoController extends Controller
{
    /**
     * Muestra el catálogo de vehículos disponibles.
     * ESTE ES EL MÉTODO QUE FALTABA O ESTABA MAL ESCRITO.
     */
    public function index(): View
    {
        // Construimos la URL a partir de nuestra configuración segura.
        $apiUrl = config('ords.base_url') . '/v1/vehiculos';

        $response = Http::get($apiUrl);
        
        $vehiculos = [];
        // Verificamos si la respuesta fue exitosa ANTES de intentar decodificar el JSON.
        if ($response->successful()) {
            // La API de ORDS envuelve la lista en una clave 'items'.
            // El '?? []' es un seguro por si 'items' no viniera en la respuesta.
            $vehiculos = $response->json()['items'] ?? [];
        }

        // Pasamos la lista (o un array vacío si falló) a la vista.
        return view('welcome', ['vehiculos' => $vehiculos]);
    }

    /**
     * Muestra la página de detalle de un vehículo específico.
     */
    public function show($id): View
    {
        $apiUrl = config('ords.base_url') . '/v1/vehiculos/' . $id;

        $response = Http::get($apiUrl);

        // Si la respuesta no es exitosa o el cuerpo está vacío, mostramos un error 404.
        if (!$response->successful() || empty($response->json())) {
            abort(404, 'Vehículo no encontrado');
        }

        $vehiculo = $response->json();

        return view('vehiculo.show', ['vehiculo' => $vehiculo]);
    }
}