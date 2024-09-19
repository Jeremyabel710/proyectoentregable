@extends('adminlte::page')

@section('title', 'Ventas')

@section('content_header')
<div class="table">
    <h1>Detalles de Venta</h1>
    <form method="GET" action="{{ route('detalles_venta.index') }}">
        <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
    <a href="{{ route('detalles_venta.create') }}" class="btn btn-primary">Crear Detalle de Venta</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Venta</th>
                <th>ID Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalles as $detalle)
            <tr>
                <td>{{ $detalle->id_detalle }}</td>
                <td>{{ $detalle->id_venta }}</td>
                <td>{{ $detalle->id_producto }}</td>
                <td>{{ $detalle->cantidad }}</td>
                <td>{{ $detalle->precio_unitario }}</td>
                <td>{{ $detalle->subtotal }}</td>
                <td>
                    <a href="{{ route('detalles_venta.edit', $detalle->id_detalle) }}" class="btn btn-warning">Detalles</a>
                    <form action="{{ route('detalles_venta.destroy', $detalle->id_detalle) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $detalles->links() }}
</div>
@endsection
