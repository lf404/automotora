<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Automotora "El Puente" - Nuestro Catálogo</title>
    <!-- Aquí irían los estilos CSS en el futuro -->
    <style>
        body { font-family: sans-serif; line-height: 1.6; margin: 2em; }
        .container { max-width: 1000px; margin: auto; }
        .vehiculo-card { border: 1px solid #ddd; padding: 1.5em; margin-bottom: 2em; border-radius: 8px; }
        .vehiculo-card img { max-width: 100%; height: auto; border-radius: 4px; }
        .precio { font-size: 1.2em; font-weight: bold; color: #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nuestro Inventario</h1>
        @forelse ($vehiculos as $vehiculo)
        <div>
            <h2>{{ $vehiculo['marca'] }} {{ $vehiculo['modelo'] }} ({{ $vehiculo['ano'] }})</h2>
            <p>Precio: ${{ number_format($vehiculo['precio'], 2) }}</p>
            <img src="{{ $vehiculo['foto_url'] }}" alt="Foto del vehículo" width="300">
            <p>{{ $vehiculo['descripcion'] }}</p>
            <a href="{{ route('vehiculo.show', ['vehiculo' => $vehiculo['id']]) }}" class="comprar-btn">Ver Detalles y Comprar</a>
        </div>
        <hr>
        @empty
        <p>No hay vehículos disponibles en este momento.</p>
        @endforelse
    </div>
</body>
</html>