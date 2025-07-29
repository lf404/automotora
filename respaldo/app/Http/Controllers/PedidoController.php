<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Exception;

class PedidoController extends Controller
{
    /**
     * Muestra la página de checkout/confirmación del pedido.
     */
    public function checkout($vehiculoId): View
    {
        // Usamos el alias de configuración de detalle y le añadimos el ID.
        $apiUrl = config('ords.vehiculo_detail_endpoint') . $vehiculoId;

        $response = Http::get($apiUrl);

        if (!$response->successful() || empty($response->json())) {
            abort(404, 'Vehículo no encontrado para el checkout.');
        }

        $vehiculo = $response->json();

        if (isset($vehiculo['estado']) && $vehiculo['estado'] !== 'DISPONIBLE') {
            abort(403, 'Este vehículo ya no está disponible para la venta.');
        }

        return view('pedido.checkout', ['vehiculo' => $vehiculo]);
    }

    /**
     * Almacena un nuevo pedido en la base de datos a través de la API protegida.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehiculo_id' => 'required|integer',
            'precio_final' => 'required|numeric',
        ]);

        try {
            $httpClient = $this->getAuthenticatedOrdsClient();

            // Usamos el alias correcto para crear pedidos.
            $apiUrl = config('ords.pedidos_create_endpoint');

            Log::info("Enviando pedido a la API protegida: " . $apiUrl);

            $response = $httpClient->post($apiUrl, [
                'cliente_id'  => 1, // El ID de nuestro cliente de prueba
                'vehiculo_id' => $validated['vehiculo_id'],
                'precio_final'  => $validated['precio_final'],
            ]);

            $response->throw();

            return redirect()->route('pedido.success');

        } catch (Exception $e) {
            Log::error('FALLO CRÍTICO AL PROCESAR PEDIDO: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['api_error' => 'No hemos podido procesar tu pedido en este momento. Nuestro equipo técnico ha sido notificado.']);
        }
    }

    /**
     * Obtiene un token de acceso de ORDS y devuelve un cliente HTTP con el token.
     */
    private function getAuthenticatedOrdsClient(): PendingRequest
    {
        $token = Cache::remember('ords_oauth_token', 3500, function () {
            // Usamos el alias correcto para el endpoint del token.
            $tokenUrl = config('ords.oauth_token_endpoint');

            Log::info("Cache de token vacía. Solicitando nuevo token de ORDS en: " . $tokenUrl);

            $response = Http::asForm()->post($tokenUrl, [
                'grant_type'    => 'client_credentials',
                'client_id'     => config('ords.client_id'),
                'client_secret' => config('ords.client_secret'),
            ]);

            $response->throw();

            Log::info("Nuevo token obtenido y cacheado.");

            return $response->json('access_token');
        });

        return Http::withToken($token);
    }

    /**
     * Muestra la página de agradecimiento.
     */
    public function success(): View
    {
        return view('pedido.success');
    }
}