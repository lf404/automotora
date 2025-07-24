<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class VehiculoController extends Controller
{
    public function show($id): View
    {
        // NOTA: Para AUTO-REST, el endpoint es diferente, suele ser la URL base + alias de tabla + ID
        $apiUrl = 'https://<ID-ALEATORIO>.adb.region.oraclecloudapps.com/ords/automotora/vehiculos/' . $id;

        $response = Http::get($apiUrl);

        // ¡IMPORTANTE! ORDS puede devolver un array vacío si no encuentra el ID, o un error si el ID es inválido.
        // Haremos un manejo de errores robusto aquí.
        if (!$response->successful() || empty($response->json())) {
        // Si el vehículo no se encuentra o hay un error, redirigimos al usuario al inicio.
        // La función abort() muestra una página de error estándar.
        abort(404, 'Vehículo no encontrado');
        }

        $vehiculo = $response->json();

        return view('vehiculo.show', ['vehiculo' => $vehiculo]);
    }
}