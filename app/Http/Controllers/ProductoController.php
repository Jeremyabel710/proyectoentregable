<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $productos = Producto::when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('nombre_producto', 'like', "%{$query}%")
                ->orWhere('descripcion', 'like', "%{$query}%");
        })->paginate(10);
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nombre_producto' => 'required', 'precio' => 'required|numeric', 'stock' => 'required|integer']);
        Producto::create($request->all());
        return redirect()->route('productos.index')->with('success', 'Producto creado con éxito.');
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nombre_producto' => 'required', 'precio' => 'required|numeric', 'stock' => 'required|integer']);
        $producto = Producto::findOrFail($id);
        $producto->update($request->all());
        return redirect()->route('productos.index')->with('success', 'Producto actualizado con éxito.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado con éxito.');
    }
}
