<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $ventas = Venta::when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('id_cliente', 'like', "%{$query}%")
                ->orWhere('total', 'like', "%{$query}%");
        })->paginate(10);
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        return view('ventas.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'fecha_venta' => 'required|date',
            'id_producto.*' => 'required|exists:productos,id_producto',
            'cantidad.*' => 'required|integer|min:1',
        ]);
    
        // Crear la venta
        $venta = new Venta();
        $venta->id_cliente = $request->id_cliente;
        $venta->fecha_venta = $request->fecha_venta;
        $venta->total = 0; // El total se calculará al final
        $venta->save();
    
        $total = 0;
    
        // Procesar cada detalle de la venta
        foreach ($request->id_producto as $index => $idProducto) {
            $producto = Producto::find($idProducto);
            $cantidad = $request->cantidad[$index];
            $precioUnitario = $producto->precio; // Precio del producto
    
            // Validar que la cantidad no exceda el stock
            if ($producto->stock < $cantidad) {
                return redirect()->back()->withErrors([
                    'cantidad' => "La cantidad solicitada para el producto '{$producto->nombre_producto}' excede el stock disponible ({$producto->stock} unidades).",
                ]);
            }
    
            // Crear el detalle de la venta
            $detalle = new DetalleVenta();
            $detalle->id_venta = $venta->id_venta;
            $detalle->id_producto = $producto->id_producto;
            $detalle->cantidad = $cantidad;
            $detalle->precio_unitario = $precioUnitario;
            $detalle->save();
    
            // Restar la cantidad del stock del producto
            $producto->stock -= $cantidad;
            $producto->save();
    
            // Sumar al total de la venta
            $total += $cantidad * $precioUnitario;
        }
    
        // Actualizar el total de la venta
        $venta->total = $total;
        $venta->save();
    
        return redirect()->route('ventas.index')->with('success', 'Venta registrada correctamente.');
    }

    public function edit($id)
    {
        $venta = Venta::with('detalles')->findOrFail($id);
        $clientes = Cliente::all();
        $productos = Producto::all();
    
        return view('ventas.edit', compact('venta', 'clientes', 'productos'));
    }
    

    public function update(Request $request, $id)
    {
        // Obtener la venta
        $venta = Venta::findOrFail($id);
        $detallesAntiguos = $venta->detalles;
    
        // Obtener los productos actuales y los nuevos productos del formulario
        $productosNuevos = $request->input('id_producto', []);
        $cantidadesNuevas = $request->input('cantidad', []);
        $preciosUnitarios = $request->input('precio_unitario', []);
    
        // Recorrer los detalles antiguos y sumar el stock de los productos eliminados
        foreach ($detallesAntiguos as $detalle) {
            $producto = Producto::find($detalle->id_producto);
            $producto->stock += $detalle->cantidad; // Devolver el stock al eliminar
            $producto->save();
        }
    
        // Limpiar detalles existentes
        DetalleVenta::where('id_venta', $id)->delete();
    
        // Si no hay nuevos productos, eliminar la venta y redirigir
        if (count($productosNuevos) == 0) {
            $venta->delete();  // Eliminar la venta si no quedan productos
            return redirect()->route('ventas.index')->with('success', 'Venta eliminada porque no quedan productos.');
        }
    
        // Variable para calcular el nuevo total
        $nuevoTotal = 0;
    
        // Si hay nuevos productos, agregar detalles de venta y actualizar el total
        foreach ($productosNuevos as $index => $idProducto) {
            $cantidad = $cantidadesNuevas[$index];
            $precioUnitario = $preciosUnitarios[$index];
            $subtotal = $cantidad * $precioUnitario;
    
            // Verificar si el stock es suficiente
            $producto = Producto::find($idProducto);
            if ($producto->stock < $cantidad) {
                return redirect()->back()->withErrors(['Stock insuficiente para el producto: ' . $producto->nombre_producto]);
            }
    
            // Crear nuevo detalle de venta
            DetalleVenta::create([
                'id_venta' => $venta->id_venta,
                'id_producto' => $idProducto,
                'cantidad' => $cantidad,
                'precio_unitario' => $precioUnitario,
                'subtotal' => $subtotal,
            ]);
    
            // Restar del stock disponible
            $producto->stock -= $cantidad;
            $producto->save();
    
            // Sumar al nuevo total de la venta
            $nuevoTotal += $subtotal;
        }
    
        // Actualizar el total de la venta con el nuevo total calculado
        $venta->total = $nuevoTotal;
        $venta->save();
    
        return redirect()->route('ventas.index')->with('success', 'Venta actualizada correctamente.');
    }
    
    

    public function destroy($id)
    {
        // Encontrar la venta
        $venta = Venta::findOrFail($id);
    
        // Recuperar los detalles de la venta
        $detalles = $venta->detalles;
    
        // Restaurar el stock de cada producto
        foreach ($detalles as $detalle) {
            $producto = Producto::find($detalle->id_producto);
            if ($producto) {
                // Aumentar el stock del producto en la cantidad vendida
                $producto->stock += $detalle->cantidad;
                $producto->save();
            }
        }
    
        // Eliminar los detalles de venta relacionados
        $venta->detalles()->delete();
    
        // Eliminar la venta
        $venta->delete();
    
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada y stock restaurado con éxito.');
    }
    
}
