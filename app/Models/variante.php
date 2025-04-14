<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class variante extends Model
{
    use HasFactory;
    protected $fillable = [
        'codigo',
        'sku',
        'nombre',
        'presentacion',
        'cantidad',
        'unidad',
        'descripcion',
        'producto_id',
    ];

    public function precios(){
        return $this->hasMany(precio::class, 'variante_id');
    }
}
