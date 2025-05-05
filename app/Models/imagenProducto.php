<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class imagenProducto extends Model
{
    use HasFactory;
    protected $fillable = [
        'ruta',
        'variante_id',
        'producto_id',
    ];

    public function productos(){
        return $this->belongsTo(producto::class, 'producto_id');
    }
}
