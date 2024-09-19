<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $detalles = DetalleVenta::when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('id_venta', 'like', "%{$query}%")
                ->orWhere('id_producto', 'like', "%{$query}%");
        })->paginate(10);
        return view('detalles_venta.index', compact('detalles'));
    }

    public function create()
    {
        // Obtener todos los clientes y productos
        $clientes = Cliente::all();
        $productos = Producto::all();
        
        // Pasar ambas variables a la vista
        return view('detalles_venta.create', compact('clientes', 'productos'));
    }


    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'fecha_venta' => 'required|date',
            'id_producto.*' => 'required|exists:productos,id_producto',
            'cantidad.*' => 'required|integer|min:1',
            'precio_unitario.*' => 'required|numeric|min:0.01',
        ]);
    
        // Verificar si ya existe una venta para el cliente en la fecha dada
        $venta = Venta::where('id_cliente', $request->id_cliente)
            ->where('fecha_venta', $request->fecha_venta)
            ->first();
    
        // Si no existe una venta, crear una nueva
        if (!$venta) {
            $venta = new Venta();
            $venta->id_cliente = $request->id_cliente;
            $venta->fecha_venta = $request->fecha_venta;
            $venta->total = 0; // El total se calculará después
            $venta->save();
        }
    
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
    
            // Sumar al total de la venta (subtotal por detalle)
            $total += $cantidad * $precioUnitario;
        }
    
        // Actualizar el total de la venta
        $venta->total += $total; // Sumar al total existente
        $venta->save();
    
        return redirect()->route('detalles_venta.index')->with('success', 'Detalle de venta registrado correctamente');
    }
    
    


    public function edit($id)
    {
        $detalle = DetalleVenta::findOrFail($id);
        $clientes = Cliente::all(); // Obtener todos los clientes
        $productos = Producto::all(); // Obtener todos los productos
        return view('detalles_venta.edit', compact('detalle', 'clientes', 'productos'));
    }


    public function update(Request $request, $id)
    {
        // Validar la solicitud
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'id_producto' => 'required|exists:productos,id_producto',
            'cantidad' => 'required|integer|min:1',
        ]);

        // Buscar el detalle de venta que se está editando
        $detalle = DetalleVenta::findOrFail($id);
        $producto = Producto::find($request->id_producto);
        
        // Obtener la cantidad actual del detalle
        $cantidadActual = $detalle->cantidad;

        // Calcular nueva cantidad de stock
        $producto->stock = $producto->stock + $cantidadActual - $request->cantidad;

        // Validar que la nueva cantidad no exceda el stock
        if ($producto->stock < 0) {
            return redirect()->back()->withErrors([
                'cantidad' => "La cantidad solicitada para el producto '{$producto->nombre_producto}' excede el stock disponible ({$producto->stock} unidades).",
            ]);
        }

        // Cambiar cliente si es diferente
        if ($detalle->id_cliente != $request->id_cliente) {
            // Verificar si ya existe una venta para el nuevo cliente
            $venta = Venta::where('id_cliente', $request->id_cliente)->first();

            // Si no existe una venta, crear una nueva
            if (!$venta) {
                $venta = new Venta();
                $venta->id_cliente = $request->id_cliente;
                $venta->fecha_venta = now(); // O la fecha que desees
                $venta->total = 0; // El total se calculará después
                $venta->save();
            }

            // Cambiar el id_venta del detalle a la nueva venta
            $detalle->id_venta = $venta->id_venta;
        }

        // Actualizar el detalle
        $detalle->id_producto = $request->id_producto;
        $detalle->cantidad = $request->cantidad;
        $detalle->precio_unitario = $producto->precio; // Actualiza el precio si es necesario
        $detalle->save();

        // Actualizar el stock del producto
        $producto->stock -= $request->cantidad; // Restar la nueva cantidad
        $producto->save();

        // Calcular el nuevo total de la venta
        $total = DetalleVenta::where('id_venta', $detalle->id_venta)->sum(DB::raw('cantidad * precio_unitario'));
        
        // Actualizar el total de la venta
        $venta->total = $total;
        $venta->save();

        return redirect()->route('detalles_venta.index')->with('success', 'Detalle de venta actualizado correctamente');
    }

    public function destroy($id)
    {
        $detalle = DetalleVenta::findOrFail($id);
        $venta = $detalle->venta; // Venta asociada al detalle
    
        // Actualizar el stock del producto antes de eliminar el detalle
        $producto = Producto::findOrFail($detalle->id_producto);
        $producto->stock += $detalle->cantidad; // Devolver la cantidad al stock
        $producto->save();
    
        // Eliminar el detalle de venta
        $detalle->delete();
    
        // Recalcular el total de la venta después de eliminar el detalle
        $totalVenta = DetalleVenta::where('id_venta', $venta->id_venta)
                                ->sum(DB::raw('cantidad * precio_unitario'));
    
        // Si la venta ya no tiene otros detalles, eliminarla
        if ($totalVenta == 0) {
            $venta->delete();
        } else {
            // Actualizar el total de la venta si aún tiene detalles
            $venta->total = $totalVenta;
            $venta->save();
        }
    
        return redirect()->route('detalles_venta.index')->with('success', 'Detalle de venta eliminado con éxito.');
    }
    
}
