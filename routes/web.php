<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DetalleVentaController;

Route::get('/', function () {
    return view('auth.login');
});

Route::resource('clientes', ClienteController::class);
Route::resource('productos', ProductoController::class);
Route::resource('ventas', VentaController::class);
Route::resource('detalles_venta', DetalleVentaController::class);

// Rutas protegidas por autenticación
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('productos.index');
    })->name('dashboard');
});
