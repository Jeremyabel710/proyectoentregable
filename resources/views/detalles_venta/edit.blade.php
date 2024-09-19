@extends('adminlte::page')

@section('title', 'Ver Detalle de Venta')

@section('content_header')
    <h1>Ver Detalle de Venta</h1>
@endsection

@section('content')
<div class="container">
    <h3>Detalles del Cliente y Venta</h3>

    <!-- Nombre del Cliente -->
    <label for="cliente">Cliente:</label>
    <p>{{ $detalle->venta->cliente->nombre }}</p>

    <!-- Fecha de la Venta -->
    <label for="fecha_venta">Fecha de Venta:</label>
    <p>{{ $detalle->venta->fecha_venta }}</p>

    <h3>Detalles del Producto</h3>

    <div id="detalles" class="detalle">
        <!-- Producto -->
        <label for="producto">Producto:</label>
        <p>{{ $detalle->producto->nombre_producto }}</p>

        <!-- Cantidad del Producto -->
        <label for="cantidad">Cantidad:</label>
        <p>{{ $detalle->cantidad }}</p>

        <!-- Precio Unitario -->
        <label for="precio_unitario">Precio Unitario:</label>
        <p>{{ number_format($detalle->precio_unitario, 2) }}</p>

        <!-- Subtotal del Producto -->
        <label for="subtotal">Subtotal:</label>
        <p>{{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</p>
    </div>

    <a href="{{ route('detalles_venta.index') }}" class="btn btn-primary mt-3">Regresar</a>
</div>
@endsection

@section('css')
<style>
    h1 {
        text-align: center;
    }
    .container {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    h3 {
        margin-bottom: 15px;
        font-size: 20px;
        color: #333;
    }

    label {
        font-weight: bold;
        margin-top: 10px;
    }

    p {
        margin: 5px 0 15px;
    }

    .detalle {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 5px;
        background-color: #f5f5f5;
        margin-bottom: 20px;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
</style>
@endsection
