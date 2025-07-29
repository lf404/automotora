<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehiculoController; // ¡Importa nuestro controlador!
use App\Http\Controllers\PedidoController;   // ¡Importa nuestro controlador!
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Públicas de la Automotora (Nuestras Rutas)
|--------------------------------------------------------------------------
*/
// ESTA ES LA RUTA QUE RESTAURAMOS: La página de inicio DEBE ser manejada por VehiculoController.

Route::get('/', [VehiculoController::class, 'index'])->name('home');

Route::get('/vehiculo/{vehiculo}', [VehiculoController::class, 'show'])->name('vehiculo.show');
Route::get('/checkout/{vehiculo}', [PedidoController::class, 'checkout'])->name('checkout.show');
Route::post('/pedido', [PedidoController::class, 'store'])->name('pedido.store');

// ESTA ES LA LÍNEA CORREGIDA
Route::get('/pedido/exito', [PedidoController::class, 'success'])->name('pedido.success');
/*
|--------------------------------------------------------------------------
| Rutas de Autenticación y Dashboard (Añadidas por Breeze)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';