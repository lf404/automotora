<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Exception; // Importamos la clase base de Excepción para un mejor manejo

class PedidoController extends Controller
{
    /**
     * Muestra la página de checkout/confirmación del pedido.
     * Este método no cambia, solo se queda como estaba.
     */
    public function checkout($vehiculoId): View
    {
        $apiUrl = config('ords.base_url') . '/v1/vehiculos/' . $vehiculoId;
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
     * ESTE ES EL MÉTODO QUE VAMOS A CAMBIAR COMPLETAMENTE.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehiculo_id' => 'required|integer',
            'precio_final' => 'required|numeric',
        ]);

        try {
            // 1. Obtenemos un cliente HTTP ya configurado con el token de acceso.
            $httpClient = $this->getAuthenticatedOrdsClient();

            // 2. Usamos ese cliente para hacer la llamada POST al endpoint correcto.
            $apiUrl = config('ords.pedidos_endpoint');

            Log::info("Enviando pedido a la API protegida: " . $apiUrl);

            $response = $httpClient->post($apiUrl, [
                'cliente_id'  => 1, // El ID de nuestro cliente de prueba
                'vehiculo_id' => $validated['vehiculo_id'],
                'precio_final'  => $validated['precio_final'],
            ]);

            // 3. Verificamos la respuesta de la API. El throw() convierte errores (4xx, 5xx) en excepciones.
            $response->throw();

            // 4. Si todo salió bien (la API devolvió un código 2xx), redirigimos al éxito.
            return redirect()->route('pedido.success');

        } catch (Exception $e) {
            // 5. Si algo falla (obtener el token O enviar el pedido), lo capturamos.
            Log::error('FALLO CRÍTICO AL PROCESAR PEDIDO: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString() // Añadimos más detalles al log para depuración.
            ]);

            return back()->withErrors(['api_error' => 'No hemos podido procesar tu pedido en este momento. Nuestro equipo técnico ha sido notificado.']);
        }
    }

    /**
     * Obtiene un token de acceso de ORDS y devuelve un cliente HTTP con el token.
     * Utiliza la caché para no solicitar un token en cada petición.
     */
    private function getAuthenticatedOrdsClient(): PendingRequest
    {
        $token = Cache::remember('ords_oauth_token', 3500, function () {
            $tokenUrl = config('ords.base_url') . '/oauth/token';

            Log::info("Cache de token vacía. Solicitando nuevo token de ORDS en: " . $tokenUrl);

            $response = Http::asForm()->post($tokenUrl, [
                'grant_type'    => 'client_credentials',
                'client_id'     => config('ords.client_id'),
                'client_secret' => config('ords.client_secret'),
            ]);

            // Si la petición del token falla, se lanzará una excepción que será
            // capturada por el bloque try-catch en el método store().
            $response->throw();

            Log::info("Nuevo token obtenido y cacheado.");

            return $response->json('access_token');
        });

        // Devolvemos una instancia del cliente HTTP con el token ya incluido
        // en la cabecera 'Authorization: Bearer <token>'.
        return Http::withToken($token);
    }


    /**
     * Muestra la página de agradecimiento. Se queda igual.
     */
    public function success(): View
    {
        return view('pedido.success');
    }
}