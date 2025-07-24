<!-- Reutilizamos el layout básico de welcome.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle: {{ $vehiculo['marca'] }} {{ $vehiculo['modelo'] }}</title>
    <!-- Reutilizamos los mismos estilos -->
    <style>
        body { font-family: sans-serif; line-height: 1.6; margin: 2em; background-color: #f4f4f4; color: #333; }
        .container { max-width: 960px; margin: auto; background: white; padding: 2em; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1, h2 { color: #005c9e; }
        img { max-width: 100%; height: auto; border-radius: 5px; margin-bottom: 1em; }
        .precio { font-size: 1.8em; font-weight: bold; color: #28a745; }
        .comprar-btn { display: inline-block; background: #28a745; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; margin-top: 10px; font-size: 1.2em;}
        .volver-link { display: block; margin-top: 2em; }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $vehiculo['marca'] }} {{ $vehiculo['modelo'] }} ({{ $vehiculo['ano'] }})</h1>

        @if (isset($vehiculo['foto_url']))
            <img src="{{ $vehiculo['foto_url'] }}" alt="Foto de {{ $vehiculo['marca'] }} {{ $vehiculo['modelo'] }}">
        @endif

        <p class="precio">Precio Final: ${{ number_format($vehiculo['precio'], 0, ',', '.') }}</p>
        <p><strong>Descripción:</strong></p>
        <p>{{ $vehiculo['descripcion'] }}</p>

        {{-- Este botón lleva a la página de confirmación (checkout) --}}
        <a href="{{ route('checkout.show', ['vehiculo' => $vehiculo['id']]) }}" class="comprar-btn">Iniciar Compra Ahora</a>

        <a href="{{ route('home') }}" class="volver-link">← Volver al catálogo</a>
    </div>
</body>
</html>