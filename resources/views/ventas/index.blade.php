@extends('adminlte::page')

@section('title', 'Ventas')

@section('content_header')
<div class="table">
    <h1>Ventas</h1>
    <form method="GET" action="{{ route('ventas.index') }}">
        <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
    <a href="{{ route('ventas.create') }}" class="btn btn-primary">Crear Venta</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>ID Cliente</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ventas as $venta)
            <tr>
                <td>{{ $venta->id_venta }}</td>
                <td>{{ $venta->fecha_venta }}</td>
                <td>{{ $venta->cliente->nombre }}</td>
                <td>{{ $venta->total }}</td>
                <td>
                    <a href="{{ route('ventas.edit', $venta->id_venta) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('ventas.destroy', $venta->id_venta) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $ventas->links() }}
</div>
@endsection
