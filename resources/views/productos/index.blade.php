@extends('adminlte::page')

@section('title', 'Ventas')

@section('content_header')
<div class="table">
    <h1>Productos</h1>
    <form method="GET" action="{{ route('productos.index') }}">
        <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
    <a href="{{ route('productos.create') }}" class="btn btn-primary">Crear Producto</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
            <tr>
                <td>{{ $producto->id_producto }}</td>
                <td>{{ $producto->nombre_producto }}</td>
                <td>{{ $producto->descripcion }}</td>
                <td>{{ $producto->precio }}</td>
                <td>{{ $producto->stock }}</td>
                <td>
                    <a href="{{ route('productos.edit', $producto->id_producto) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('productos.destroy', $producto->id_producto) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $productos->links() }}
</div>
@endsection
