<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    public $timestamps = false; // Desactiva timestamps automáticos

    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    protected $fillable = ['nombre_producto', 'descripcion', 'precio', 'stock'];

    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class, 'id_producto');
    }
}
