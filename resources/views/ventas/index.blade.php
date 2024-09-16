@extends('adminlte::page')

@section('title', 'Ventas')

@section('content_header')
    <h1>Ventas</h1>
@stop

@section('content')
    <p><a href="{{ route('ventas.create') }}" class="btn btn-primary">Crear Venta</a></p>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Cantidad Vendida</th>
                <th>Precio Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
                <tr>
                    <td>{{ $venta->id }}</td>
                    <td>{{ $venta->producto->nombre }}</td>
                    <td>{{ $venta->cantidad_vendida }}</td>
                    <td>{{ $venta->formatted_total }}</td>
                    <td>
                        <a href="{{ route('ventas.show', $venta) }}" class="btn btn-info">Ver</a>
                        <a href="{{ route('ventas.edit', $venta) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('ventas.destroy', $venta) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
