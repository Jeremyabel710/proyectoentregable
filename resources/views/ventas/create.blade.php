@extends('adminlte::page')

@section('title', 'Ventas')

@section('content_header')
<div class="container">
    <h1>Crear Venta</h1>
    <form method="POST" action="{{ route('ventas.store') }}">
        @csrf
        <label for="id_cliente">Cliente:</label>
        <select name="id_cliente" required class="form-control mb-2">
            @foreach ($clientes as $cliente)
                <option value="{{ $cliente->id_cliente }}">{{ $cliente->nombre }}</option>
            @endforeach
        </select>
        
        <label for="fecha_venta">Fecha:</label>
        <input type="date" name="fecha_venta" required class="form-control mb-2">

        <h3>Detalles de Venta</h3>
        <div id="detalles">
            <div class="detalle row mb-2">
                <div class="col-md-4">
                    <label for="id_producto">Producto:</label>
                    <select name="id_producto[]" class="producto form-control" required onchange="actualizarPrecio(this)">
                        <option value="">Seleccione un producto</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id_producto }}" data-precio="{{ $producto->precio }}" data-stock="{{ $producto->stock }}">
                                {{ $producto->nombre_producto }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" name="cantidad[]" class="cantidad form-control" required min="1" onchange="validarStock(this)">
                </div>
                <div class="col-md-3">
                    <label for="precio_unitario">Precio Unitario:</label>
                    <input type="number" name="precio_unitario[]" class="precio_unitario form-control" step="0.01" readonly>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <!-- Botón para eliminar detalle -->
                    <button type="button" class="btn btn-danger btnEliminarDetalle" onclick="eliminarDetalle(this)">Eliminar</button>
                </div>
            </div>
        </div>

        <button type="button" id="btnAgregarDetalle" class="btn btn-primary mt-2" onclick="agregarDetalle()">Agregar Detalle</button>
        <button type="submit" class="btn btn-success mt-2">Guardar</button>
    </form>
    <a href="{{ route('ventas.index') }}" class="btn btn-secondary mt-2">Regresar</a>
</div>

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
<script>
// Función para contar cuántos productos se han agregado
function contarProductosSeleccionados() {
    return document.querySelectorAll('.detalle').length;
}

// Función para obtener el número total de productos disponibles
function obtenerTotalProductos() {
    return {{ count($productos) }};
}

function actualizarPrecio(select) {
    const selectedOption = select.options[select.selectedIndex];
    const precio = selectedOption.getAttribute('data-precio');
    const stock = selectedOption.getAttribute('data-stock');

    const productoSeleccionado = select.value;
    if (productoYaSeleccionado(productoSeleccionado, select)) {
        alert('Este producto ya ha sido seleccionado.');
        select.value = "";
        return;
    }

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
    const totalDetalles = contarProductosSeleccionados();

    // Verificar si hay más de un detalle antes de permitir eliminar
    if (totalDetalles > 1) {
        const detalle = button.closest('.detalle');
        detalle.remove();
    } else {
        alert('Debe haber al menos un producto en la venta.');
    }
}

function productoYaSeleccionado(idProducto, currentSelect) {
    const productosSeleccionados = document.querySelectorAll('.producto');
    for (let i = 0; i < productosSeleccionados.length; i++) {
        if (productosSeleccionados[i] !== currentSelect && productosSeleccionados[i].value == idProducto) {
            return true;
        }
    }
    return false;
}
</script>
@endsection
