<?php

// config/ords.php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración de Servicios ORDS
    |--------------------------------------------------------------------------
    |
    | Este archivo centraliza las credenciales para nuestra API de Oracle.
    | Lee los valores del archivo .env de forma segura.
    |
    */

    'base_url'      => env('ORDS_BASE_URL'),
    'client_id'     => env('ORDS_CLIENT_ID'),
    'client_secret' => env('ORDS_CLIENT_SECRET'),
    'pedidos_endpoint' => env('ORDS_BASE_URL') . '/v1/pedidos', // ¡Asegúrate de que incluya /v1/!
];