<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // Muestra la lista de productos
    public function index(Request $request)
    {
        $search = $request->get('search');
        $productos = Producto::when($search, function ($query) use ($search) {
            return $query->where('nombre', 'LIKE', "%$search%")
                        ->orWhere('id', 'LIKE', "%$search%")
                        ->orWhere('precio', 'LIKE', "%$search%")
                        ->orWhere('cantidad', 'LIKE', "%$search%")
                        ->orWhere('descripcion', 'LIKE', "%$search%"); // Ajusta según tus columnas
        })->get();

        // Comprobar si la solicitud es AJAX
        if ($request->ajax()) {
            return response()->json(['data' => $productos]);
        }

        // Pasar los productos a la vista
        return view('productos.index', ['productos' => $productos, 'search' => $search]);
    }

    // Muestra el formulario para crear un nuevo producto
    public function create()
    {
        return view('productos.create'); // Muestra el formulario de creación
    }

    // Almacena un nuevo producto en la base de datos
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0',
        ]);

        Producto::create($validated);

        return redirect()->route('productos.index')
                         ->with('success', 'Producto creado con éxito.');
    }

    // Muestra un producto específico para editarlo
    public function edit(Producto $producto)
    {
        return view('productos.edit', ['producto' => $producto]); // Pasa el producto a la vista de edición
    }

    // Actualiza un producto específico en la base de datos
    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0',
        ]);

        $producto->update($validated);

        return redirect()->route('productos.index')
                         ->with('success', 'Producto actualizado con éxito.');
    }

    // Elimina un producto específico de la base de datos
    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')
                         ->with('success', 'Producto eliminado con éxito.');
    }
}
