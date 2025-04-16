<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class precio extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'monto'
    ];

    public function variante(){
        return $this->belongsTo(variante::class, 'variante_id');
    }

    public function producto(){
        return $this->belongsTo(producto::class, 'producto_id');
    }
}
