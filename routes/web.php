<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;

Route::get('/', function () {
    return view('auth.login');
});

// Rutas de recursos para productos y ventas
Route::resource('productos', ProductoController::class);

Route::resource('ventas', VentaController::class);

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
