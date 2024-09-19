@extends('adminlte::page')

@section('title', 'Crear Cliente')

@section('content_header')
<div class="container">
    <h1>Crear Cliente</h1>
    <form method="POST" action="{{ route('clientes.store') }}">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="correo">Correo:</label>
            <input type="email" name="correo" class="form-control">
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" class="form-control">
        </div>

        <div class="form-group">
            <label for="direccion">Dirección:</label>
            <input type="text" name="direccion" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
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

    .btn-success {
        background-color: #28a745;
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
