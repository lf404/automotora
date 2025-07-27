<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log; // Importante para depurar

class VehiculoController extends Controller
{
    /**
     * Muestra el catálogo de vehículos disponibles.
     */
    public function index(): View
    {
        // Usamos el alias correcto y limpio desde nuestro archivo de configuración.
        $apiUrl = config('ords.vehiculos_list_endpoint');
        
        // Log para saber qué URL estamos usando realmente. Muy útil para depurar.
        Log::info('Llamando a la API de lista de vehículos: ' . $apiUrl);
        
        $response = Http::get($apiUrl);
        
        $vehiculos = [];
        if ($response->successful()) {
            $vehiculos = $response->json()['items'] ?? [];
        } else {
            // Si la llamada falla, lo registramos en el log para saber por qué.
            Log::error('Fallo al obtener la lista de vehículos.', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
        }

        return view('welcome', ['vehiculos' => $vehiculos]);
    }

    /**
     * Muestra la página de detalle de un vehículo específico.
     */
    public function show($id): View
    {
        // Usamos el alias de configuración de detalle y le añadimos el ID.
        $apiUrl = config('ords.vehiculo_detail_endpoint') . $id;

        Log::info('Llamando a la API de detalle de vehículo: ' . $apiUrl);

        $response = Http::get($apiUrl);

        if (!$response->successful() || empty($response->json())) {
            Log::error('Fallo al obtener el detalle del vehículo con ID: ' . $id, [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            abort(404, 'Vehículo no encontrado');
        }

        $vehiculo = $response->json();

        return view('vehiculo.show', ['vehiculo' => $vehiculo]);
    }
}