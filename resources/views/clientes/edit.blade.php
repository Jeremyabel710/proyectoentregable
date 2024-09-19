@extends('adminlte::page')

@section('title', 'Editar Cliente')

@section('content_header')
<div class="container">
    <h1>Editar Cliente</h1>
    <form method="POST" action="{{ route('clientes.update', $cliente->id_cliente) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" value="{{ $cliente->nombre }}" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="correo">Correo:</label>
            <input type="email" name="correo" value="{{ $cliente->correo }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" value="{{ $cliente->telefono }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" value="{{ $cliente->direccion }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
    <a href="{{ route('clientes.index') }}" class="btn btn-secondary mt-2">Regresar</a>
</div>
@endsection

@section('css')
<style>
    .container {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    h1 {
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .btn {
        padding: 10px 15px;
        border-radius: 5px;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>
@endsection
