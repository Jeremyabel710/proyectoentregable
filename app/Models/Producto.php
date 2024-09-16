<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Define el nombre de la tabla si no sigue la convención de Laravel
    protected $table = 'productos';

    // Los campos que son asignables en masa
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'cantidad',
    ];

    // Los campos que no deberían ser asignables en masa
    protected $guarded = [];

    // Si los timestamps están presentes en la tabla, mantenlos activados
    public $timestamps = true;

    // Define los tipos de los atributos para evitar problemas
    protected $casts = [
        'precio' => 'decimal:2',
        'cantidad' => 'integer',
    ];

    // Mutadores para modificar atributos antes de guardarlos en la base de datos
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = ucfirst($value);
    }

    public function setPrecioAttribute($value)
    {
        $this->attributes['precio'] = round($value, 2);
    }

    // Accesores para obtener atributos modificados
    public function getFormattedPriceAttribute()
    {
        return number_format($this->precio, 2);
    }

    public function getShortDescriptionAttribute()
    {
        return strlen($this->descripcion) > 50 
            ? substr($this->descripcion, 0, 50) . '...' 
            : $this->descripcion;
    }
}
