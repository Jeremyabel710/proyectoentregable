<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta; 
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $clientes = Cliente::when($query, function ($queryBuilder) use ($query) {
            return $queryBuilder->where('nombre', 'like', "%{$query}%")
                ->orWhere('correo', 'like', "%{$query}%")
                ->orWhere('telefono', 'like', "%{$query}%")
                ->orWhere('direccion', 'like', "%{$query}%");
        })->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required']);
        Cliente::create($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente creado con éxito.');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nombre' => 'required']);
        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado con éxito.');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
    
        // Eliminar todos los detalles de venta relacionados a las ventas del cliente
        foreach ($cliente->ventas as $venta) {
            DetalleVenta::where('id_venta', $venta->id_venta)->delete();
        }
    
        // Eliminar todas las ventas del cliente
        Venta::where('id_cliente', $cliente->id_cliente)->delete();
    
        // Finalmente, eliminar al cliente
        $cliente->delete();
    
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado junto con todas sus ventas y detalles.');
    }
    
    
}
