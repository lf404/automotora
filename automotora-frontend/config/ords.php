<?php

// config/ords.php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración de Servicios ORDS
    |--------------------------------------------------------------------------
    |
    | Este archivo centraliza las credenciales y las URLs para nuestra API.
    | Lee los valores del archivo .env de forma segura.
    |
    */

    // --- CREDENCIALES ---
    'client_id'     => env('ORDS_CLIENT_ID'),
    'client_secret' => env('ORDS_CLIENT_SECRET'),
    
    // --- URLs COMPLETAS DE LOS ENDPOINTS ---
    // Hemos escrito las URLs completas aquí para máxima claridad,
    // ya que tu 'base_url' podría ser diferente para otros módulos.

    // Endpoint para obtener la LISTA de vehículos
    'vehiculos_list_endpoint'   => 'https://ge3810f3f6838ef-automotoradb.adb.sa-santiago-1.oraclecloudapps.com/ords/automotora_ws/v1/vehiculos',
    
    // Endpoint para obtener el DETALLE de un vehículo. Dejamos la barra al final para añadir el ID.
    'vehiculo_detail_endpoint'  => 'https://ge3810f3f6838ef-automotoradb.adb.sa-santiago-1.oraclecloudapps.com/ords/automotora_ws/v1/vehiculos/', 
    
    // Endpoint para CREAR un nuevo pedido.
    'pedidos_create_endpoint'   => 'https://ge3810f3f6838ef-automotoradb.adb.sa-santiago-1.oraclecloudapps.com/ords/automotora_ws/v1/pedidos',

    // Endpoint para la obtención del token OAuth2
    'oauth_token_endpoint'      => 'https://ge3810f3f6838ef-automotoradb.adb.sa-santiago-1.oraclecloudapps.com/ords/automotora_ws/oauth/token',
];