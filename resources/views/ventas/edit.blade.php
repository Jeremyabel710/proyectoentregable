@extends('adminlte::page')

@section('title', 'Editar Venta')

@section('content_header')
<div class="container">
    <h1>Editar Venta</h1>
    <form method="POST" action="{{ route('ventas.update', $venta->id_venta) }}">
        @csrf
        @method('PUT')

        <label for="id_cliente">Cliente:</label>
        <select name="id_cliente" class="form-control">
            @foreach($clientes as $cliente)
                <option value="{{ $cliente->id_cliente }}" {{ $venta->id_cliente == $cliente->id_cliente ? 'selected' : '' }}>{{ $cliente->nombre }}</option>
            @endforeach
        </select>

        <label for="fecha_venta">Fecha:</label>
        <input type="date" name="fecha_venta" class="form-control" value="{{ $venta->fecha_venta }}">

        <h3>Detalles de Venta</h3>
        <div id="detalles">
            @foreach ($venta->detalles as $detalle)
            <div class="detalle row mb-2">
                <div class="col-md-4">
                    <label for="id_producto">Producto:</label>
                    <select name="id_producto[]" class="producto form-control" required onchange="actualizarPrecio(this)">
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id_producto }}" data-precio="{{ $producto->precio }}" data-stock="{{ $producto->stock }}"
                                {{ $producto->id_producto == $detalle->id_producto ? 'selected' : '' }}>
                                {{ $producto->nombre_producto }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" name="cantidad[]" class="cantidad form-control" value="{{ $detalle->cantidad }}" required min="1" onchange="validarStock(this)">
                </div>
                <div class="col-md-3">
                    <label for="precio_unitario">Precio Unitario:</label>
                    <input type="number" name="precio_unitario[]" class="precio_unitario form-control" value="{{ $detalle->precio_unitario }}" step="0.01" readonly>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger" onclick="eliminarDetalle(this)">Eliminar</button>
                </div>
            </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-primary mt-2" onclick="agregarDetalle()">Agregar Detalle</button>
        <button type="submit" class="btn btn-success mt-2">Guardar</button>
    </form>
    <a href="{{ route('ventas.index') }}" class="btn btn-secondary mt-2">Regresar</a>
</div>
@endsection

@section('css')

<style>
    .detalle {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 10px;
        background-color: #f9f9f9;
    }

    .detalle:last-child {
        margin-bottom: 0;
    }

    .btnEliminarDetalle {
        margin-top: 5px;
    }

    #detalles {
        margin-top: 15px;
    }
</style>
@endsection

@section('js')
<script>
function contarProductosSeleccionados() {
    return document.querySelectorAll('.detalle').length;
}

function obtenerTotalProductos() {
    return {{ count($productos) }};
}

function actualizarPrecio(select) {
    const selectedOption = select.options[select.selectedIndex];
    const precio = selectedOption.getAttribute('data-precio');
    const stock = selectedOption.getAttribute('data-stock');

    const detalle = select.closest('.detalle');
    detalle.querySelector('.precio_unitario').value = precio;
    detalle.querySelector('.cantidad').max = stock;
}

function validarStock(input) {
    const cantidad = parseInt(input.value);
    const maxStock = parseInt(input.max);

    if (cantidad > maxStock) {
        alert('La cantidad excede el stock disponible.');
        input.value = maxStock;
    }
}

function agregarDetalle() {
    const totalProductos = obtenerTotalProductos();
    const productosSeleccionados = contarProductosSeleccionados();

    if (productosSeleccionados >= totalProductos) {
        alert('No puedes agregar más productos, ya has alcanzado el límite.');
        return;
    }

    let detalles = document.getElementById('detalles');
    let nuevoDetalle = detalles.firstElementChild.cloneNode(true);

    nuevoDetalle.querySelector('.producto').selectedIndex = 0;
    nuevoDetalle.querySelector('.cantidad').value = '';
    nuevoDetalle.querySelector('.precio_unitario').value = '';

    detalles.appendChild(nuevoDetalle);
}

function eliminarDetalle(button) {
    const detalle = button.closest('.detalle');
    const cantidadEliminada = parseInt(detalle.querySelector('.cantidad').value);
    const productoId = detalle.querySelector('.producto').value;

    // Aumentar el stock del producto eliminado
    const producto = document.querySelector(`option[value="${productoId}"]`);
    const stockActual = parseInt(producto.getAttribute('data-stock'));
    producto.setAttribute('data-stock', stockActual + cantidadEliminada); // Actualizar el stock en el DOM

    detalle.remove();
}
</script>
@endsection
