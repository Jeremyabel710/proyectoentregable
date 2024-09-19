@extends('adminlte::page')

@section('title', 'Editar Producto')

@section('content_header')
<div class="container">
    <h1>Editar Producto</h1>
    <form method="POST" action="{{ route('productos.update', $producto->id_producto) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nombre_producto">Nombre:</label>
            <input type="text" name="nombre_producto" class="form-control" value="{{ $producto->nombre_producto }}" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <input type="text" name="descripcion" class="form-control" value="{{ $producto->descripcion }}">
        </div>

        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" name="precio" class="form-control" value="{{ $producto->precio }}" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" name="stock" class="form-control" value="{{ $producto->stock }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
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
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 100%;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 10px 15px;
        border-radius: 5px;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        padding: 10px 15px;
        border-radius: 5px;
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>
@endsection
