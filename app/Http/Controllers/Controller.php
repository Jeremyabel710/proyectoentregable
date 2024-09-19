<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /* public function index(Request $request)
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
    } */
}
