<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    // Mostrar una lista de todas las ventas
    public function index(Request $request)
    {
        $search = $request->get('search');
    
        // Obtén las ventas y filtra según la búsqueda
        $ventas = Venta::with('producto') // Carga el producto asociado
            ->when($search, function ($query) use ($search) {
                return $query->where('cantidad_vendida', 'LIKE', "%$search%")
                    ->orWhere('id', 'LIKE', "%$search%")
                    ->orWhere('precio_total', 'LIKE', "%$search%")
                    ->orWhere('producto_id', 'LIKE', "%$search%")
                    ->orWhereHas('producto', function ($query) use ($search) {
                        $query->where('nombre', 'LIKE', "%$search%"); // Filtra por el nombre del producto
                    });
            })
            ->paginate(10); // Cambia el número a la cantidad de registros por página que desees
    
        // Comprobar si la solicitud es AJAX
        if ($request->ajax()) {
            return response()->json(['data' => $ventas]);
        }
    
        // Pasar las ventas a la vista
        return view('ventas.index', compact('ventas', 'search'));
    } 
    
    // Mostrar el formulario para crear una nueva venta
    public function create()
    {
        $productos = Producto::all();
        return view('ventas.create', compact('productos'));
    }

    // Almacenar una nueva venta
    public function store(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad_vendida' => 'required|integer|min:1',
            'precio_total' => 'required|numeric|min:0',
        ]);

        Venta::create([
            'producto_id' => $request->input('producto_id'),
            'cantidad_vendida' => $request->input('cantidad_vendida'),
            'precio_total' => $request->input('precio_total'),
        ]);

        return redirect()->route('ventas.index')->with('success', 'Venta creada con éxito.');
    }

    // Mostrar los detalles de una venta específica
    public function show(Venta $venta)
    {
        return view('ventas.show', compact('venta'));
    }

    // Mostrar el formulario para editar una venta existente
    public function edit(Venta $venta)
    {
        $productos = Producto::all();
        return view('ventas.edit', compact('venta', 'productos'));
    }

    // Actualizar una venta existente
    public function update(Request $request, Venta $venta)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad_vendida' => 'required|integer|min:1',
            'precio_total' => 'required|numeric|min:0',
        ]);

        $venta->update([
            'producto_id' => $request->input('producto_id'),
            'cantidad_vendida' => $request->input('cantidad_vendida'),
            'precio_total' => $request->input('precio_total'),
        ]);

        return redirect()->route('ventas.index')->with('success', 'Venta actualizada con éxito.');
    }

    // Eliminar una venta existente
    public function destroy(Venta $venta)
    {
        $venta->delete();
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada con éxito.');
    }
}
