@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
<div class="container">
    <h1>Crear Producto</h1>
    <form method="POST" action="{{ route('productos.store') }}">
        @csrf
        <div class="form-group">
            <label for="nombre_producto">Nombre:</label>
            <input type="text" name="nombre_producto" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <input type="text" name="descripcion" class="form-control">
        </div>

        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" name="precio" class="form-control" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" name="stock" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
    <a href="{{ route('productos.index') }}" class="btn btn-secondary mt-2">Regresar</a>
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

    .btn-success {
        padding: 10px 15px;
        border-radius: 5px;
        background-color: #28a745;
        border: none;
    }

    .btn-secondary {
        padding: 10px 15px;
        border-radius: 5px;
        background-color: #6c757d;
        border: none;
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>
@endsection
