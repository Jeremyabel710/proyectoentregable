@extends('adminlte::page')

@section('title', 'Ventas')

@section('content_header')
<div class="table">
    <h1>Clientes</h1>
    <form method="GET" action="{{ route('clientes.index') }}">
        <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">Crear Cliente</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
            <tr>
                <td>{{ $cliente->id_cliente }}</td>
                <td>{{ $cliente->nombre }}</td>
                <td>{{ $cliente->correo }}</td>
                <td>{{ $cliente->telefono }}</td>
                <td>{{ $cliente->direccion }}</td>
                <td>
                    <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $clientes->links() }}
</div>
@endsection
