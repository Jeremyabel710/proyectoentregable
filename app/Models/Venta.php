<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    // Define el nombre de la tabla si no sigue la convención de Laravel
    protected $table = 'ventas';

    // Los campos que son asignables en masa
    protected $fillable = [
        'producto_id',
        'cantidad_vendida',
        'precio_total',
    ];

    // Los campos que no deberían ser asignables en masa
    protected $guarded = [];

    // Si los timestamps no están presentes en la tabla, desactívalos
    public $timestamps = true;

    // Define las relaciones, si las hay
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    // Mutadores y accesores si es necesario
    public function setCantidadVendidaAttribute($value)
    {
        $this->attributes['cantidad_vendida'] = (int) $value;
    }

    public function setPrecioTotalAttribute($value)
    {
        $this->attributes['precio_total'] = round($value, 2);
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->precio_total, 2);
    }
}
