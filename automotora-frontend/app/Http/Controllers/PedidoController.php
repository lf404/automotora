<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\PedidoController; // ¡Importa el nuevo controlador!

// Muestra el catálogo de todos los vehículos
Route::get('/', [VehiculoController::class, 'index'])->name('home');

// Muestra la página de detalle de un vehículo específico
// {vehiculo} es un parámetro de ruta. El valor se pasará al método 'show'.
Route::get('/vehiculo/{vehiculo}', [VehiculoController::class, 'show'])->name('vehiculo.show');

// Muestra la página de confirmación del pedido (el "checkout")
Route::get('/checkout/{vehiculo}', [PedidoController::class, 'checkout'])->name('checkout.show');

// Procesa el pedido. Esta es la ruta a la que el formulario de checkout hará POST.
Route::post('/pedido', [PedidoController::class, 'store'])->name('pedido.store');

// Página de éxito después de la compra
Route::get('/pedido/exito', [PedidoController::class, 'success'])->name('pedido.success');