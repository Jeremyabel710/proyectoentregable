@extends('adminlte::page')

@section('title', 'Crear Venta')

@section('content_header')
    <h1>Crear Venta</h1>
@stop

@section('content')
    <form action="{{ route('ventas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="producto_id">Producto</label>
            <select name="producto_id" id="producto_id" class="form-control">
                @foreach($productos as $producto)
                    <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                @endforeach
            </select>
            @error('producto_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="cantidad_vendida">Cantidad Vendida</label>
            <input type="number" name="cantidad_vendida" id="cantidad_vendida" class="form-control" value="{{ old('cantidad_vendida') }}">
            @error('cantidad_vendida')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="precio_total">Precio Total</label>
            <input type="text" name="precio_total" id="precio_total" class="form-control" value="{{ old('precio_total') }}">
            @error('precio_total')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@stop
