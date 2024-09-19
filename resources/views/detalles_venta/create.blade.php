@extends('adminlte::page')

@section('title', 'Crear Detalle de Venta')

@section('content_header')
    <h1>Crear Detalle de Venta</h1>
@endsection

@section('content')
<div class="container">
    <form method="POST" action="{{ route('detalles_venta.store') }}">
        @csrf

        <!-- Selección de Cliente -->
        <label for="id_cliente">Cliente:</label>
        <select name="id_cliente" required class="form-control mb-2">
            <option value="">Seleccione un cliente</option>
            @foreach ($clientes as $cliente)
                <option value="{{ $cliente->id_cliente }}">{{ $cliente->nombre }}</option>
            @endforeach
        </select>

        <!-- Fecha de Venta -->
        <label for="fecha_venta">Fecha de Venta:</label>
        <input type="date" name="fecha_venta" required class="form-control mb-2">

        <h3>Detalles de Venta</h3>

        <div id="detalles">
            <div class="detalle row mb-2">
                <!-- Selección de Producto -->
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

                <!-- Cantidad del Producto -->
                <div class="col-md-2">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" name="cantidad[]" class="cantidad form-control" required min="1" onchange="validarStock(this)">
                </div>

                <!-- Precio Unitario -->
                <div class="col-md-2">
                    <label for="precio_unitario">Precio Unitario:</label>
                    <input type="number" name="precio_unitario[]" class="precio_unitario form-control" step="0.01" readonly>
                </div>

                <!-- Subtotal del Producto -->
                <div class="col-md-2">
                    <label for="subtotal">Subtotal:</label>
                    <input type="number" name="subtotal[]" class="subtotal form-control" step="0.01" readonly>
                </div>

                <!-- Botón para eliminar el detalle -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btnEliminarDetalle" onclick="eliminarDetalle(this)">Eliminar</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary mt-2" onclick="agregarDetalle()">Agregar Detalle</button>

        <h4>Total de la Venta: <span id="total">0.00</span></h4>

        <button type="submit" class="btn btn-success mt-2">Guardar Detalles</button>
    </form>
    <a href="{{ route('detalles_venta.index') }}" class="btn btn-secondary mt-2">Regresar</a>
</div>

<!-- Scripts para manejar la lógica del frontend -->
<script>
    function actualizarPrecio(select) {
        const selectedOption = select.options[select.selectedIndex];
        const precio = selectedOption.getAttribute('data-precio');
        const stock = selectedOption.getAttribute('data-stock');

        const detalle = select.closest('.detalle');
        detalle.querySelector('.precio_unitario').value = precio;
        detalle.querySelector('.cantidad').max = stock;

        calcularSubtotal(detalle.querySelector('.cantidad'));
    }

    function validarStock(input) {
        const detalle = input.closest('.detalle');
        const select = detalle.querySelector('.producto');
        const selectedOption = select.options[select.selectedIndex];
        const stock = parseInt(selectedOption.getAttribute('data-stock'));
        const cantidad = parseInt(input.value);

        if (cantidad > stock) {
            alert('La cantidad solicitada excede el stock disponible. Stock actual: ' + stock);
            input.value = stock;
        }

        calcularSubtotal(input);
    }

    function calcularSubtotal(input) {
        const detalle = input.closest('.detalle');
        const cantidad = parseFloat(detalle.querySelector('.cantidad').value) || 0;
        const precioUnitario = parseFloat(detalle.querySelector('.precio_unitario').value) || 0;

        const subtotal = cantidad * precioUnitario;
        detalle.querySelector('.subtotal').value = subtotal.toFixed(2);

        actualizarTotal();
    }

    function actualizarTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(function(subtotalInput) {
            total += parseFloat(subtotalInput.value) || 0;
        });

        document.getElementById('total').innerText = total.toFixed(2);
    }

    function agregarDetalle() {
        let detalles = document.getElementById('detalles');
        let nuevoDetalle = detalles.firstElementChild.cloneNode(true);

        nuevoDetalle.querySelector('.producto').selectedIndex = 0;
        nuevoDetalle.querySelector('.cantidad').value = '';
        nuevoDetalle.querySelector('.precio_unitario').value = '';
        nuevoDetalle.querySelector('.subtotal').value = '';

        detalles.appendChild(nuevoDetalle);
    }

    function eliminarDetalle(button) {
        const detalle = button.closest('.detalle');
        detalle.remove();

        actualizarTotal();
    }
</script>
@endsection

@section('css')
<style>
    .container {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        margin-top: 20px;
    }

    .detalle {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 5px;
        background-color: #f5f5f5;
        margin-bottom: 10px;
    }

    .btnEliminarDetalle {
        margin-top: 5px;
    }

    h4 {
        margin-top: 20px;
    }
</style>
@endsection
