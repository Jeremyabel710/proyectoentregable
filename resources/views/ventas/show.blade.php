@extends('adminlte::page')

@section('title', 'Ver Venta')

@section('content_header')
    <h1>Ver Venta</h1>
@stop

@section('content')
    <p><strong>ID:</strong> {{ $venta->id }}</p>
    <p><strong>Producto:</strong> {{ $venta->producto->nombre }}</p>
    <p><strong>Cantidad Vendida:</strong> {{ $venta->cantidad_vendida }}</p>
    <p><strong>Precio Total:</strong> {{ $venta->formatted_total }}</p>
    <p><strong>Fecha de Creación:</strong> {{ $venta->created_at }}</p>
    <p><strong>Última Actualización:</strong> {{ $venta->updated_at }}</p>
    <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver</a>
@stop
